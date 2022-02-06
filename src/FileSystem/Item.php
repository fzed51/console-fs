<?php

namespace Console\FileSystem;

interface Item
{
    public static function exists(string $name): bool;

    public function getName(): string;

    public function getFullName(): string;
}
