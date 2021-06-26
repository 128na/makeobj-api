<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\v1\PakRequest;
use App\Services\File\FileServiceInterface;
use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Http\UploadedFile;

class DemoController extends Controller
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

    public function pak(PakRequest $reqeust)
    {
        $filename = $reqeust->input('filename');
        $pakFilename = "{$filename}.pak";
        $datFilename = "{$filename}.dat";

        $dat = $reqeust->input('dat', '');
        $dir = 'pak/'.md5($dat);

        array_map(
            fn (UploadedFile $file) => $this->fileService->putAsOriginal($dir, $file, '.png'),
            $reqeust->file('images', [])
        );

        $this->makeobjService->pak(
            (int) $reqeust->input('size', 64),
            $pakFilename,
            $this->fileService->put($dir, $dat, $datFilename),
            (bool) $reqeust->input('debug', false)
        );

        return $this->fileService->download($dir, $pakFilename);
    }
}
