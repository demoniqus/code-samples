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
use IncomeBudgetBundle\RepresentationScheme\Items\Company;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\Contr\Contract;

final class EstimateContractorScheme extends AbstractBudgetItemEstimateScheme
{
    public const STAGE = 'stage';

    private Number $stageNumber;
    private Contract $contract;
    private Company  $company;

    protected function init()
    {
        parent::init();
        $this->stageNumber = new Number('Наименование этапа');
        $this->contract = new Contract();
        $this->company = new Company();
    }

    public function stageNumber(string $entityPrefix): Number
    {
        return $this->stageNumber->setEntityName($entityPrefix);
    }

    public function contract(): Contract
    {
        return $this->contract->setEntityName($this->entityPrefix);
    }

    public function company(): Company
    {
        return $this->company->setEntityName($this->entityPrefix);
    }
}
