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
use EstimateBundle\Reestr\EstimateCustomer\EstimateCustomerLevels;
use IncomeBudgetBundle\DataObject\EstimateCustomer;
use IncomeBudgetBundle\Dto\BudgetItemDto;
use IncomeBudgetBundle\Entity\BudgetItemData;
use IncomeBudgetBundle\RepresentationScheme\Reestr\BdrContractorScheme;

abstract class AbstractBdrContractorRepresentative extends AbstractBudgetItemRepresentative
{
    private function printCheckboxState(bool $state): string
    {
        return $state ? 'Да' : 'Нет';
    }

    /**
     * @param BudgetItemDto $dataItem
     */
    public function represent($dataItem, ParamsBag $paramsBag = null): array
    {
        $scheme = $this->getScheme();

        $type = $paramsBag->getParam('type');

        /* @var $budgetItem BudgetItemData */
        $budgetItem = $dataItem->getEntity();
        $docNumber = $dataItem->getNumber();
        $parentBdrNumber = null;

        switch ($type) {
            case EstimateCustomerLevels::LEVEL_ONE:
                $docNumber = $dataItem->getNumber();
                break;
            case EstimateCustomerLevels::LEVEL_TWO:
                if ($budgetItem) {
                    $completed = $budgetItem->isState(EstimateCustomer::STATE_CONTRACTOR_COMPLETED) || $budgetItem->isState(EstimateCustomer::STATE_COMPLETED);
                    $completed = $this->printCheckboxState($completed);
                }
                break;
            case EstimateCustomerLevels::LEVEL_THREE:
                if ($budgetItem) {
                    $completed = $this->printCheckboxState($budgetItem->isCompleted());
                }
                break;
            case EstimateCustomerLevels::LEVEL_FOUR:
                $completed = '';
                $docNumber = $dataItem->getKs3();
                $parentBdrNumber = $dataItem->getParentBdr() ? $dataItem->getParentBdr()->getNumber() : null;
                break;
        }

        $item = [
            $scheme->estimateNumber($scheme::ESTIMATE)->fullName() => $docNumber,
            $scheme->company()->fullName() => $dataItem->getContract() && $dataItem->getContract()->getContragent() ? $dataItem->getContract()->getContragent()->getName() : '',
            $scheme->ks2Number($scheme::DOC)->fullName() => EstimateCustomerLevels::LEVEL_FOUR === $type && $dataItem->getDocument() ? $dataItem->getDocument()->getDocNumber() : '',
            $scheme->ks2Date($scheme::DOC)->fullName() => EstimateCustomerLevels::LEVEL_FOUR === $type && $dataItem->getDocument() && $dataItem->getDocument()->getDate() ? $dataItem->getDocument()->getDate()->format('d.m.Y') : '',
            $scheme->baseTotalSumCheck()->fullName() => $dataItem->getBaseTotalSum(),
            $scheme->baseEquipmentSum()->fullName() => $dataItem->getBaseEquipmentSum(),
            $scheme->baseMaterialSum()->fullName() => $dataItem->getBaseMatirialSum(),
            $scheme->baseServiceSum()->fullName() => $dataItem->getBaseServiceSum(),
            $scheme->sumWoNds()->fullName() => $dataItem->getSumWoNds(),
            $scheme->equipmentSum()->fullName() => $dataItem->getEquipmentSum(),
            $scheme->materialSum()->fullName() => $dataItem->getMatirialSum(),
            $scheme->serviceSum()->fullName() => $dataItem->getServiceSum(),
            $scheme->sumWithNds()->fullName() => $dataItem->getSumWithNds(),
            $scheme->rests()->fullName() => $dataItem->getBdrRests(),
            $scheme->completed()->fullName() => $completed ?? '',
            $scheme->stageNumber($scheme::STAGE)->fullName() => $budgetItem instanceof BudgetItemData && $budgetItem->getStage() ? $budgetItem->getStage()->getSelectTitle() : '',
            $scheme->ndsRate()->fullName() => EstimateCustomerLevels::LEVEL_FOUR === $type && $budgetItem ? $budgetItem->getNdsRate() : null,
            $scheme->parentBdr()->fullName() => $parentBdrNumber,
        ];

        $comments = $paramsBag->getParam('comments') ?? [];
        $item[$scheme->commentsCount()->fullName()] = \count($comments);
        $comment = array_pop($comments);
        $item[$scheme->comment()->fullName()] = $this->representComment($comment);

        return $item;
    }

    public function getScheme(): BdrContractorScheme
    {
        /* @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::getScheme();
    }
}
