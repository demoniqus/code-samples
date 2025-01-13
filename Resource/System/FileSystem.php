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

namespace App\Resource\System;

use App\Resource\Dto\AppMaterialDtoInterface;
use App\Resource\Exception\File\FileDuplicateOnCreateException;
use App\Resource\Exception\File\FileDuplicateOnSaveException;
use Enorma\DtoBundle\Dto\DtoInterface;
use Enorma\MaterialBundle\Exception\File\FileCannotBeCreatedException;
use Enorma\MaterialBundle\Exception\File\FileCannotBeRemovedException;
use Enorma\MaterialBundle\Exception\File\FileCannotBeSavedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileSystem extends \Enorma\MaterialBundle\System\FileSystem
{
    private int $defDirPermissions = 0700;

    public function __construct(
        private LoggerInterface $logger,
        private FileNameGeneratingStrategyInterface $fileNameGeneratingStrategy,
        private FileUploadingPathResolverInterface $uploadingPathResolver,
    ) {
    }

    /**
     * @param DtoInterface|AppMaterialDtoInterface $dto
     *
     * @throws FileCannotBeCreatedException
     */
    public function create(DtoInterface $dto, ?File $file): File
    {
        try {
            return $this->save($dto, $file, new FileDuplicateOnCreateException());
        } catch (\Throwable $ex) {
            $this->logError($ex, $dto);

            $ex instanceof FileCannotBeCreatedException ?
                throw $ex : throw new FileCannotBeCreatedException();
        }
    }

    /**
     * @param DtoInterface|AppMaterialDtoInterface $dto
     *
     * @throws FileCannotBeSavedException
     */
    public function update(DtoInterface $dto, ?File $file, ?string $existingFilePath): File
    {
        try {
            $this->delete($dto, $existingFilePath);

            return $this->save($dto, $file, new FileDuplicateOnSaveException());
        } catch (\Throwable $ex) {
            $this->logError($ex, $dto);

            $ex instanceof FileCannotBeSavedException ?
                throw $ex : throw new FileCannotBeSavedException();
        }
    }

    /**
     * @param DtoInterface|AppMaterialDtoInterface $dto
     *
     * @throws FileCannotBeRemovedException
     */
    public function delete(DtoInterface $dto, ?string $file): void
    {
        try {
            if (file_exists($file)) {
                unlink($file);
            }
        } catch (\Throwable $ex) {
            $this->logError($ex, $dto);

            throw new FileCannotBeRemovedException();
        }
    }

    private function save(
        AppMaterialDtoInterface $dto,
        File $file,
        \Exception $exceptionOnDuplicate,
    ): File {
        $path = $this->uploadingPathResolver->resolve($dto);

        if (!file_exists($path)) {
            mkdir($path, $this->defDirPermissions, true);
        }

        $from = $file->getPathname();
        $destFileName = $this->fileNameGeneratingStrategy->generate($file);
        $to = $this->uploadingPathResolver->getPath([$path, $destFileName]);

        if (file_exists($to)) {
            throw $exceptionOnDuplicate;
        }

        return $this->copy($from, $to);
    }

    private function copy(string $from, string $to): File
    {
        copy($from, $to);

        return new File($to);
    }

    private function logError(\Throwable $ex, AppMaterialDtoInterface $dto): void
    {
        $context = [
            'company' => $dto->hasCompanyApiDto() ? $dto->getCompanyApiDto()->getId() : null,
        ];
        $this->logger->critical($ex->getMessage(), $context);
    }
}
