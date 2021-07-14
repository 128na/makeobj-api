<?php

namespace App\Services\File;

use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileService implements FileServiceInterface
{
    private FilesystemAdapter $disk;

    public function __construct(FilesystemAdapter $disk)
    {
        $this->disk = $disk;
    }

    public function download(string $dir, string $filename): StreamedResponse
    {
        $path = "$dir/$filename";

        return $this->disk->download($path, basename($filename));
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

    public function putAsOriginalFromUrl(string $dir, string $filename, string $url): string
    {
        $res = Http::get($url);
        if (!$res->ok() || $res->header('Content-Type') !== 'image/png') {
            throw new FileExeption(sprintf('invalid response from %s. status: %s, content-type: "%s"', $url, $res->status(), $res->header('Content-Type')));
        }

        $path = "$dir/$filename";
        if ($this->disk->exists($path)) {
            $this->disk->delete($path);
        }
        $this->disk->put($path, $res->body());

        return $this->disk->path($path);
    }

    public function deleteOldFiles(string $baseDir = '', int $days = 7): array
    {
        $now = now();
        $deleted = [];
        foreach ($this->disk->directories($baseDir) as $dir) {
            $time = $this->disk->lastModified($dir);

            if ($now->diffInDays(Carbon::parse($time)) > $days) {
                $this->disk->deleteDirectory($dir);
                $deleted[] = $dir;
            }
        }

        return $deleted;
    }
}
