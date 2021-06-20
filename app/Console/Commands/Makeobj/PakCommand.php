<?php

namespace App\Console\Commands\Makeobj;

use App\Services\Makeobj\MakeobjServiceInterface;
use Illuminate\Console\Command;

class PakCommand extends Command
{
    protected $signature = 'makeobj:pak
        {size? : pakサイズ}
        {dirPath? : 実行ディレクトリ}
        {pakFile? : pakファイル}
        {datFile? : datファイル}
        {--d|debug : デバッグ表示}';

    protected $description = 'pak化実行';

    private MakeobjServiceInterface $makeobjServiceInterface;

    public function __construct(MakeobjServiceInterface $makeobjServiceInterface)
    {
        parent::__construct();
        $this->makeobjServiceInterface = $makeobjServiceInterface;
    }

    public function handle()
    {
        $size = intval($this->argument('size') ?? 64);
        $dirPath = $this->argument('dirPath') ?? '';
        $pakFile = $this->argument('pakFile') ?? '';
        $datFile = $this->argument('datFile') ?? '';
        $res = $this->makeobjServiceInterface->pak($size, $dirPath, $pakFile, $datFile);
        $this->info('successed.');
        if ($this->option('debug')) {
            $this->info(implode("\n", $res));
        }

        return 0;
    }
}
