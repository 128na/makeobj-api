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

        return $res->getStdErr();
    }

    public function capabilities(): array
    {
        $res = $this->makeobj->capabilities();
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
        $stdout = $res->getStdOutAsArray();
        // 先頭と末尾を除外する
        $capabilities = array_slice($stdout, 1, count($stdout) - 2);

        return array_map('trim', $capabilities);
    }

    public function list(string $pakFilePath): array
    {
        $res = $this->makeobj->list(dirname($pakFilePath), basename($pakFilePath));
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
        $stdout = $res->getStdOutAsArray();
        $list = array_slice($stdout, 3, count($stdout) - 4);

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

    public function pak(int $size, string $pakFilename, string $datFilePath, bool $debug = true): void
    {
        $res = $this->makeobj->pak(dirname($datFilePath), $size, $pakFilename, basename($datFilePath), $debug);
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
    }

    public function dump(string $pakFilePath): Node
    {
        $res = $this->makeobj->dump(dirname($pakFilePath), basename($pakFilePath));
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
        $stdout = $res->getStdOutAsArray();
        $list = array_slice($stdout, 1, count($stdout) - 2);
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

    public function merge(string $mergedPakFilename, array $pakFilePathes): void
    {
        $res = $this->makeobj->merge(
            dirname($pakFilePathes[0]),
            $mergedPakFilename,
            implode(' ', array_map(fn ($p) => basename($p), $pakFilePathes))
        );
        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }
    }

    public function extract(string $pakFilePath): array
    {
        $dir = dirname($pakFilePath);
        $res = $this->makeobj->extract(
            $dir,
            basename($pakFilePath)
        );

        if ($res->getCode() !== 0) {
            throw new MakeobjFailedException($res);
        }

        return $this->filterExtractedFilename($res->getStdOutAsArray());
    }

    private function filterExtractedFilename(array $stdout): array
    {
        $tmp = array_map(function ($line) {
            preg_match('/^\s*writing \'(.+)\' \.\.\.\s*/', $line, $matches);

            return $matches[1] ?? '';
        }, $stdout);
        $tmp = array_filter($tmp);
        $tmp = array_values($tmp);

        return $tmp;
    }
}
