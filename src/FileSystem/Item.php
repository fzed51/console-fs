<?php
declare(strict_types=1);

namespace Console\FileSystem;

/**
 * Interface pour les éléments FileSystem
 */
interface Item
{
    /**
     * Test l'existance d'un élément
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool;

    /**
     * suppression d'un item
     * @param string $name
     * @return void
     */
    public static function delete(string $name): void;

    /**
     * Donne le nom d'un élément
     * @return string
     */
    public function getName(): string;

    /**
     * Donne le nom complet d'un élément
     * @return string
     */
    public function getFullName(): string;

    /**
     * copie d'un élément
     * @param string $destination
     * @return static
     */
    public function copy(string $destination);
}
