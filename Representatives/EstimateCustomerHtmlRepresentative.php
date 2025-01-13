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
use CellBundle\Interfaces\FieldsInterface;

final class EstimateCustomerHtmlRepresentative extends AbstractEstimateCustomerRepresentative
{
    public function initFieldsCell(FieldsInterface $head): FieldsInterface
    {
        $scheme = $this->getScheme();
        $scheme->tree()->cellHeader($head);
        $scheme->estimateNumber($scheme::ESTIMATE)->cellHeader($head);
        $scheme->stageNumber($scheme::STAGE)->cellHeader($head);
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
        $scheme->contracted()->cellHeader($head);
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

    public function represent($dataItem, ParamsBag $paramsBag = null): array
    {
        $item = parent::represent($dataItem, $paramsBag);

        $comments = $paramsBag->getParam($this->getScheme()->comment()->name()) ?? [];

        $item[$this->getScheme()->commentsCount()->fullName()] = \count($comments);

        $lastComment = array_pop($comments);

        $item[$this->getScheme()->comment()->fullName()] = $this->representComment($lastComment);

        return $item;
    }
}
