<?php
declare(strict_types=1);

namespace Console\FileSystem;

use LogicException;
use RuntimeException;

/**
 * Directory
 */
class Directory implements Item
{
    /** @var string nom du dossier */
    private string $fullname;

    /**
     * constructeur du dossier
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
     * permet de savoir si un dossier existe
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        return is_dir($name);
    }

    /**
     * Supprime un dossier
     * @param string $name
     * @param bool $force
     * @return void
     */
    public static function delete(string $name, bool $force = false): void
    {
        if (is_file($name)) {
            throw new RuntimeException("$name n'est pas un dossier");
        }
        if (self::exists($name)) {
            $dir = new self($name);
            $isEmpty = $dir->empty();
            if (!$force && !$isEmpty) {
                throw new RuntimeException("impossible de supprimer $name, le dossier n'est pas vide");
            }
            if (!$isEmpty) {
                $files = array_reverse($dir->ls());
                foreach ($files as $file) {
                    File::delete($name . DIRECTORY_SEPARATOR . $file);
                }
                $directories = array_reverse($dir->lsDirectory());
                foreach ($directories as $directory) {
                    self::delete($name . DIRECTORY_SEPARATOR . $directory, true);
                }
            }
            $dir = null;
            rmdir($name);
        }
    }

    /**
     * indique si le dossier est vide
     * @return bool
     */
    public function empty(): bool
    {
        $this->checkIfIExist();
        $realPath = realpath($this->fullname);
        $ios = array_filter(scandir($realPath), static function ($item) {
            return !in_array($item, ['.', '..']);
        });
        return empty($ios);
    }

    /**
     * emmet une exception si le dossier n'existe plus
     * @return void
     */
    private function checkIfIExist(): void
    {
        if (!self::exists($this->fullname)) {
            throw new RuntimeException("le dossier $this->fullname n'existe plus");
        }
    }

    /**
     * liste les fichiers d'un dossier
     * @param bool $recurse
     * @return array<string>
     */
    public function ls(bool $recurse = false): array
    {
        $this->checkIfIExist();
        $realPath = realpath($this->fullname);
        $ios = scandir($realPath);
        $files = [];
        $directories = [];
        foreach ($ios as $io) {
            if (in_array($io, ['.', '..'])) {
                continue;
            }
            $ioFull = $realPath . DIRECTORY_SEPARATOR . $io;
            if (is_file($ioFull)) {
                $files[] = $io;
            } elseif (is_dir($ioFull)) {
                $directories[] = $io;
            }
        }
        if ($recurse) {
            foreach ($directories as $dirname) {
                $localDir = new self($realPath . DIRECTORY_SEPARATOR . $dirname);
                $subFiles = $localDir->ls(true);
                foreach ($subFiles as $sub) {
                    $files[] = $dirname . DIRECTORY_SEPARATOR . $sub;
                }
            }
        }
        return $files;
    }

    /**
     * liste les fichiers d'un dossier
     * @param bool $recurse
     * @return array<string>
     */
    public function lsDirectory(bool $recurse = false): array
    {
        $this->checkIfIExist();
        $realPath = realpath($this->fullname);
        $ios = scandir($realPath);
        $directories = [];
        foreach ($ios as $io) {
            if (in_array($io, ['.', '..'])) {
                continue;
            }
            $ioFull = $realPath . DIRECTORY_SEPARATOR . $io;
            if (is_dir($ioFull)) {
                $directories[] = $io;
            }
        }
        if ($recurse) {
            foreach ($directories as $dirname) {
                $localDir = new self($realPath . DIRECTORY_SEPARATOR . $dirname);
                $subDirectories = $localDir->lsDirectory(true);
                foreach ($subDirectories as $sub) {
                    $directories[] = $dirname . DIRECTORY_SEPARATOR . $sub;
                }
            }
        }
        return $directories;
    }

    /**
     * donne le nom du dossier
     * @return string
     */
    public function getName(): string
    {
        $this->checkIfIExist();
        return basename($this->fullname);
    }

    /**
     * donne le nom complet du dossier
     * @return string
     */
    public function getFullName(): string
    {
        $this->checkIfIExist();
        return $this->fullname;
    }

    /**
     * @param string $destination
     * @return Directory
     */
    public function copy(string $destination): Directory
    {
        $this->checkIfIExist();
        $files = $this->ls(true);
        if (File::exists($destination)) {
            throw new RuntimeException("Ilmpossible de copier $this->fullname dans un fichier ");
        }
        if (!self::exists($destination)) {
            self::create($destination);
        }
        foreach ($files as $filename) {
            $file = new File($this->fullname . DIRECTORY_SEPARATOR . $filename);
            $file->copy($destination . DIRECTORY_SEPARATOR . $filename);
        }
        return new self($destination);
    }

    /**
     * Créer un dossier
     * @param string $name
     * @param int $permission
     * @return void
     */
    public static function create(string $name, int $permission = 0777): void
    {
        if (!self::exists($name) && !mkdir($name, $permission, true) && !is_dir($name)) {
            throw new RuntimeException(sprintf("Le dossier '%s' n'a pu être créé", $name));
        }
    }
}
