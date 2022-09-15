<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileUploaderService
{
    private $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('public');
    }

    public function store($file, string $folder)
    {
        $fileName = $this->getFileName($file);
        $filePath = $this->getFilePath($folder, $fileName);

        try {
            $fileContent = $this->getFileContents($file);
           
            $this->storage->put($filePath, $fileContent);

            Log::info("Uploaded file");

            return $filePath;
        } catch(\Exception $exception) {
            Log::error('Unable to upload file');
            Log::error($exception->getMessage());
        }
    }

    public function update($file, $previousPath, $folder)
    {
        try {
            if($this->delete($previousPath)) {
                $filePath = $this->store($file, $folder);
              
                Log::info("Updated file");

                return $filePath;
            }
        } catch(\Exception $exception) {
            Log::info('Unable to update file');
            Log::info($exception->getMessage());
        }

        return null;
    }

    public function delete($filePath)
    {
        try {
            if($this->existFile($filePath)) {
                $this->storage->delete($filePath);

                Log::info("Deleted file");

                return true;
            }
        } catch(\Exception $exception) {
            Log::error('Unable to delete file');
            Log::error($exception->getMessage());
        }

        return false;
    }

    public function existFile($filePath)
    {
        return $this->storage->exists($filePath);
    }

    private function getFileName($file)
    {
        return Str::random('10').time().Str::random('10').".{$this->getExtension($file)}";
    }

    private function getFilePath($folder, $fileName)
    {
        return "{$folder}/{$fileName}";
    }

    public function getFileContents($file){
        return fopen($file, 'r+');
    }

    public function getExtension($file){
        return $file->getClientOriginalExtension();
    }
}