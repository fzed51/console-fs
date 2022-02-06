<?php
declare(strict_types=1);

namespace Console\FileSystem;

use Test\TestCase;

/**
 * Tests de la Console\FileSystem\Directory
 */
class DirectoryTest extends TestCase
{
    /**
     * Test le constructeur
     * @return void
     */
    public function testConstructDirectory(): void
    {
        $this->expectNotToPerformAssertions();
        new Directory(__DIR__ . '/../directoryForTest');
    }

    /**
     * test la methode static exists
     * @return void
     */
    public function testDirectoryExists(): void
    {
        self::assertTrue(Directory::exists(__DIR__ . '/../directoryForTest'));
    }

    /**
     * test le listing des fichiers d'un dossier
     * @return void
     */
    public function testLs(): void
    {
        $dir = new Directory(__DIR__ . '/../directoryForTest');
        $listFile = $dir->ls();
        self::assertIsArray($listFile);
        self::assertCount(1, $listFile);
        self::assertEquals('FileForTest', $listFile[0]);
    }

    /**
     * test le listing avec recursion
     * @return void
     */
    public function testLsWithRecurce(): void
    {
        $dir = new Directory(__DIR__ . '/../directoryForTest');
        $listFile = $dir->ls(true);
        self::assertCount(2, $listFile);
        self::assertContains('FileForTest', $listFile);
        self::assertContains('subDirectory\fileInSubDirectory', $listFile);
    }

    /**
     * test la creation de dossier
     * @return void
     */
    public function testCreate(): void
    {
        Directory::create('testCreateDirectory');
        self::assertTrue(is_dir('testCreateDirectory'));
        rmdir('testCreateDirectory');
    }

    /**
     * test la suppression d'un dossier
     * @return void
     */
    public function testDelete(): void
    {
        mkdir('testDeleteDirectory');
        self::assertTrue(Directory::exists('testDeleteDirectory'));
        Directory::delete('testDeleteDirectory');
        self::assertFalse(Directory::exists('testDeleteDirectory'));
    }

    /**
     * Test si un dossier est vide ou pas
     * @return void
     */
    public function testEmptyDirectoryAndNotEmpty(): void
    {
        $dir = new Directory(__DIR__ . '/../directoryForTest');
        self::assertFalse($dir->empty());
        mkdir('testEmptyDirectory');
        $dir = new Directory('testEmptyDirectory');
        self::assertTrue($dir->empty());
        rmdir('testEmptyDirectory');
        $dir = null;
    }

    /**
     * test la recuperation du nom et du nom complet
     * @return void
     */
    public function testGetNameAndFullName(): void
    {
        $dir = new Directory(__DIR__ . '/../directoryForTest');
        self::assertEquals('directoryForTest', $dir->getName());
        self::assertEquals(realpath(__DIR__ . '/../directoryForTest'), $dir->getFullName());
    }
}
