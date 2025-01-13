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

namespace App\Resource\Dto;

use App\Company\Dto\AppCompanyDto;
use App\Company\Dto\AppCompanyDtoInterface;
use Enorma\DtoBundle\Annotation\Dto;
use Enorma\DtoBundle\Dto\DtoInterface;
use Enorma\CompanyBundle\Dto\CompanyApiDtoInterface;
use Enorma\CompanyBundle\DtoCommon\ValueObject\Mutable\CompanyApiDtoTrait;
use Enorma\MaterialBundle\Dto\MaterialApiDto;

class AppMaterialDto extends MaterialApiDto implements AppMaterialDtoInterface
{
    use CompanyApiDtoTrait;
    /**
     * @Dto(class="App\Company\Dto\AppCompanyDto", generator="genRequestCompanyApiDto")
     *
     * @var AppCompanyDtoInterface|null
     */
    protected ?CompanyApiDtoInterface $companyApiDto = null;

    public static function initDto(): DtoInterface
    {
        static::$classCompanyApiDto = AppCompanyDto::class;

        return parent::initDto();
    }
}
