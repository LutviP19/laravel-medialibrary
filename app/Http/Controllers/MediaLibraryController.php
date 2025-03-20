<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaLibraryRequest;
use App\Http\Requests\UpdateMediaLibraryRequest;
use Illuminate\Http\Request;
use App\Models\MediaLibrary;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\MediaLibraryResource;
use App\Http\Resources\MediaLibraryCollection;

class MediaLibraryController extends Controller
{
    protected $mediaCollections = 'media-libraries';
    protected $perPage = 15;

    public function search(Request $request)
    {
        Gate::authorize('viewAny', MediaLibrary::class);

        // Search
        $data = MediaLibrary::search($request->q)->paginate($this->perPage);

        return (new MediaLibraryCollection($data));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        Gate::authorize('viewAny', MediaLibrary::class);

        // Query Get data
        // $data = MediaLibrary::paginate($this->perPage); // N+1
        $data = MediaLibrary::with(['owner','album'])->paginate($this->perPage); // with | withOnly

        return (new MediaLibraryCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaLibraryRequest $request)
    {
        // dd((bool)auth()->user()->status);
        Gate::authorize('create', MediaLibrary::class);

        // Get Validated data
        $validated = $request->validated();
        $medium = MediaLibrary::create($validated);

        // Add image
        if($request->filled('image')) {
            $url = $request->image;
            $medium->addMediaFromUrl($url)
                ->preservingOriginal()
                ->sanitizingFileName(function($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection($this->mediaCollections);

            // Get Media
            $mediaItems = $medium->getMedia($this->mediaCollections);
            $url = isset($mediaItems[0]) ? $mediaItems[0]->getFullUrl() : null;
            $url_path = isset($mediaItems[0]) ? $mediaItems[0]->getUrl() : null;
            $dir_path = isset($mediaItems[0]) ? $mediaItems[0]->getPath() : null;

            // Save path and user_ulid
            $medium->url_path = str_replace(env('APP_URL'), '', $url_path);
            $medium->dir_path = str_replace(storage_path(), '', $dir_path);
            $medium->user_ulid = $request->user()->ulid;
            $medium->save();
        }

        return (new MediaLibraryResource($medium))->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(MediaLibrary $medium)
    {
        Gate::authorize('view', $medium);

        // dd($medium->id);
        $data = MediaLibrary::with(['owner', 'album'])->findOrFail($medium->id);

        return (new MediaLibraryResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaLibraryRequest $request, MediaLibrary $medium)
    {
        Gate::authorize('update', $medium);

        // dd($medium->id);
        try {
            $medium->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Update image
            $id = $medium->id;
            if($request->filled('image')) {
                // Delete old media
                $mediaItems = $medium->getMedia($this->mediaCollections);
                if(isset($mediaItems[0])) {
                    $mediaItems[0]->delete();
                }

                // Generate new model
                $data = MediaLibrary::findOrFail($id);
                $url = $request->image;
                $data->addMediaFromUrl($url)
                    ->preservingOriginal()
                    ->sanitizingFileName(function($fileName) {
                        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                    })
                    ->toMediaCollection($this->mediaCollections);

                // Get Media
                $mediaItems = $data->getMedia($this->mediaCollections);
                $url = isset($mediaItems[0]) ? $mediaItems[0]->getFullUrl() : null;
                $url_path = isset($mediaItems[0]) ? $mediaItems[0]->getUrl() : null;
                $dir_path = isset($mediaItems[0]) ? $mediaItems[0]->getPath() : null;

                // Save path and user_ulid
                $data->url_path = str_replace(env('APP_URL'), '', $url_path);
                $data->dir_path = str_replace(storage_path(), '', $dir_path);
                $data->user_ulid = auth()->user()->ulid;
                $data->save();
                
                return new MediaLibraryResource($data);
            }

            return new MediaLibraryResource($medium);
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
    public function destroy(MediaLibrary $medium)
    {
        Gate::authorize('delete', $medium);

        //
        $title = $medium->name;
        if($medium->delete()) {
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
