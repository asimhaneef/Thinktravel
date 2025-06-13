<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    public function index()
    {
        return Category::all();
    }
    //
    public function category_with_posts()
    {
        $data = Category::with(['posts.files'])->orderBy('created_at', 'desc')->get();

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(data $data)
    {
        //

        // Validate and store category data

        // Example usage to upload a single file
        $file = $this->uploadFile($request, 'file_field_name', 'path_to_store_files');

        // Example usage to upload multiple files
        // $files = $this->uploadMultipleFiles($request, 'files_field_name', 'path_to_store_files');

        // Save category with file details if needed
        $category = new Category();
        $category->name = $request->name;
        $category->file_path = $file; // Adjust as per your file storage logic
        $category->save();
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
