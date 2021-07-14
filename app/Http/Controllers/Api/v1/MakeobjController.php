<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\DumpRequest;
use App\Http\Requests\Api\v1\ExtractRequest;
use App\Http\Requests\Api\v1\ListRequest;
use App\Http\Requests\Api\v1\MergeRequest;
use App\Http\Requests\Api\v1\PakRequest;
use App\Services\File\FileExeption;
use App\Services\File\FileServiceInterface;
use App\Services\Makeobj\MakeobjFailedException;
use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MakeobjController extends Controller
{
    private MakeobjServiceInterface $makeobjService;
    private FileServiceInterface $fileService;

    public function __construct(
        MakeobjServiceInterface $makeobjService,
        FileServiceInterface $fileService
    ) {
        $this->makeobjService = $makeobjService;
        $this->fileService = $fileService;
    }

    private function handleMakeobjError(MakeobjFailedException $e)
    {
        return response([
            'code' => $e->getResponse()->getCode(),
            'error' => $e->getResponse()->getStdErr(),
            'output' => $e->getResponse()->getStdOut(),
        ], 400);
    }

    private function handleFileError(FileExeption $e)
    {
        return response([
            'error' => $e->getMessage(),
        ], 400);
    }

    public function version()
    {
        try {
            return response(['version' => $this->makeobjService->version()]);
        } catch (MakeobjFailedException $e) {
            return $this->handleMakeobjError($e);
        }
    }

    public function capabilities()
    {
        try {
            return response(['capabilities' => $this->makeobjService->capabilities()]);
        } catch (MakeobjFailedException $e) {
            return $this->handleMakeobjError($e);
        }
    }

    public function list(ListRequest $reqeust)
    {
        $dir = 'list/'.Str::uuid();
        $path = $this->fileService->putAsHash($dir, $reqeust->file('file'), '.pak');

        try {
            return response(['list' => $this->makeobjService->list($path)]);
        } catch (MakeobjFailedException $e) {
            return $this->handleMakeobjError($e);
        }
    }

    public function dump(DumpRequest $reqeust)
    {
        $dir = 'dump/'.Str::uuid();
        $path = $this->fileService->putAsHash($dir, $reqeust->file('file'), '.pak');

        try {
            return response(['node' => $this->makeobjService->dump($path)->toArray()]);
        } catch (MakeobjFailedException $e) {
            return $this->handleMakeobjError($e);
        }
    }

    public function pak(PakRequest $reqeust)
    {
        $filename = $reqeust->input('filename', now()->format('Y-m-d_His'));
        $pakFilename = "{$filename}.pak";
        $datFilename = "{$filename}.dat";

        $dat = $reqeust->input('dat', '');
        $dir = 'pak/'.Str::uuid();

        try {
            array_map(
                fn (UploadedFile $file) => $this->fileService->putAsOriginal($dir, $file),
                $reqeust->input('images', [])
            );
            array_map(
                fn (array $data) => $this->fileService->putAsOriginalFromUrl($dir, $data['filename'], $data['url']),
                $reqeust->input('imageUrls', [])
            );
        } catch (FileExeption $e) {
            return $this->handleFileError($e);
        }

        try {
            $this->makeobjService->pak(
                (int) $reqeust->input('size', 64),
                $pakFilename,
                $this->fileService->put($dir, $dat, $datFilename),
                (bool) $reqeust->input('debug', false)
            );

            return ['pakfile' => $this->fileService->url($dir, $pakFilename)];
        } catch (MakeobjFailedException $e) {
            return $this->handleMakeobjError($e);
        }
    }

    public function merge(MergeRequest $reqeust)
    {
        $filename = $reqeust->input('filename', now()->format('Y-m-d_His'));
        $pakFilename = "{$filename}.pak";
        $dir = 'merge/'.Str::uuid();
        $pakFilePathes = array_map(
            fn (UploadedFile $file) => $this->fileService->putAsHash($dir, $file, '.pak'),
            $reqeust->file('files', [])
        );

        try {
            $this->makeobjService->merge($pakFilename, $pakFilePathes);

            return ['pakfile' => $this->fileService->url($dir, $pakFilename)];
        } catch (MakeobjFailedException $e) {
            return $this->handleMakeobjError($e);
        }
    }

    public function extract(ExtractRequest $reqeust)
    {
        $dir = 'extract/'.Str::uuid();
        $path = $this->fileService->putAsHash($dir, $reqeust->file('file'), '.pak');

        try {
            $pakFilenames = $this->makeobjService->extract($path);

            return response([
                'pakfiles' => array_map(fn ($f) => $this->fileService->url($dir, $f), $pakFilenames),
            ]);
        } catch (MakeobjFailedException $e) {
            return $this->handleMakeobjError($e);
        }
    }
}
