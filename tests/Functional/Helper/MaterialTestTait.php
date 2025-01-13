<?php

declare(strict_types=1);

/*
 * This file is part of the package.
 *
 * Developer list:
 * (c) Dmitry Antipov <demoniqus@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Functional\Helper;

use App\Material\Dto\AppMaterialDtoInterface;
use App\Tests\Functional\Action\Fcr;
use App\Tests\Functional\DataFixtures\FcrFixtures;
use Enorma\FcrBundle\Dto\FcrApiDtoInterface;
use Enorma\FcrBundle\Tests\Functional\ValueObject\Fcr\Id as ValueObjectFcrId;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Active;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Description;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Id;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Position;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Start;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Title;
use Enorma\UtilsBundle\Model\ActiveModel;
use Enorma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;

trait MaterialTestTait
{
    protected static function withId(array $query): array
    {
        $query[AppMaterialDtoInterface::ID] = Id::value();

        return $query;
    }

    protected static function withFcr(array $query): array
    {
        $query[AppMaterialDtoInterface::FCR] = static::merge(Fcr::defaultData(), [FcrApiDtoInterface::ID => ValueObjectFcrId::value()]);

        return $query;
    }

    protected function checkMaterial($entity): void
    {
        Assert::assertArrayHasKey(AppMaterialDtoInterface::ID, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::ACTIVE, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::POSITION, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::FCR, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::TITLE, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::DESCRIPTION, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::START, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::IMAGE, $entity);
        Assert::assertArrayHasKey(AppMaterialDtoInterface::PREVIEW, $entity);
    }

    protected function material($entity): void
    {
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $entity);
        $this->checkMaterial($entity[PayloadModel::PAYLOAD][0]);
    }

    protected function runCriteria(): void
    {
        $query = static::getDefault([
            AppMaterialDtoInterface::ID => Id::blank(),
            AppMaterialDtoInterface::ACTIVE => Active::value(),
            AppMaterialDtoInterface::POSITION => Position::default(),
            AppMaterialDtoInterface::TITLE => Title::blank(),
            AppMaterialDtoInterface::DESCRIPTION => Description::blank(),
            AppMaterialDtoInterface::START => Start::blank(),
            AppMaterialDtoInterface::FCR => static::merge(Fcr::defaultData(), [FcrApiDtoInterface::ID => ValueObjectFcrId::value()]),
        ]);
        $response = $this->criteria($query);
        $this->testResponseStatusOK();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
        Assert::assertCount(1, $response[PayloadModel::PAYLOAD]);
        $entity = $response[PayloadModel::PAYLOAD][0];
        $this->checkMaterial($entity);
        Assert::assertEquals(ValueObjectFcrId::value(), $entity[AppMaterialDtoInterface::FCR][FcrApiDtoInterface::ID]);
    }

    protected function runCriteriaNotFound(): void
    {
        $query = static::getDefault([
            AppMaterialDtoInterface::ID => Id::blank(),
            AppMaterialDtoInterface::FCR => Fcr::defaultData(),
        ]);
        $response = $this->criteria($query);
        $this->testResponseStatusNotFound();
        Assert::assertArrayHasKey(PayloadModel::PAYLOAD, $response);
    }

    protected function makePut(): array
    {
        $find = $this->assertGet(Id::value());

        Assert::assertEquals(ActiveModel::ACTIVE, $find[PayloadModel::PAYLOAD][0][AppMaterialDtoInterface::ACTIVE]);

        $query = static::getDefault([
            AppMaterialDtoInterface::FCR => static::merge(Fcr::defaultData(), [FcrApiDtoInterface::ID => FcrFixtures::AVAILABLE_FCR_190]),
        ]);

        return $this->put($query);
    }
}
