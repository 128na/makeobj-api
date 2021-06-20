<?php

namespace App\Services\Makeobj;

use _128Na\Simutrans\Makeobj\Makeobj;

class MakeobjService implements MakeobjServiceInterface
{
    private Makeobj $makeobj;

    public function __construct(Makeobj $makeobj)
    {
        $this->makeobj = $makeobj;
    }

    public function version(): string
    {
        $res = $this->makeobj->version();

        if ($res->getCode() !== 3) {
            throw new MakeobjFailedException($res);
        }

        return $res->getOutput()[1] ?? '';
    }

    public function capabilities(): array
    {
        $res = $this->makeobj->capabilities();
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
        $capabilities = array_slice($res->getOutput(), 6);

        return array_map('trim', $capabilities);
    }

    public function list(string $filePath): array
    {
        $res = $this->makeobj->list($filePath);
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
        $list = array_slice($res->output, 3);

        return array_map(function ($l) {
            preg_match('/^(\w+)\s+(.+)\s+(\d+)\s+(\d+)$/', $l, $matches);

            return [
                'type' => $matches[1],
                'name' => trim($matches[2]),
                'nodes' => (int) $matches[3],
                'size' => (int) $matches[4],
            ];
        }, $list);
    }

    public function pak(int $size, string $basePath, string $pakFile, string $datFile, bool $debug = false): array
    {
        if (!$datFile) {
            $datFile = './';
        }
        if (!$pakFile) {
            $pakFile = './';
        }

        $res = $this->makeobj->pak($size, $basePath, $datFile, $pakFile, $debug);
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }

        return $res->getOutput();
    }

    public function dump(string $pakFile): Node
    {
        $res = $this->makeobj->dump($pakFile);
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
        $list = array_slice($res->getOutput(), 6);
        $list = array_map(fn ($l) => new Node($l), $list);

        return $this->toArrayReclusive($list)[0];
    }

    /**
     * ノード一覧を再帰処理して入れ子にする.
     */
    private function toArrayReclusive(array $list): array
    {
        $result = [];
        for ($i = 0; $i < count($list); ++$i) {
            $current = $list[$i];
            $next = $list[$i + 1] ?? null;
            // 次のノードがあり、次のノードの方がレベルが高い場合は子として処理する
            if ($next && $current->level < $next->level) {
                $children = $this->getChildren($list, $i, $current->level);
                $current->children = $this->toArrayReclusive($children);
                // 子ノードの数だけスキップ
                $i += count($children);
            }
            $result[] = $current;
        }

        return $result;
    }

    /**
     * 指定レベル以下のノードより手前の指定レベルより大きいノード一覧を返す.
     */
    private function getChildren(array $list, int $index, int $level): array
    {
        $result = [];
        for ($i = $index + 1; $i < count($list); ++$i) {
            $current = $list[$i];
            if ($current->level <= $level) {
                return $result;
            }
            $result[] = $current;
        }

        return $result;
    }

    // public function merge(string $pakFile, array $pakFiles): array{}

    // public function expand(string $output, array $datFiles): array{}

    // public function extract(string $pakFileArchive): array{}
}
