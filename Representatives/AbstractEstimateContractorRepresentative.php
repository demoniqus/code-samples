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
use CellBundle\Interfaces\FieldsInterface;
use IncomeBudgetBundle\Dto\BudgetItemDto;
use IncomeBudgetBundle\RepresentationScheme\Reestr\EstimateContractorScheme;

abstract class AbstractEstimateContractorRepresentative extends AbstractBudgetItemRepresentative
{
    public function initFieldsCell(FieldsInterface $head): FieldsInterface
    {
        $scheme = $this->getScheme();
        $scheme->tree()->cellHeader($head);
        $scheme->estimateNumber($scheme::ESTIMATE)->cellHeader($head);
        $scheme->contract()->cellHeader($head);
        $scheme->company()->cellHeader($head);
        $scheme->activity()->cellHeader($head);
        $scheme->baseTotalSumCheck()->cellHeader($head);
        $scheme->baseEquipmentSum()->cellHeader($head);
        $scheme->baseMaterialSum()->cellHeader($head);
        $scheme->baseServiceSum()->cellHeader($head);
        $scheme->sumWoNds()->cellHeader($head);
        $scheme->equipmentSum()->cellHeader($head);
        $scheme->materialSum()->cellHeader($head);
        $scheme->serviceSum()->cellHeader($head);
        $scheme->ndsRate()->cellHeader($head);
        $scheme->sumWithNds()->cellHeader($head);
        $scheme->stageNumber($scheme::STAGE)->cellHeader($head);
        $scheme->commentsCount()->cellHeader($head);
        $scheme->comment()->cellHeader($head);
        $scheme->restsBaseTotalSum()->cellHeader($head);
        $scheme->restsBaseEquipmentSum()->cellHeader($head);
        $scheme->restsBaseMaterialSum()->cellHeader($head);
        $scheme->restsBaseServiceSum()->cellHeader($head);
        $scheme->restsTotalSum()->cellHeader($head);
        $scheme->restsEquipmentSum()->cellHeader($head);
        $scheme->restsMaterialSum()->cellHeader($head);
        $scheme->restsServiceSum()->cellHeader($head);

        return $head;
    }

    /**
     * @param BudgetItemDto $dataItem
     */
    public function represent($dataItem, ParamsBag $paramsBag = null): array
    {
        $scheme = $this->getScheme();
        $isEstContr = $dataItem->isEstimateContractor();

        return [
            $scheme->estimateNumber($scheme::ESTIMATE)->fullName() => $dataItem->getNumber(),
            $scheme->baseTotalSumCheck()->fullName() => $dataItem->getBaseTotalSum(),
            $scheme->baseEquipmentSum()->fullName() => $dataItem->getBaseEquipmentSum(),
            $scheme->baseMaterialSum()->fullName() => $dataItem->getBaseMatirialSum(),
            $scheme->baseServiceSum()->fullName() => $dataItem->getBaseServiceSum(),
            $scheme->sumWoNds()->fullName() => $dataItem->getSumWoNds(),
            $scheme->sumWithNds()->fullName() => $dataItem->getSumWithNds(),
            $scheme->ndsRate()->fullName() => $isEstContr ? $dataItem->getNdsRate() : null,
            $scheme->equipmentSum()->fullName() => $dataItem->getEquipmentSum(),
            $scheme->materialSum()->fullName() => $dataItem->getMatirialSum(),
            $scheme->serviceSum()->fullName() => $dataItem->getServiceSum(),
            $scheme->activity()->fullName() => $isEstContr && $dataItem->getActivity() ? $dataItem->getActivity()->getName() : null,
            $scheme->restsBaseTotalSum()->fullName() => $dataItem->getRestBCTotal() ?? 0,
            $scheme->restsBaseEquipmentSum()->fullName() => $dataItem->getRestBCEquipment() ?? 0,
            $scheme->restsBaseMaterialSum()->fullName() => $dataItem->getRestBCMaterial() ?? 0,
            $scheme->restsBaseServiceSum()->fullName() => $dataItem->getRestBCService() ?? 0,
            $scheme->restsTotalSum()->fullName() => $dataItem->getRestTotal() ?? 0,
            $scheme->restsEquipmentSum()->fullName() => $dataItem->getRestEquipment() ?? 0,
            $scheme->restsMaterialSum()->fullName() => $dataItem->getRestMaterial() ?? 0,
            $scheme->restsServiceSum()->fullName() => $dataItem->getRestService() ?? 0,
            $scheme->stageNumber($scheme::STAGE)->fullName() => $dataItem->getStage() ? $dataItem->getStage()->getSelectTitle() : null, //у пустой итоговой строки может вообще ничего не быть
            $scheme->contract()->fullName() => $isEstContr && $dataItem->getContract() ? $dataItem->getContract()->getTitle() : null,
            $scheme->company()->fullName() => $isEstContr && $dataItem->getContract() ? $dataItem->getContract()->getContragent()->getName() : null,
        ];
    }

    public function getScheme(): EstimateContractorScheme
    {
        /* @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::getScheme();
    }
}
