<?php
declare(strict_types=1);

namespace Console\FileSystem;

/**
 * Path
 * gestion des chemin
 */
class Path
{

    /**
     * Test si le chemin fini par un séparateur de dossier
     * @param string $path
     * @return bool
     */
    public static function endBySeparator(string $path): bool
    {
        if ($path === '') {
            return false;
        }
        $tmp = self::normalize($path);
        return $tmp[-1] === '/';
    }

    /**
     * normalise le chemin
     * @param string $path
     * @return string
     */
    public static function normalize(string $path): string
    {
        $re = '/[\/\\\\]+/';
        $tmp = preg_replace($re, '/', $path);
        return str_replace('/./', '/', $tmp);
    }

    /**
     * join des éléments de chemin
     * @param string ...$paths
     * @return string
     */
    public static function join(string ...$paths): string
    {
        $nextPath = [];
        foreach ($paths as $path) {
            $nextPath[] = self::normalize($path);
        }
        return implode('/', $nextPath);
    }
}
