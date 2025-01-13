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

use AppBundle\DataItemScheme\AbstractDataItemScheme;
use AppBundle\RepresentationScheme\Items\Activity;
use AppBundle\RepresentationScheme\Items\BaseEquipmentSum;
use AppBundle\RepresentationScheme\Items\BaseMaterialSum;
use AppBundle\RepresentationScheme\Items\BaseServiceSum;
use AppBundle\RepresentationScheme\Items\BaseTotalSumCheck;
use AppBundle\RepresentationScheme\Items\Comment;
use AppBundle\RepresentationScheme\Items\CommentsCount;
use AppBundle\RepresentationScheme\Items\EquipmentSum;
use AppBundle\RepresentationScheme\Items\MaterialSum;
use AppBundle\RepresentationScheme\Items\NdsRate;
use AppBundle\RepresentationScheme\Items\Number;
use AppBundle\RepresentationScheme\Items\ServiceSum;
use AppBundle\RepresentationScheme\Items\SumWithNds;
use AppBundle\RepresentationScheme\Items\SumWoNds;
use AppBundle\RepresentationScheme\Items\Tree;

abstract class AbstractBudgetItemScheme extends AbstractDataItemScheme
{
    public const ESTIMATE = 'estimate';

    protected BaseEquipmentSum $baseEquipmentSum;
    protected BaseMaterialSum $baseMaterialSum;
    protected BaseServiceSum $baseServiceSum;
    protected EquipmentSum $equipmentSum;
    protected MaterialSum $materialSum;
    protected ServiceSum $serviceSum;
    protected NdsRate $ndsRate;
    protected SumWoNds $sumWoNds;
    protected SumWithNds $sumWithNds;
    protected Number $number;
    protected Activity $activity;
    protected Tree $tree;
    protected BaseTotalSumCheck $baseTotalSumCheck;
    protected Comment           $comment;
    protected CommentsCount     $commentsCount;
    protected Number $estimateNumber;

    protected function init()
    {
        parent::init();
        $this->baseEquipmentSum = new BaseEquipmentSum();
        $this->baseMaterialSum = new BaseMaterialSum();
        $this->baseServiceSum = new BaseServiceSum();
        $this->equipmentSum = new EquipmentSum();
        $this->materialSum = new MaterialSum();
        $this->serviceSum = new ServiceSum();
        $this->ndsRate = new NdsRate();
        $this->sumWoNds = new SumWoNds();
        $this->sumWithNds = new SumWithNds();
        $this->number = new Number();
        $this->activity = new Activity();
        $this->tree = new Tree();
        $this->baseTotalSumCheck = new BaseTotalSumCheck();
        $this->comment = new Comment();
        $this->commentsCount = new CommentsCount();
    }

    public function baseEquipmentSum(): BaseEquipmentSum
    {
        return $this->baseEquipmentSum->setEntityName($this->entityPrefix);
    }

    public function baseMaterialSum(): BaseMaterialSum
    {
        return $this->baseMaterialSum->setEntityName($this->entityPrefix);
    }

    public function baseServiceSum(): BaseServiceSum
    {
        return $this->baseServiceSum->setEntityName($this->entityPrefix);
    }

    public function equipmentSum(): EquipmentSum
    {
        return $this->equipmentSum->setEntityName($this->entityPrefix);
    }

    public function materialSum(): MaterialSum
    {
        return $this->materialSum->setEntityName($this->entityPrefix);
    }

    public function serviceSum(): ServiceSum
    {
        return $this->serviceSum->setEntityName($this->entityPrefix);
    }

    public function ndsRate(): NdsRate
    {
        return $this->ndsRate->setEntityName($this->entityPrefix);
    }

    public function sumWoNds(): SumWoNds
    {
        return $this->sumWoNds->setEntityName($this->entityPrefix);
    }

    public function sumWithNds(): SumWithNds
    {
        return $this->sumWithNds->setEntityName($this->entityPrefix);
    }

    public function number(): Number
    {
        return $this->number->setEntityName($this->entityPrefix);
    }

    public function activity(): Activity
    {
        return $this->activity->setEntityName($this->entityPrefix);
    }

    public function baseTotalSumCheck(): BaseTotalSumCheck
    {
        return $this->baseTotalSumCheck->setEntityName($this->entityPrefix);
    }

    public function tree(): Tree
    {
        return $this->tree;
    }

    public function commentsCount(): CommentsCount
    {
        return $this->commentsCount->setEntityName($this->entityPrefix);
    }

    public function comment(): Comment
    {
        return $this->comment->setEntityName($this->entityPrefix);
    }

    public function estimateNumber(string $entityPrefix): Number
    {
        /*
         * Т.к. в реестре для estimateNumber используется другой alias сущности, то используем обязательный аргумент
         * для функции. Однако при этом оставим пользователю возможность самостоятельно выбрать нужный ему alias,
         * хотя и предложим alias по умолчанию self::ESTIMATE
         */
        return $this->estimateNumber->setEntityName($entityPrefix);
    }
}
