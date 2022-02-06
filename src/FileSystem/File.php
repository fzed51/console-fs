<?php

namespace Console\FileSystem;


use LogicException;
use RuntimeException;

/**
 * File
 */
class File implements Item
{
    /** @var string nom complet du fichier */
    private string $fullname;

    /**
     * constructeur de File
     * @param string $name
     */
    public function __construct(string $name)
    {
        if (!self::exists($name)) {
            throw new LogicException("le dossier $name n'existe pas");
        }
        $this->fullname = realpath($name);
    }

    /**
     * test si un fichier existe
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return is_file($name);
    }

    /**
     * retourne le nom complet d'un fichier
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullname;
    }

    /**
     * emmet une exception si le fichier n'existe plus
     * @return void
     */
    private function checkIfIExist(): void
    {
        if (!self::exists($this->fullname)) {
            throw new RuntimeException("le fichier $this->fullname n'existe plus");
        }
    }

    /**
     * retourne le nom du fichier
     * @return string
     */
    public function getName(): string
    {
        return basename($this->fullname);
    }
}