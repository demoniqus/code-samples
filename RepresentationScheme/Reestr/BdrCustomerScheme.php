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
use AppBundle\RepresentationScheme\Items\ParentEntity;
use IncomeBudgetBundle\RepresentationScheme\Items\Bdr\Cust\Ks3;
use IncomeBudgetBundle\RepresentationScheme\Items\Bdr\Rests;

final class BdrCustomerScheme extends AbstractBudgetItemBdrScheme
{
    private ParentEntity $parent;
    private Ks3          $ks3Number;

    protected function init()
    {
        parent::init();
        $this->estimateNumber = new Number('Номер сметы/Этапа');
        $this->parent = new ParentEntity('Смета заказчика');
        $this->rests = new Rests('Незакрытый объем');
        $this->ks3Number = new Ks3();
    }

    public function parent(): ParentEntity
    {
        return $this->parent->setEntityName($this->entityPrefix);
    }

    public function ks3Number(string $entityPrefix): Ks3
    {
        return $this->ks3Number->setEntityName($entityPrefix);
    }
}
