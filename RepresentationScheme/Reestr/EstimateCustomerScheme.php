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
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\Cust\Contracted;

final class EstimateCustomerScheme extends AbstractBudgetItemEstimateScheme
{
    public const STAGE = 'stage';

    private Number $stageNumber;
    private Contracted $contracted;

    protected function init()
    {
        parent::init();

        $this->stageNumber = new Number('Этап');
        $this->contracted = new Contracted();
    }

    public function stageNumber(string $entityPrefix): Number
    {
        return $this->stageNumber->setEntityName($entityPrefix);
    }

    public function contracted(): Contracted
    {
        return $this->contracted->setEntityName($this->entityPrefix);
    }
}
