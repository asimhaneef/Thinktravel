<?php

namespace App\Services;

use App\Models\Post as ModelsPost;

class PostService
{

    public function index()
    {
        $posts = ModelsPost::with('files', 'category')->orderBy('created_at', 'desc')->get();

        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(data $data)
    {
        //

        // Validate and store Post data

        // Example usage to upload a single file
        $file = $this->uploadFile($request, 'file_field_name', 'path_to_store_files');

        // Example usage to upload multiple files
        // $files = $this->uploadMultipleFiles($request, 'files_field_name', 'path_to_store_files');

        // Save Post with file details if needed
        $Post = new Post();
        $Post->name = $request->name;
        $Post->file_path = $file; // Adjust as per your file storage logic
        $Post->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(data $data)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(data $data)
    {
        //
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(data $data)
    {
        //
    }
}
