<?php

namespace App\Http\Controllers;

use App\Models\FileManagment;
use App\Traits\FileUpload;
use App\Http\Requests\StoreFileManagmentRequest;
use App\Http\Requests\UpdateFileManagmentRequest;

class FileManagmentController extends Controller
{
    use FileUpload;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        $query = FileManagment::with('files');

        // Filtering
        if ($request->has('filters')) {
            $filters = json_decode($request->filters, true);
            foreach ($filters as $filter => $value) {
                if (!empty($value['value'])) {
                    $query->where($filter, 'like', '%' . $value['value'] . '%');
                }
            }
        }

        // Sorting
        if ($request->has('sortField') && $request->has('sortOrder')) {
            if ($request['sortOrder'] == 1) {
                $order = 'asc';
            } else {
                $order = 'desc';
            }
            $query->orderBy($request['sortField'], $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $countDocuments = $query->paginate($request->rows);

        return response()->json($countDocuments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileManagmentRequest $request)
    {
        //
        if(isset($request->id) && $request->id != "null" && $request->id > 0){
            $query = FileManagment::find($request->id);
            $query->update([
                'userid' => auth()->id(),
                'document_access' => $request->document_access,
                'document_name' => $request->document_name,
                'document_category' => $request->document_category,
                'document_description' => $request->document_description,
                'active' => $request->active == "true" ? 1 : 0,
            ]);
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $this->uploadFile($file, 'FileManagment', $query);
            }
        } else {

            // Create the post
            $document = FileManagment::create([
                    'userid' => auth()->id(),
                    'document_access' => $request->document_access,
                    'document_name' => $request->document_name,
                    'document_category' => $request->document_category,
                    'document_description' => $request->document_description,
                    'active' => $request->active == "true" ? 1 : 0,
            ]);

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
            $this->uploadFile($file, 'FileManagment', $document);
        }
        }

        return true;
    }

    /**
     * Display the specified resource.
     */
    public function show(FileManagment $fileManagment)
    {
        //
        return FileManagment::with('files')->where('slug', $fileManagment)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileManagmentRequest $request, FileManagment $fileManagment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileManagment $fileManagment)
    {
        //
        try {
            $document = fileManagment::find($request);
            $document->delete();
            return true;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
    public function webDocuments()
    {
        $documents = FileManagment::with('files')->where('document_access','!=', 'Private')->get();
        return $documents;
    }
}
