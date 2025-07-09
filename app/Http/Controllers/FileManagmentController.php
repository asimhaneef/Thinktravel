<?php

namespace App\Http\Controllers;

use App\Models\FileManagment;
use App\Traits\FileUpload;
use App\Http\Requests\StoreFileManagmentRequest;
use App\Http\Requests\UpdateFileManagmentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                // $this->uploadFile($file, 'FileManagment', $query);

                $destinationPath =  'uploads';
                $folderName      = 'uploads/';
                $storagePath        = $this->s3SingleFileUpload($destinationPath, $request, 'file', $folderName, $query);


                // $fileRecord = new File();
                // $fileRecord->file_name = $file->getClientOriginalName();
                // $fileRecord->file_real_path = $storagePath; // Store the modified path
                // $fileRecord->file_extension = $file->getClientOriginalExtension(); // Store the modified path
                // $fileRecord->file_size = $file->getSize(); // Store the modified path
                // $fileRecord->file_mime_type = $file->getMimeType(); // Store the modified path
                // $fileRecord->fileable_id = $query->id;
                // $fileRecord->user_id = auth()->user()->id;
                // $fileRecord->fileable_type = get_class($query);
                // $fileRecord->save();

                 return $query->update([
                    'userid' => auth()->id(),
                    'document_access' => $request->document_access,
                    'document_name' => $storagePath,
                    'document_category' => $request->document_category,
                    'document_description' => $request->document_description,
                ]);
            }
            
            else {
                return $query->update([
                    'userid' => auth()->id(),
                    'document_access' => $request->document_access,
                    'document_category' => $request->document_category,
                    'document_description' => $request->document_description,
                ]);
            }
        } else {

            // Create the post
            
            if ($request->hasFile('file')) {
                //     $file = $request->file('file');
                // $this->uploadFile($file, 'FileManagment', $document);
                    $destinationPath =  'uploads';
                    $folderName      = 'uploads/';
                    $fileInfo        = $this->s3SingleFileUpload($destinationPath, $request, 'file', $folderName, $document);

                    return $document = FileManagment::create([
                        'userid' => auth()->id(),
                        'document_access' => $request->document_access,
                        'document_name' => $fileInfo,
                        'document_category' => $request->document_category,
                        'document_description' => $request->document_description,
                    ]);
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
        // try {
            $fileManagment->delete();
            return true;
        // } catch (\Exception $e) {
        //     Log::error($e);
        //     return false;
        // }
    }
    public function webDocuments()
    {
        $documents = FileManagment::with('files')->where('document_access','!=', 'Private')->get();
        return $documents;
    }
}
