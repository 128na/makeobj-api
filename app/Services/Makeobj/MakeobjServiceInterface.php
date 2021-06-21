<?php

namespace App\Services\Makeobj;

interface MakeobjServiceInterface
{
    public function version(): string;

    public function capabilities(): array;

    public function list(string $pakFilePath): array;

    public function pak(int $size, string $pakFilename, string $datFilePath, bool $debug = true): void;

    public function dump(string $pakFilePath): Node;

    public function merge(string $mergedPakFilename, array $pakFilePathes): void;

    public function extract(string $pakFilePath): array;
}
