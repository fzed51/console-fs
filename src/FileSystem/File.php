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

    /**
     * créationd'un fichier
     * @param string $name
     * @param int $permission
     * @return \Console\FileSystem\File
     */
    public static function create(string $name, int $permission = 0777)
    {
        if (self::exists($name)) {
            throw new \RuntimeException("Impossible de créer '$name', il existe déjà");
        }
        if (Directory::exists($name)) {
            throw new \RuntimeException("Impossible de créer '$name', c'est un dossier");
        }
        $directory = dirname($name);
        Directory::create($directory, $permission);
        touch($name);
        chmod($name, $permission);
        if (!self::exists($name)) {
            throw new \RuntimeException("'$name', n'a pu être créé");
        }
        return new self($name);
    }

    /**
     * retourne le nom complet d'un fichier
     * @return string
     */
    public function getFullName(): string
    {
        $this->checkIfIExist();
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
        if (Directory::exists($destination) || Path::enfBySeparator($destination)) {
            $destination = rtrim($destination, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->getName();
        }
        if (!Directory::exists(dirname($destination))) {
            Directory::create(dirname($destination));
        }
        try {
            if (!copy($this->fullname, $destination)) {
                throw new RuntimeException("La copie de de $this->fullname vers '$destination' a échoué");
            }
        } catch (\Throwable $err) {
            error_log("La copie de de '$this->fullname' vers '$destination' a échoué : " . $err->getMessage());
            throw new RuntimeException("La copie de de $this->fullname vers '$destination' a échoué");
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
        $this->checkIfIExist();
        return basename($this->fullname);
    }
}
