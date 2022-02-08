<?php
declare(strict_types=1);

namespace Console\FileSystem;

use Test\TestCase;

/**
 * Test de la class File
 */
class FileTest extends TestCase
{
    /**
     * test la creation d'une instance File
     * @return void
     */
    public function testContruct(): void
    {
        $file = new File(__DIR__ . "/../directoryForTest/FileForTest");
        self::assertInstanceOf(File::class, $file);
    }

    /**
     * test la création d'un fichier
     * @return void
     */
    public function testCreate(): void
    {
        if (is_file('file_test')) {
            unlink('file_test');
        }
        File::create('file_test');
        self::assertTrue(is_file('file_test'));
        unlink('file_test');
    }

    /**
     * test la suppression d'un fichier
     * @return void
     */
    public function testDelete(): void
    {
        if (!is_file('file_test')) {
            touch('file_test');
        }
        self::assertTrue(is_file('file_test'));
        File::delete('file_test');
        self::assertFalse(is_file('file_test'));
    }

    /**
     * Test l'existance d'un fichier
     * @return void
     */
    public function testExists(): void
    {
        if (!is_file('file_test')) {
            touch('file_test');
        }
        self::assertTrue(File::exists('file_test'));
        unlink('file_test');
        self::assertFalse(File::exists('file_test'));
    }

    /**
     * test getName
     * @return void
     */
    public function testGetName(): void
    {
        $file = new File(__DIR__ . "/../directoryForTest/FileForTest");
        self::assertEquals('FileForTest', $file->getName());
    }

    /**
     * test getFullName
     * @return void
     */
    public function testGetFullName(): void
    {
        $file = new File(__DIR__ . "/../directoryForTest/FileForTest");
        self::assertEquals(realpath(__DIR__ . "/../directoryForTest/FileForTest"), $file->getFullName());
    }

    /**
     * test la copie de fichier
     * @return void
     */
    public function testCopy(): void
    {
        $file = new File(__DIR__ . "/../directoryForTest/FileForTest");
        $newFile = $file->copy('directory/');
        self::assertTrue(is_file('directory/FileForTest'));
        self::assertEquals(realpath('directory/FileForTest'), $newFile->getFullName());
        unlink('directory/FileForTest');
    }
}
