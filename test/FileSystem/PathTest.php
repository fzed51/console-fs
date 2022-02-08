<?php
declare(strict_types=1);

namespace Console\FileSystem;

use PHPUnit\Framework\TestCase;

/**
 * Test de Path
 */
class PathTest extends TestCase
{
    /**
     * test l'assemblage de chemin
     * @return void
     */
    public function testJoin()
    {
        $p = Path::join('a', 'b');
        self::assertEquals('a/b', $p);
    }

    /**
     * test la normalisation de chemin
     * @return void
     */
    public function testNormalize()
    {
        self::assertEquals('a/b', Path::normalize('a\\b'), "normalise back slash");
        self::assertEquals('a/b', Path::normalize('a\\\\b'), 'normalise double back slash');
        self::assertEquals('a/b', Path::normalize('a/b'), 'normalise slash');
        self::assertEquals('a/b', Path::normalize('a//b'), 'normalise double slash');
        self::assertEquals('a/b', Path::normalize('a//\\b'), 'normalise slash et back slash');
        self::assertEquals('a/b/c', Path::normalize('a//b\\c'), 'normalise mixed slash et back slash');
    }

    /**
     * test du test si un chemin fini par un séparateur
     * @return void
     */
    public function testEnfBySeparator()
    {
        self::assertTrue(Path::endBySeparator('a/'));
        self::assertTrue(Path::endBySeparator('a\\'));
        self::assertFalse(Path::endBySeparator('a'));
    }
}
