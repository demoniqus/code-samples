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

namespace App\Resource\Handler;

use App\Resource\Dto\AppMaterialDtoInterface;
use App\Resource\System\FileUploadingPathResolverInterface;
use Enorma\DtoBundle\Dto\DtoInterface;
use Enorma\MaterialBundle\Dto\MaterialApiDtoInterface;
use Enorma\MaterialBundle\Exception\File\FileCannotBeCreatedException;
use Enorma\UtilsBundle\Handler\PostHandlerInterface;
use Psr\Log\LoggerInterface;

class MaterialPostHandler implements PostHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private FileUploadingPathResolverInterface $uploadingPathResolver,
    ) {
    }

    /**
     * @param DtoInterface|MaterialApiDtoInterface|AppMaterialDtoInterface $dto
     *
     * @throws FileCannotBeCreatedException
     */
    public function onPost(DtoInterface $dto, array &$entities, string &$group): void
    {
        if (!$dto->getId()) {
            $path = $this->uploadingPathResolver->resolve($dto);
            $tmp = explode(\DIRECTORY_SEPARATOR, $path);
            $tmp[\count($tmp) - 1] = $entities[0]->getId();
            $dest = $this->uploadingPathResolver->getPath($tmp);
            try {
                if (false === rename($path, $dest)) {
                    throw new \Exception('Cannot rename temp material folder');
                }
            } catch (\Throwable $ex) {
                $this->logger->critical($ex->getMessage(), ['sourcePath' => $path, 'destPath' => $path]);

                throw new FileCannotBeCreatedException();
            }
        }
    }

    /**
     * @param DtoInterface|MaterialApiDtoInterface $dto
     */
    public function onPut(DtoInterface $dto, array &$entities, string &$group): void
    {
    }

    public function onGet(DtoInterface $dto, array &$entities, string &$group): void
    {
    }

    public function onCriteria(DtoInterface $dto, array &$entities, string &$group): void
    {
    }

    public function onDelete(DtoInterface $dto, array &$entities, string &$group): void
    {
    }
}
