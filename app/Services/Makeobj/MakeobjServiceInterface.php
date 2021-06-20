<?php

namespace App\Services\Makeobj;

interface MakeobjServiceInterface
{
    /**
     * MakeObj.
     */
    public function version(): string;

    /*
     * MakeObj CAPABILITIES.
     */
    public function capabilities(): array;

    /*
     * MakeObj LIST <pak file(s)>.
     */
    public function list(string $filePath): array;

    /*
     * MakeObj PAK <pak file> <dat file(s)>.
     */
    public function pak(int $size, string $basePath, string $pakFile, string $datFile, bool $debug = false): array;

    /*
     * MakeObj DUMP <pak file> <pak file(s)>.
     */
    public function dump(string $pakFile): Node;

    /*
     * MakeObj MERGE <pak file library> <pak file(s)>.
     */
    // public function merge(string $pakFile, array $pakFiles): array;

    /*
     * MakeObj EXPAND <output> <dat file(s)>.
     */
    // public function expand(string $output, array $datFiles): array;

    /*
     * MakeObj EXTRACT <pak file archive>.
     */
    // public function extract(string $pakFileArchive): array;
}
