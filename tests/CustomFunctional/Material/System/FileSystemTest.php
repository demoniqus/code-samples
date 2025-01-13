<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * Developer list:
 * (c) Dmitry Antipov <demoniqus@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\CustomFunctional\Material\System;

use App\Fcr\Dto\Preserve\AppFcrDto;
use App\Material\Dto\Preserve\AppMaterialDto;
use App\Material\System\FileSystem;
use App\Material\System\FileUploadingPathResolverInterface;
use Enorma\TestUtilsBundle\Helper\AbstractSymfony;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSystemTest extends WebTestCase
{
    private const TEST_TMP_FILE_NAME = 'abc123DEF';
    private const TEST_ORIG_FILE_NAME = 'origFileName.ext';
    private const FILE_SYSTEM_ROOT_DIR = 'project';
    private const ROOT_MATERIALS_UPLOADING_DIR = 'root_dir/dir/var/materials';
    private const TEST_FILE_CONTENT = 'Test file content';

    private ?vfsStreamDirectory $root = null;
    private ?vfsStreamDirectory $rootMaterialsUploadingDir = null;
    private ?FileUploadingPathResolverInterface $uploadingPathResolver = null;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup(self::FILE_SYSTEM_ROOT_DIR);

        $structElements = explode('/', self::ROOT_MATERIALS_UPLOADING_DIR);
        $struct = [];
        $i = \count($structElements);
        while ($i) {
            $struct = [
                $structElements[--$i] => $struct,
            ];
        }

        vfsStream::create($struct, $this->root);
        vfsStream::create(['tmp' => [self::TEST_TMP_FILE_NAME => self::TEST_FILE_CONTENT]],
            $this->root
        );

        $this->rootMaterialsUploadingDir = $this->root->getChild(implode(\DIRECTORY_SEPARATOR, $structElements));

        $container = AbstractSymfony::checkVersion() ? $this->getContainer() : static::$container;

        $this->uploadingPathResolver = $container->get(FileUploadingPathResolverInterface::class);

        $rootMaterialUploadingDir = $this->rootMaterialsUploadingDir->url();
        \Closure::bind(
            function () use ($rootMaterialUploadingDir) {
                $this->rootMaterialUploadingDir = $rootMaterialUploadingDir;
            },
            $this->uploadingPathResolver,
            \get_class($this->uploadingPathResolver)
        )->__invoke();

        parent::setUp();
    }

    public function testSuccessFileCreating()
    {
        $container = $this->getContainer();

        /** @var vfsStreamDirectory $tmpDir */
        $tmpDir = $this->root->getChild('tmp');
        $this->assertTrue($tmpDir->hasChild(self::TEST_TMP_FILE_NAME));

        /** @var FileSystem $fs */
        $fs = $container->get(FileSystem::class);

        /** @var MockObject|UploadedFile $file */
        $file = $this->createUploadedFileMock();

        $this->assertCount(0, $this->rootMaterialsUploadingDir->getChildren());

        $materialDto = $this->getMaterialDto();

        $fs->create($materialDto, $file);

        $this->directoriesStructure($materialDto);
    }

    public function testSuccessFileUpdating()
    {
        $container = $this->getContainer();

        /** @var vfsStreamDirectory $tmpDir */
        $tmpDir = $this->root->getChild('tmp');
        $this->assertTrue($tmpDir->hasChild(self::TEST_TMP_FILE_NAME));

        /** @var FileSystem $fs */
        $fs = $container->get(FileSystem::class);

        /** @var MockObject|UploadedFile $file */
        $file = $this->createUploadedFileMock();

        vfsStream::create(['deletedFile.txt' => 'deleted file content'],
            $this->rootMaterialsUploadingDir
        );

        $this->assertTrue($this->rootMaterialsUploadingDir->hasChild('deletedFile.txt'));

        $materialDto = $this->getMaterialDto();

        $fs->update($materialDto, $file, $this->rootMaterialsUploadingDir->getChild('deletedFile.txt')->url());

        $this->directoriesStructure($materialDto);

        $this->assertFalse($this->rootMaterialsUploadingDir->hasChild('deletedFile.txt'));
    }

    public function testSuccessFileDeleting()
    {
        $container = $this->getContainer();

        /** @var vfsStreamDirectory $tmpDir */
        $tmpDir = $this->root->getChild('tmp');
        $this->assertTrue($tmpDir->hasChild(self::TEST_TMP_FILE_NAME));

        /** @var FileSystem $fs */
        $fs = $container->get(FileSystem::class);

        $fs->delete($this->getMaterialDto(), $tmpDir->getChild(self::TEST_TMP_FILE_NAME)->url());

        $this->assertFalse($tmpDir->hasChild(self::TEST_TMP_FILE_NAME));
    }

    protected function tearDown(): void
    {
        restore_exception_handler();
    }

    private function directoriesStructure(AppMaterialDto $materialDto): void
    {
        $path = $this->uploadingPathResolver->resolve($materialDto);
        $path = str_replace($this->rootMaterialsUploadingDir->url(), '', $path);
        $path = array_filter(explode(\DIRECTORY_SEPARATOR, $path));
        $struct = vfsStream::inspect(new vfsStreamStructureVisitor(), $this->rootMaterialsUploadingDir)->getStructure();
        $i = 0;
        /** @var vfsStreamDirectory $baseDir */
        $baseDir = $this->rootMaterialsUploadingDir;
        while (\count($path)) {
            $dirName = array_shift($path);
            $this->assertCount(1, $baseDir->getChildren());
            $this->assertTrue($baseDir->hasChild($dirName));
            $baseDir = $baseDir->getChild($dirName);
        }
        $this->assertCount(1, $baseDir->getChildren());
        $this->assertTrue($baseDir->hasChild(self::TEST_ORIG_FILE_NAME));
    }

    private function createUploadedFileMock(): MockObject
    {
        $mockFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getPathname', 'getClientOriginalName'])
            ->getMock();

        $mockFile
            ->method('getPathname')
            ->willReturn($this->root->getChild(implode(\DIRECTORY_SEPARATOR, ['tmp', self::TEST_TMP_FILE_NAME]))->url());

        $mockFile
            ->method('getClientOriginalName')
            ->willReturn(self::TEST_ORIG_FILE_NAME);

        return $mockFile;
    }

    private function getMaterialDto(): AppMaterialDto
    {
        $fcr = new AppFcrDto();
        \Closure::bind(
            function () {
                $this->setId(1);
            },
            $fcr,
            AppFcrDto::class
        )->__invoke();

        $dto = new AppMaterialDto();
        $dto->setFcrApiDto($fcr);

        return $dto;
    }
}
