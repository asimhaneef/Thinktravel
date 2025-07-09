<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

trait FileUpload
{
    /**
     * Handle a single file upload.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return File
     */
    public function s3SingleFileUpload($destinationPath, $requestData, $fileName, $folderName = null, $model)
    {
        $file = $requestData->file($fileName);
        info($file);
        if ($file) {
                // Generate a unique file name
                $fileName = time() . '_' . $file->getClientOriginalName();

            try {
                // Upload directly to S3
                $path = Storage::disk('s3')->putFileAs($destinationPath, $file, $fileName);
                return $storagePath = $folderName  . $fileName;
                
            } catch (\Exception $e) {
                Log::error('S3 Upload Error: ' . $e->getMessage());
                return false;
            }
        }
        return false;
    }
    public function uploadFile(UploadedFile $file, string $directory, $model): File
    {        
        // Store the file
        $path = $file->store('public/'.$directory);
        
        // Remove 'public/' from the beginning of the path to store in database
        $storagePath = str_replace('public/', 'storage/', $path);

        // Create a file record
        $fileRecord = new File();
        $fileRecord->file_name = $file->getClientOriginalName();
        $fileRecord->file_real_path = $storagePath; // Store the modified path
        $fileRecord->file_extension = $file->getClientOriginalExtension(); // Store the modified path
        $fileRecord->file_size = $file->getSize(); // Store the modified path
        $fileRecord->file_mime_type = $file->getMimeType(); // Store the modified path
        $fileRecord->fileable_id = $model->id;
        $fileRecord->user_id = auth()->user()->id;
        $fileRecord->fileable_type = get_class($model);
        $fileRecord->save();

        return $fileRecord;
    }

    /**
     * Handle multiple file uploads.
     *
     * @param array $files
     * @param string $directory
     * @return array
     */
    public function uploadFiles(array $files, string $directory): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            $uploadedFiles[] = $this->uploadFile($file, $directory);
        }

        return $uploadedFiles;
    }

    /**
     * Delete a file.
     *
     * @param File $file
     * @return bool
     */
    public function deleteFile(File $file): bool
    {
        // Delete the file from storage
        Storage::delete($file->path);

        // Delete the file record
        return $file->delete();
    }

    /**
     * Get the user's profile picture.
     *
     * @return File|null
     */
    public function getProfilePicture(): ?File
    {
        return $this->files()->latest()->first();
    }
}