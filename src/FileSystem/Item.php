<?php

namespace Console\FileSystem;

interface Item
{
    public static function exists(string $name): bool;

    /**
     * suppression d'un item
     * @param string $name
     * @return void
     */
    public static function delete(string $name): void;

    public function getName(): string;

    public function getFullName(): string;

    /**
     * copie d'un item
     * @param string $destination
     * @return static
     */
    public function copy(string $destination);
}
