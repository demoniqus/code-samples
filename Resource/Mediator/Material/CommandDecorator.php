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

use App\Resource\Dto\Preserve\AppMaterialDto;
use App\Resource\Entity\Material;
use App\Resource\Voter\RoleInterface;
use Enorma\DtoBundle\Factory\FactoryDtoInterface;
use Enorma\CompanyBundle\Manager\QueryManagerInterface as CompanyQueryManagerInterface;
use Enorma\MaterialBundle\Dto\MaterialApiDtoInterface;
use Enorma\MaterialBundle\Exception\Material\MaterialCannotBeCreatedException;
use Enorma\MaterialBundle\Exception\Material\MaterialCannotBeSavedException;
use Enorma\MaterialBundle\Mediator\Material\CommandMediatorInterface;
use Enorma\MaterialBundle\Model\Material\MaterialInterface;
use Enorma\SecurityBundle\AccessControl\AccessControlInterface;
use Enorma\UserBundle\Dto\UserApiDto;
use Enorma\UserBundle\Dto\UserApiDtoInterface;
use Enorma\UserBundle\Manager\QueryManagerInterface as UserQueryManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CommandDecorator implements CommandMediatorInterface
{
    private CommandMediatorInterface $mediator;
    private AccessControlInterface $accessControl;
    private UserQueryManagerInterface $userQueryManager;
    private FactoryDtoInterface $factoryDto;
    private CompanyQueryManagerInterface $companyQueryManager;

    public function __construct(
        FactoryDtoInterface $factoryDto,
        CommandMediatorInterface $mediator,
        AccessControlInterface $accessControl,
        UserQueryManagerInterface $userQueryManager,
        CompanyQueryManagerInterface $companyQueryManager,
    ) {
        $this->factoryDto = $factoryDto;
        $this->mediator = $mediator;
        $this->accessControl = $accessControl;
        $this->userQueryManager = $userQueryManager;
        $this->companyQueryManager = $companyQueryManager;
    }

    public function onUpdate(MaterialApiDtoInterface $dto, MaterialInterface $entity): MaterialInterface
    {
        $this->accessControl->denyAccessUnlessGranted(RoleInterface::ROLE_MATERIAL_EDIT, $entity);

        /* @var Material $entity */
        $entity->setUpdatedBy($this->accessControl->getAuthorizedUser());

        try {
            $entity->setCompany($this->companyQueryManager->proxy($dto->getCompanyApiDto()));
        } catch (\Exception $e) {
            throw new MaterialCannotBeSavedException($e->getMessage());
        }

        return $this->mediator->onUpdate($dto, $entity);
    }

    public function onDelete(MaterialApiDtoInterface $dto, MaterialInterface $entity): void
    {
        $this->accessControl->denyAccessUnlessGranted(RoleInterface::ROLE_MATERIAL_EDIT, $entity);

        $this->mediator->onDelete($dto, $entity);
    }

    public function onCreate(MaterialApiDtoInterface $dto, MaterialInterface $entity): MaterialInterface
    {
        /* @var Material $entity */
        if (!($dto instanceof AppMaterialDto && $dto->isSkip())) {
            $this->accessControl->denyAccessUnlessGranted(RoleInterface::ROLE_MATERIAL_CREATE, $entity);
            $entity->setCreatedBy($this->accessControl->getAuthorizedUser());
        } else {
            $request = new Request(
                [
                    UserApiDtoInterface::DTO_CLASS => UserApiDto::class,
                    UserApiDtoInterface::USERNAME => $dto->getCreatedBy(),
                ]
            );
            $userApiDto = $this->factoryDto->setRequest($request)->createDto(UserApiDto::class);
            $response = $this->userQueryManager->criteria($userApiDto);
            if (1 === \count($response)) {
                $entity->setCreatedBy($response[0]);
            }
        }

        try {
            $entity->setCompany($this->companyQueryManager->proxy($dto->getCompanyApiDto()));
        } catch (\Exception $e) {
            throw new MaterialCannotBeCreatedException($e->getMessage());
        }

        return $this->mediator->onCreate($dto, $entity);
    }
}
