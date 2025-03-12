<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use Illuminate\Http\Request;
use App\Models\Album;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\AlbumResource;
use App\Http\Resources\AlbumCollection;

class AlbumController extends Controller
{
    protected $mediaCollections = 'albums';

    public function search(Request $request)
    {
        Gate::authorize('viewAny', Album::class);

        // Search
        $data = Album::search($request->q)->get();

        return (new AlbumCollection($data));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        Gate::authorize('viewAny', Album::class);

        //
        $data = Album::all();

        return (new AlbumCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbumRequest $request)
    {
        // dd((bool)auth()->user()->status);
        Gate::authorize('create', Album::class);

        // Get Validated data
        $validated = $request->validated();
        $album = Album::create($validated);

        // Add image
        if($request->filled('image')) {
            $url = $request->image;
            $album->addMediaFromUrl($url)
                ->preservingOriginal()
                ->sanitizingFileName(function($fileName) {
                    return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
                })
                ->toMediaCollection($this->mediaCollections);

            // Get Media
            $mediaItems = $album->getMedia($this->mediaCollections);
            $url = isset($mediaItems[0]) ? $mediaItems[0]->getFullUrl() : null;
            $url_path = isset($mediaItems[0]) ? $mediaItems[0]->getUrl() : null;
            $dir_path = isset($mediaItems[0]) ? $mediaItems[0]->getPath() : null;

            // Save path and user_ulid
            $album->url_path = $url_path;
            $album->dir_path = $dir_path;
            $album->user_ulid = $request->user()->ulid;
            $album->save();
        }

        return (new AlbumResource($album))->response();
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        Gate::authorize('view', $album);

        // 
        $data = Album::findOrFail($album->id);

        return (new AlbumResource($data));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        Gate::authorize('update', $album);

        //
        try {
            $album->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            // Update image
            $id = $album->id;
            if($request->filled('image')) {
                // Delete old media
                $mediaItems = $album->getMedia($this->mediaCollections);
                if(isset($mediaItems[0])) {
                    $mediaItems[0]->delete();
                }

                // Generate new model
                $data = Album::findOrFail($id);
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
                $data->url_path = $url_path;
                $data->dir_path = $dir_path;
                $data->user_ulid = auth()->user()->ulid;
                $data->save();
                
                return new AlbumResource($data);
            }

            return new AlbumResource($album);
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
    public function destroy(Album $album)
    {
        Gate::authorize('delete', $album);

        //
        $title = $album->name;
        if($album->delete()) {
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
