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

namespace App\Resource\Dto\Preserve;

use App\User\DtoCommon\ValueObject\Immutable\SkipInterface as ImmutableSkipInterface;
use App\User\DtoCommon\ValueObject\Mutable\SkipInterface as MutableSkipInterface;
use Enorma\DtoCommon\ValueObject\Immutable\CreatedByInterface as ImmutableCreatedByInterface;
use Enorma\DtoCommon\ValueObject\Mutable\CreatedByInterface as MutableCreatedByInterface;
use Enorma\CompanyBundle\DtoCommon\ValueObject\Mutable\CompanyApiDtoInterface;
use Enorma\MaterialBundle\Dto\Preserve\MaterialApiDtoInterface;

interface AppMaterialDtoInterface extends MaterialApiDtoInterface, CompanyApiDtoInterface, ImmutableSkipInterface, MutableSkipInterface, ImmutableCreatedByInterface, MutableCreatedByInterface
{
}
