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

class FileUploadingPathResolver implements FileUploadingPathResolverInterface
{
    private ?string $rootMaterialUploadingDir = null;

    public function setRootMaterialUploadingDir(
        string $rootMaterialUploadingDir,
    ): void {
        $this->rootMaterialUploadingDir = $this->trim($rootMaterialUploadingDir);
    }

    public function resolve(AppMaterialDtoInterface $dto): string
    {
        $path = [
            $this->rootMaterialUploadingDir,
            $dto->getCompanyApiDto()->getId() ?? 'company', // Если так не сделать, то уровень company пропадет из адреса
            $dto->getId() ?? spl_object_hash($dto),
        ];

        return $this->getPath($path);
    }

    public function getPath(array $pathItems): string
    {
        return implode(\DIRECTORY_SEPARATOR, $pathItems);
    }

    public function getRootUploadingDir(): string
    {
        return $this->rootMaterialUploadingDir;
    }

    private function trim(string $str): string
    {
        $str = trim($str);
        $str = rtrim($str, '/');

        return rtrim($str, '\\');
    }
}
