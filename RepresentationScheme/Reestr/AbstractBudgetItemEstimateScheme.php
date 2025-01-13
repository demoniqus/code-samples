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
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsBaseEquipmentSum;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsBaseMaterialSum;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsBaseServiceSum;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsBaseTotalSum;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsEquipmentSum;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsMaterialSum;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsServiceSum;
use IncomeBudgetBundle\RepresentationScheme\Items\Estimate\RestsTotalSum;

abstract class AbstractBudgetItemEstimateScheme extends AbstractBudgetItemScheme
{
    protected RestsBaseEquipmentSum $restsBaseEquipmentSum;
    protected RestsBaseMaterialSum $restsBaseMaterialSum;
    protected RestsBaseServiceSum $restsBaseServiceSum;
    protected RestsBaseTotalSum  $restsBaseTotalSum;
    protected RestsEquipmentSum $restsEquipmentSum;
    protected RestsMaterialSum $restsMaterialSum;
    protected RestsServiceSum $restsServiceSum;
    protected RestsTotalSum  $restsTotalSum;

    protected function init()
    {
        parent::init();
        $this->restsBaseEquipmentSum = new RestsBaseEquipmentSum();
        $this->restsBaseMaterialSum = new RestsBaseMaterialSum();
        $this->restsBaseServiceSum = new RestsBaseServiceSum();
        $this->restsBaseTotalSum = new RestsBaseTotalSum();
        $this->restsEquipmentSum = new RestsEquipmentSum();
        $this->restsMaterialSum = new RestsMaterialSum();
        $this->restsServiceSum = new RestsServiceSum();
        $this->restsTotalSum = new RestsTotalSum();

        $this->estimateNumber = new Number('Номер сметы');
    }

    public function restsBaseEquipmentSum(): RestsBaseEquipmentSum
    {
        return $this->restsBaseEquipmentSum->setEntityName($this->entityPrefix);
    }

    public function restsBaseMaterialSum(): RestsBaseMaterialSum
    {
        return $this->restsBaseMaterialSum->setEntityName($this->entityPrefix);
    }

    public function restsBaseServiceSum(): RestsBaseServiceSum
    {
        return $this->restsBaseServiceSum->setEntityName($this->entityPrefix);
    }

    public function restsBaseTotalSum(): RestsBaseTotalSum
    {
        return $this->restsBaseTotalSum->setEntityName($this->entityPrefix);
    }

    public function restsEquipmentSum(): RestsEquipmentSum
    {
        return $this->restsEquipmentSum->setEntityName($this->entityPrefix);
    }

    public function restsMaterialSum(): RestsMaterialSum
    {
        return $this->restsMaterialSum->setEntityName($this->entityPrefix);
    }

    public function restsServiceSum(): RestsServiceSum
    {
        return $this->restsServiceSum->setEntityName($this->entityPrefix);
    }

    public function restsTotalSum(): RestsTotalSum
    {
        return $this->restsTotalSum->setEntityName($this->entityPrefix);
    }
}
