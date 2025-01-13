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

interface FileUploadingPathResolverInterface
{
    public function resolve(AppMaterialDtoInterface $dto): string;

    public function getPath(array $pathItems): string;

    public function getRootUploadingDir(): string;

    public function setRootMaterialUploadingDir(string $rootMaterialUploadingDir): void;
}
