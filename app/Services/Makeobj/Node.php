<?php

namespace App\Services\Makeobj;

class Node
{
    public string $line;
    /**
     * インデントレベル.
     */
    public string $level;

    /**
     * ノード番号.
     */
    public string $num;

    /**
     * ノード名.
     */
    public string $name;

    /**
     * ノードタイプ.
     */
    public string $type;

    /**
     * バイト数.
     */
    public string $size;

    /**
     * 値.
     */
    public ?string $value;

    /**
     * 子ノード.
     */
    public array $children = [];

    public function __construct(string $line)
    {
        $this->line = $line;
        $this->parseLevel();
        $this->parseData();
    }

    private function parseLevel(): void
    {
        preg_match('/^(\s+)/', $this->line, $matches);
        $this->level = strlen($matches[1] ?? '   ') / 3;
    }

    private function parseData(): void
    {
        preg_match('/^(\d+)\s+(.+)\s+\((\w+)\)\s+(\d+)\s+bytes(.*)$/', trim($this->line), $matches);
        $this->num = $matches[1];
        $this->name = $matches[2];
        $this->type = $matches[3];
        $this->size = (int) $matches[4];
        preg_match('/^\'(.+)\'$/', trim($matches[5] ?? ''), $matches2);
        $this->value = $matches2[1] ?? null;
    }

    public function hasChild(): bool
    {
        return (bool) count($this->children);
    }

    public function __toString()
    {
        $str = $this->line;
        if ($this->hasChild()) {
            $children = implode("\n", array_map(fn (Node $n) => $n->__toString(), $this->children));
            $str .= "\n".$children;
        }

        return $str;
    }

    public function toArray(): array
    {
        return [
            'level' => $this->level,
            'name' => $this->name,
            'type' => $this->type,
            'size' => $this->size,
            'value' => $this->value,
            'children' => array_map(fn (Node $n) => $n->toArray(), $this->children),
        ];
    }
}
