<?php

/*
 * This file is part of the package Tech product.
 *
 * Developer list:
 * (c) Dmitry Antipov <demoniqus@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IncomeBudgetBundle\Representatives;

use AppBundle\ParamsBag\ParamsBag;
use IncomeBudgetBundle\Dto\BudgetItemDto;
use IncomeBudgetBundle\RepresentationScheme\Reestr\EstimateCustomerScheme;

abstract class AbstractEstimateCustomerRepresentative extends AbstractBudgetItemRepresentative
{
    /**
     * @param BudgetItemDto  $dataItem
     * @param ParamsBag|null $paramsBag
     *
     * @return array
     */
    public function represent($dataItem, ParamsBag $paramsBag = null): array
    {
        $scheme = $this->getScheme();
        $budget = $dataItem->getBudgetItem();

        return [
            $scheme->estimateNumber($scheme::ESTIMATE)->fullName() => $dataItem->getNumber(),
            $scheme->baseTotalSumCheck()->fullName() => $dataItem->getBaseTotalSum(),
            $scheme->baseEquipmentSum()->fullName() => $dataItem->getBaseEquipmentSum(),
            $scheme->baseMaterialSum()->fullName() => $dataItem->getBaseMatirialSum(),
            $scheme->baseServiceSum()->fullName() => $dataItem->getBaseServiceSum(),
            $scheme->sumWoNds()->fullName() => $dataItem->getSumWoNds(),
            $scheme->sumWithNds()->fullName() => $dataItem->getSumWithNds(),
            $scheme->ndsRate()->fullName() => $dataItem->getNdsRate(),
            $scheme->equipmentSum()->fullName() => $dataItem->getEquipmentSum(),
            $scheme->materialSum()->fullName() => $dataItem->getMatirialSum(),
            $scheme->serviceSum()->fullName() => $dataItem->getServiceSum(),
            $scheme->activity()->fullName() => $dataItem->getActivity() ? $dataItem->getActivity()->getName() : null,
            $scheme->contracted()->fullName() => $dataItem->isContracted() ? 'Да' : 'Нет',
            $scheme->restsBaseTotalSum()->fullName() => $dataItem->getRestBCTotal() ?? 0,
            $scheme->restsBaseEquipmentSum()->fullName() => $dataItem->getRestBCEquipment() ?? 0,
            $scheme->restsBaseMaterialSum()->fullName() => $dataItem->getRestBCMaterial() ?? 0,
            $scheme->restsBaseServiceSum()->fullName() => $dataItem->getRestBCService() ?? 0,
            $scheme->restsTotalSum()->fullName() => $dataItem->getRestTotal() ?? 0,
            $scheme->restsEquipmentSum()->fullName() => $dataItem->getRestEquipment() ?? 0,
            $scheme->restsMaterialSum()->fullName() => $dataItem->getRestMaterial() ?? 0,
            $scheme->restsServiceSum()->fullName() => $dataItem->getRestService() ?? 0,
            $scheme->stageNumber($scheme::STAGE)->fullName() => $budget ? $budget->getStage()->getName() : null,
        ];
    }

    public function getScheme(): EstimateCustomerScheme
    {
        /* @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::getScheme();
    }
}
