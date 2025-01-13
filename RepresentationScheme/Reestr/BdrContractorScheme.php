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

namespace IncomeBudgetBundle\RepresentationScheme\Reestr;

use AppBundle\RepresentationScheme\Items\Number;
use IncomeBudgetBundle\RepresentationScheme\Items\Bdr\Contr\ParentBdr;
use IncomeBudgetBundle\RepresentationScheme\Items\Bdr\Rests;
use IncomeBudgetBundle\RepresentationScheme\Items\Company;

final class BdrContractorScheme extends AbstractBudgetItemBdrScheme
{
    public const STAGE = 'stage';
    private ParentBdr $parentBdr;
    private Company $company;
    private Number $stageNumber;

    protected function init()
    {
        parent::init();
        $this->parentBdr = new ParentBdr();
        $this->company = new Company();
        $this->stageNumber = new Number('Этап');
        $this->estimateNumber = new Number('Номер сметы/КС3');
        $this->rests = new Rests();
    }

    public function parentBdr(): ParentBdr
    {
        return $this->parentBdr->setEntityName($this->entityPrefix);
    }

    public function company(): Company
    {
        return $this->company->setEntityName($this->entityPrefix);
    }

    public function stageNumber(string $entityPrefix): Number
    {
        return $this->stageNumber->setEntityName($entityPrefix);
    }
}
