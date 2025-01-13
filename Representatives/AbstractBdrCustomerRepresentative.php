<?php

declare(strict_types=1);

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
use EstimateBundle\Entity\Estimate;
use IncomeBudgetBundle\DataObject\EstimateCustomer;
use IncomeBudgetBundle\Dto\BudgetItemDto;
use IncomeBudgetBundle\Interfaces\BudgetItemDtoInterface;
use IncomeBudgetBundle\Interfaces\BudgetItemEntityInterface;
use IncomeBudgetBundle\Interfaces\BudgetItemInterface;
use IncomeBudgetBundle\RepresentationScheme\Reestr\BdrCustomerScheme;
use IncomeStageBundle\Entity\IncomeStage;

abstract class AbstractBdrCustomerRepresentative extends AbstractBudgetItemRepresentative
{
    private function printCheckboxState(bool $state): string
    {
        return $state ? 'Да' : 'Нет';
    }

    private function representStage(IncomeStage $stage, ParamsBag $paramsBag = null): array
    {
        $scheme = $this->getScheme();
        /** @var BudgetItemDtoInterface $dto */
        $dto = $paramsBag->getParam('sums');

        return [
            $scheme->estimateNumber($scheme::ESTIMATE)->fullName() => $stage->getSelectTitle(),
            $scheme->ks3Number($scheme::DOC)->fullName() => '',
            $scheme->activity()->fullName() => '',
            $scheme->ks2Date($scheme::DOC)->fullName() => '',
            $scheme->ks2Number($scheme::DOC)->fullName() => '',
            $scheme->baseTotalSumCheck()->fullName() => $dto->getBaseTotalSum(),
            $scheme->baseEquipmentSum()->fullName() => $dto->getBaseEquipmentSum(),
            $scheme->baseMaterialSum()->fullName() => $dto->getBaseMatirialSum(),
            $scheme->baseServiceSum()->fullName() => $dto->getBaseServiceSum(),
            $scheme->sumWoNds()->fullName() => $dto->getSumWoNds(),
            $scheme->equipmentSum()->fullName() => $dto->getEquipmentSum(),
            $scheme->materialSum()->fullName() => $dto->getMatirialSum(),
            $scheme->serviceSum()->fullName() => $dto->getServiceSum(),
            $scheme->sumWithNds()->fullName() => $dto->getSumWithNds(),
            $scheme->rests()->fullName() => $dto->bdrRests,
            $scheme->completed()->fullName() => $this->printCheckboxState(EstimateCustomer::STATE_COMPLETED === $stage->getState()),
            $scheme->ndsRate()->fullName() => null,
            $scheme->parent()->fullName() => null,
            $scheme->commentsCount()->fullName() => null,
            $scheme->comment()->fullName() => null,
        ];
    }

    private function representEstimate(Estimate $estimate, ParamsBag $paramsBag = null): array
    {//TODO Метод скорее всего надо будет доработать вместе с  EstimateBundle\Reestr\EstimateCustomer\Additional\BdrCustomer\BdrCustomerReestr
        $scheme = $this->getScheme();
        /** @var BudgetItemDto $dto */
        $dto = $estimate->getTmpData();

        return [
            $scheme->estimateNumber($scheme::ESTIMATE)->fullName() => $estimate->getNumber(),
            $scheme->ks3Number($scheme::DOC)->fullName() => '',
            $scheme->activity()->fullName() => '',
            $scheme->ks2Date($scheme::DOC)->fullName() => '',
            $scheme->ks2Number($scheme::DOC)->fullName() => '',
            $scheme->baseTotalSumCheck()->fullName() => $dto->getBaseTotalSum(),
            $scheme->baseEquipmentSum()->fullName() => $dto->getBaseEquipmentSum(),
            $scheme->baseMaterialSum()->fullName() => $dto->getBaseMatirialSum(),
            $scheme->baseServiceSum()->fullName() => $dto->getBaseServiceSum(),
            $scheme->sumWoNds()->fullName() => $dto->getSumWoNds(),
            $scheme->equipmentSum()->fullName() => $dto->getEquipmentSum(),
            $scheme->materialSum()->fullName() => $dto->getMatirialSum(),
            $scheme->serviceSum()->fullName() => $dto->getServiceSum(),
            $scheme->sumWithNds()->fullName() => $dto->getSumWithNds(),
            $scheme->rests()->fullName() => $dto->bdrRests,
            $scheme->completed()->fullName() => $this->printCheckboxState($dto->amountCloseData),
            $scheme->ndsRate()->fullName() => null,
            $scheme->parent()->fullName() => null,
            $scheme->commentsCount()->fullName() => null,
            $scheme->comment()->fullName() => null,
        ];
    }

