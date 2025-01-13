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

use App\Resource\Dto\AppMaterialDto as BaseMaterialApiDto;
use App\User\DtoCommon\ValueObject\Immutable\SkipInterface as ImmutableSkipInterface;
use App\User\DtoCommon\ValueObject\Mutable\SkipTrait;
use Enorma\DtoBundle\Dto\DtoInterface;
use Enorma\DtoCommon\ValueObject\Immutable\CreatedByInterface as ImmutableCreatedByInterface;
use Enorma\DtoCommon\ValueObject\Mutable\CreatedByTrait;
use Enorma\CompanyBundle\DtoCommon\ValueObject\Preserve\CompanyApiDtoTrait;
use Enorma\MaterialBundle\Dto\Preserve\MaterialApiDtoTrait;
use Symfony\Component\HttpFoundation\Request;

final class AppMaterialDto extends BaseMaterialApiDto implements AppMaterialDtoInterface
{
    use CreatedByTrait {
        CreatedByTrait::setCreatedBy as traitSetCreatedBy;
    }

    use CompanyApiDtoTrait;
    use MaterialApiDtoTrait;
    use SkipTrait;

    public function setCreatedBy($createdBy): DtoInterface
    {
        return $this->traitSetCreatedBy($createdBy);
    }

    public function toDto(Request $request): DtoInterface
    {
        $class = $request->get(DtoInterface::DTO_CLASS);

        if ($class === $this->getClass()) {
            $skip = $request->get(ImmutableSkipInterface::SKIP);
            $createdBy = $request->get(ImmutableCreatedByInterface::CREATED_BY);

            if ($skip) {
                $this->setSkip();
            }

            if ($createdBy) {
                $this->setCreatedBy($createdBy);
            }
        }

        return parent::toDto($request);
    }
}
