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

namespace App\Resource\Mediator\Material;

use Enorma\CompanyBundle\Mediator\Common\CommonCompanyMediatorTrait;
use Enorma\MaterialBundle\Dto\MaterialApiDtoInterface;
use Enorma\MaterialBundle\Mediator\Material\QueryMediatorInterface;
use Enorma\UtilsBundle\QueryBuilder\QueryBuilderInterface;

class QueryDecorator implements QueryMediatorInterface
{
    use CommonCompanyMediatorTrait;

    private QueryMediatorInterface $mediator;

    public function __construct(QueryMediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

    public function alias(): string
    {
        return $this->mediator->alias();
    }

    public function createQuery(MaterialApiDtoInterface $dto, QueryBuilderInterface $builder): void
    {
        $this->mediator->createQuery($dto, $builder);

        $alias = $this->alias();

        $this->joinCompany($dto, $builder, $alias);
    }

    public function getResult(MaterialApiDtoInterface $dto, QueryBuilderInterface $builder): array
    {
        return $this->mediator->getResult($dto, $builder);
    }
}
