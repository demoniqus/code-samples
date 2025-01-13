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

use AppBundle\RepresentationScheme\Items\Completed;
use AppBundle\RepresentationScheme\Items\Number;
use IncomeBudgetBundle\RepresentationScheme\Items\Bdr\Date;
use IncomeBudgetBundle\RepresentationScheme\Items\Bdr\Rests;

abstract class AbstractBudgetItemBdrScheme extends AbstractBudgetItemScheme
{
    public const DOC = 'doc';
    protected Date $ks2Date;
    protected Number $ks2Number;
    protected Rests $rests;
    private Completed $completed;

    protected function init()
    {
        parent::init();

        $this->ks2Date = new Date('Дата КС2');
        $this->ks2Number = new Number('Номер акта КС2');
        $this->completed = new Completed();
    }

    public function ks2Date(string $entityPrefix): Date
    {
        return $this->ks2Date->setEntityName($entityPrefix);
    }

    public function ks2Number(string $entityPrefix): Number
    {
        return $this->ks2Number->setEntityName($entityPrefix);
    }

    public function rests(): Rests
    {
        return $this->rests->setEntityName($this->entityPrefix);
    }

    public function completed(): Completed
    {
        return $this->completed->setEntityName($this->entityPrefix);
    }
}
