<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTestingRequest;
use App\Http\Requests\UpdateTestingRequest;
use App\Models\Testing;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
//use Illuminate\Auth\Access\Response;
use App\Http\Resources\TestingResource;
use App\Http\Resources\TestingCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TestingNotification;
use App\Events\TestingUpdateEvent;
use App\Models\User;


class TestingController extends Controller
{
    protected $collection = 'collection-testing';

    public function __construct()
    {
        
    }

    public function test(Request $request)
    {
        // Check if user has the right access
        if (!$request->user()->tokenCan('read')) {
            return response()->json([
                'message' => 'This action is unauthorized.',
                'errors' => '403',
                'action' => [
                    "read" => $request->user()->tokenCan("read"),
                    "create" => $request->user()->tokenCan("create"),
                    "update" => $request->user()->tokenCan("update"),
                    "delete" => $request->user()->tokenCan("delete")
                ],
                'status' => 'failed',
            ]);
        }

        // Default response
        $default = collect([
            'message' => 'TestingController is working',
            'status' => 'success',
            'user' => array_merge_recursive($request->user()->toArray(), 
                        ['abbilities' => [
                            "read" => $request->user()->tokenCan("read"),
                            "create" => $request->user()->tokenCan("create"),
                            "update" => $request->user()->tokenCan("update"),
                            "delete" => $request->user()->tokenCan("delete")
                        ]]),
            "can" => [
                    "read" => auth()->user()->tokenCan("read"),
                    "create" => auth()->user()->tokenCan("create"),
                    "update" => auth()->user()->tokenCan("update"),
                    "delete" => auth()->user()->tokenCan("delete")
            ],
            'meta' => config('api-config.meta'),
        ]);
        $collection = $default->mergeRecursive((collect(['request' => $request->all()])));
        
        return response()->json($collection->all())->header('X-Value', env('APP_HEADER_CUSTOM_VALUE'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Testing::class);

        //
        $data = Testing::all();
        $data->map(function($item) {
            $mediaItems = $item->getMedia($this->collection);
            $item->image = $mediaItems->map(function($item) {
                return $item->getUrl();
            })->first();
        });

        return (new TestingCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestingRequest $request)
    {
        Gate::authorize('create', Testing::class);        

        //
        $testing = Testing::create($request->all());

        // Receiver
        $team_id = $request->user()->currentTeam->id ?? 21;
        $users = User::where('current_team_id', $team_id)->get();
        $user_count = $users->count();

        foreach($users as $user) {
            // Notification -> sendNow | send
            if($user_count > 100) { // queue
                Notification::send($user, new TestingNotification($testing));
            }
            else
            Notification::sendNow($user, new TestingNotification($testing));
        }

        // Notifications Megaphone
        $url = url('/api/testing/'.$testing->id);
        $message = [
            'title' => 'New Data',
            'body' => sprintf('Data was created: %s Created at: %s', $testing->name, $testing->created_at),
            'url' => $url,
            'link' => 'Read More...',
        ];
        $notification = new \MBarlow\Megaphone\Types\Important(
            $message['title'], $message['body'], $message['url'], $message['link']
        );
        
        foreach($users as $user) {
            // $user->notify($notification);
        }

        // Add image
        if($request->has('image')) {
            $url = $request->image;
            $testing->addMediaFromUrl($url)
                ->preservingOriginal()
                ->sanitizingFileName(function($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection($this->collection);

            $data = Testing::findOrFail($testing->id);

            $mediaItems = $data->getMedia($this->collection);
            $data->image = $mediaItems->map(function($item) {
                return $item->getUrl();
            })->first();
    
            return (new TestingResource($data))->response();
        }

        return (new TestingResource($testing))->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(Testing $testing)
    {
        Gate::authorize('view', $testing);

        // 
        $data = Testing::findOrFail($testing->id);
        $mediaItems = $data->getMedia($this->collection);
        $data->image = $mediaItems->map(function($item) {
            return $item->getUrl();
        })->first();

        return (new TestingResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestingRequest $request, Testing $testing)
    {
        Gate::authorize('update', $testing);

        //
        try {            
            $testing->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Receiver
            $team_id = $request->user()->currentTeam->id ?? 21;
            $users = User::where('current_team_id', $team_id)->get();
            $user_count = $users->count();

            // Broadcast Event
            TestingUpdateEvent::dispatch($testing);

            // Notifications Megaphone
            $url = url('/api/testing/'.$testing->id);
            $message = [
                'title' => 'Updated Data',
                'body' => sprintf('Data was changed: %s Updated at: %s', $testing->name, $testing->updated_at),
                'url' => $url,
                'link' => 'Read More...',
            ];
            $notification = new \MBarlow\Megaphone\Types\Important(
                $message['title'], $message['body'], $message['url'], $message['link']
            );
            
            foreach($users as $user) {
                $user->notify($notification);
            }

            return new TestingResource($testing);
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Record cannot be found',
                'errors' => '404',
                'exception' => 'No query results',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testing $testing)
    {
        Gate::authorize('delete', $testing);

        //
        $title = $testing->name;
        if($testing->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Record ['.$title.'] has been deleted'
            ]);
        }
        else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Record ['.$title.'] cannot be deleted',
                'errors' => '500',
                'exception' => 'QueryException'                
            ]);
        }
    }
}
