<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;

interface FileServiceInterface
{
    public function url(string $dir, string $filename): string;

    /*
     * ファイル名を指定名で保存する。ファイルが存在する場合は上書きする.
     */
    public function put(string $dir, string $content, string $filename): string;

    /*
     * ファイル名をファイルハッシュとして保存する。ファイルが存在する場合は上書きしない.
     */
    public function putAsHash(string $dir, UploadedFile $file, string $ext): string;

    /*
     * ファイル名をオリジナルファイル名で保存する。ファイルが存在する場合は上書きする.
     */
    public function putAsOriginal(string $dir, UploadedFile $file): string;

    /**
     * 指定日よりも過去のファイルを削除する.
     */
    public function deleteOldFiles(string $baseDir = '', int $days = 7): array;
}