    /**
     * @param BudgetItemEntityInterface $dataItem
     */
    private function representBID(BudgetItemInterface $dataItem, ParamsBag $paramsBag = null): array
    {
        $scheme = $this->getScheme();
        $type = $paramsBag->getParam('type');
        $ks3 = $dataItem->getDocument() ?
            $dataItem->getDocument()->getKs3Number() :
            ''
        ;
        $dateFormat = 'd.m.Y';

        $comments = $paramsBag->getParam('comments') ?? [];
        $commentsCount = \count($comments);
        $comment = array_pop($comments);

        return [
            $scheme->estimateNumber($scheme::ESTIMATE)->fullName() => 'estimate' === $type ? $dataItem->getNumber() : '',
            $scheme->ks3Number($scheme::DOC)->fullName() => $ks3,
            $scheme->activity()->fullName() => 'budgetItem' === $type && $dataItem->getActivity() ? $dataItem->getActivity()->getName() : '',
            $scheme->ks2Date($scheme::DOC)->fullName() => 'budgetItem' === $type ? ($dataItem->getDocument() && $dataItem->getDocument()->getDate() ? $dataItem->getDocument()->getDate()->format($dateFormat) : (new \DateTime())->format($dateFormat)) : (new \DateTime())->format($dateFormat),
            $scheme->ks2Number($scheme::DOC)->fullName() => 'budgetItem' === $type ? ($dataItem->getDocument() && $dataItem->getDocument()->getDocNumber() ? $dataItem->getDocument()->getDocNumber() : '') : '',
            $scheme->baseTotalSumCheck()->fullName() => $dataItem->getBaseTotalSum(),
            $scheme->baseEquipmentSum()->fullName() => $dataItem->getBaseEquipmentSum(),
            $scheme->baseMaterialSum()->fullName() => $dataItem->getBaseMatirialSum(),
            $scheme->baseServiceSum()->fullName() => $dataItem->getBaseServiceSum(),
            $scheme->sumWoNds()->fullName() => $dataItem->getSumWoNds(),
            $scheme->equipmentSum()->fullName() => $dataItem->getEquipmentSum(),
            $scheme->materialSum()->fullName() => $dataItem->getMatirialSum(),
            $scheme->serviceSum()->fullName() => $dataItem->getServiceSum(),
            $scheme->sumWithNds()->fullName() => $dataItem->getSumWithNds(),
            $scheme->rests()->fullName() => 0,
            $scheme->completed()->fullName() => null,
            $scheme->ndsRate()->fullName() => $dataItem->getNdsRate(),
            $scheme->parent()->fullName() => $dataItem->getParent() ? $dataItem->getParent()->getNumber() : null,
            $scheme->commentsCount()->fullName() => $commentsCount,
            $scheme->comment()->fullName() => $this->representComment($comment),
        ];
    }

    /**
     * @param IncomeStage|BudgetItemDto|Estimate $dataItem
     * @param ParamsBag|null                     $paramsBag
     *
     * @return array
     */
    public function represent($dataItem, ParamsBag $paramsBag = null): array
    {
        if ($dataItem instanceof IncomeStage) {
            return $this->representStage($dataItem, $paramsBag);
        }

        $type = $paramsBag->getParam('type');

        return 'estimateReestrItem' === $type ?
            $this->representEstimate($dataItem, $paramsBag) :
            $this->representBID($dataItem, $paramsBag);
    }

    public function getScheme(): BdrCustomerScheme
    {
        /* @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::getScheme();
    }
}
