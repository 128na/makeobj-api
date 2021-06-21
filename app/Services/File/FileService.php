<?php

namespace App\Services\File;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;

class FileService implements FileServiceInterface
{
    private FilesystemAdapter $disk;

    public function __construct(FilesystemAdapter $disk)
    {
        $this->disk = $disk;
    }

    public function url(string $dir, string $filename): string
    {
        $path = "$dir/$filename";

        return $this->disk->url($path);
    }

    public function put(string $dir, string $content, string $filename): string
    {
        $path = "$dir/$filename";
        if (!$this->disk->exists($path)) {
            $this->disk->put($path, $content);
        }

        return $this->disk->path($path);
    }

    public function putAsHash(string $dir, UploadedFile $file, string $ext): string
    {
        $filename = md5_file($file->getRealPath()).$ext;
        $path = "$dir/$filename";

        if ($this->disk->exists($path)) {
            return $this->disk->path($path);
        }

        return $this->disk->path($this->disk->putFileAs($dir, $file, $filename));
    }

    public function putAsOriginal(string $dir, UploadedFile $file): string
    {
        $filename = $file->getClientOriginalName();
        $path = "$dir/$filename";

        if ($this->disk->exists($path)) {
            $this->disk->delete($path);
        }

        return $this->disk->path($this->disk->putFileAs($dir, $file, $filename));
    }
}
