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

use CellBundle\Interfaces\FieldsInterface;

final class BdrContractorHtmlRepresentative extends AbstractBdrContractorRepresentative
{
    public function initFieldsCell(FieldsInterface $head): FieldsInterface
    {
        $scheme = $this->getScheme();
        $scheme->tree()->cellHeader($head);
        $scheme->estimateNumber($scheme::ESTIMATE)->cellHeader($head);
        $scheme->parentBdr()->cellHeader($head);
        $scheme->company()->cellHeader($head);
        $scheme->ks2Date($scheme::DOC)->cellHeader($head);
        $scheme->ks2Number($scheme::DOC)->cellHeader($head);
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
        $scheme->rests()->cellHeader($head);
        $scheme->completed()->cellHeader($head);
        $scheme->stageNumber($scheme::STAGE)->cellHeader($head);
        $scheme->commentsCount()->cellHeader($head);
        $scheme->comment()->cellHeader($head);
        $scheme->activity()->cellHeader($head);

        return $head;
    }
}
