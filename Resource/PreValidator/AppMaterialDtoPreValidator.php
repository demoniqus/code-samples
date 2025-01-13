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

namespace App\Resource\PreValidator;

use App\Resource\Dto\AppMaterialDto;
use Enorma\DtoBundle\Dto\DtoInterface;
use Enorma\MaterialBundle\Exception\Material\MaterialInvalidException;
use Enorma\MaterialBundle\PreValidator\Material\DtoPreValidator;

class AppMaterialDtoPreValidator extends DtoPreValidator
{
    public function onPost(DtoInterface $dto): void
    {
        parent::onPost($dto);

        $this->checkCompany($dto);
    }

    public function onPut(DtoInterface $dto): void
    {
        parent::onPost($dto);

        $this->checkCompany($dto);
    }

    protected function checkCompany(DtoInterface $dto): self
    {
        /** @var AppMaterialDto $dto */
        if (!$dto->hasCompanyApiDto()) {
            throw new MaterialInvalidException('The Dto hasn\'t company');
        }

        return $this;
    }
}