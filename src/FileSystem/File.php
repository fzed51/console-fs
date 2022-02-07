<?php
declare(strict_types=1);

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
     * copie un fichier
     * @param string $destination
     * @return File
     */
    public function copy(string $destination): File
    {
        $this->checkIfIExist();
        if (Directory::exists($destination) || $destination[-1] === DIRECTORY_SEPARATOR) {
            $destination = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->getName();
        }
        if (!Directory::exists(dirname($destination))) {
            Directory::create(dirname($destination));
        }
        try {
            if (!copy($this->fullname, $destination)) {
                throw new RuntimeException("La copie de de $this->fullname a échouée");
            }
        } catch (\Throwable $err) {
            error_log("La copie de de $this->fullname a échouée : " . $err->getMessage());
            throw new RuntimeException("La copie de de $this->fullname a échouée");
        }
        return new self($destination);
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

    /**
     * suppression d'un fichier
     * @param string $name
     * @return void
     */
    public static function delete(string $name): void
    {
        if (Directory::exists($name)) {
            throw new \RuntimeException("Impossible de supprimer '$name', c'est un dossier");
        }
        if (!self::exists($name)) {
            return;
        }
        unlink($name);
    }
}
