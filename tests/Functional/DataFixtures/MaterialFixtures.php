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

namespace App\Tests\Functional\DataFixtures;

use App\Material\Dto\AppMaterialDtoInterface;
use App\Material\Entity\Material;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Enorma\MaterialBundle\Dto\MaterialApiDtoInterface;
use Enorma\MaterialBundle\Entity\Material\BaseMaterial;
use Enorma\MaterialBundle\Fixtures\FileFixtures;
use Enorma\MaterialBundle\Fixtures\MaterialFixtures as BaseMaterialFixtures;

final class MaterialFixtures extends BaseMaterialFixtures implements FixtureGroupInterface, OrderedFixtureInterface
{
    protected function getData(): array
    {
        $array = [];
        foreach (static::$data as $value) {
            $value['created_by'] = '0';
            $value[AppMaterialDtoInterface::FCR] = '0';
            $value[MaterialApiDtoInterface::IMAGE] = implode(
                \DIRECTORY_SEPARATOR,
                ['', 'tmp', 'img_'.$value[MaterialApiDtoInterface::TITLE].'.png']
            );
            $value[MaterialApiDtoInterface::PREVIEW] = implode(
                \DIRECTORY_SEPARATOR,
                ['', 'tmp', 'img_prev_'.$value[MaterialApiDtoInterface::TITLE].'.png']
            );

            $array[] = $value;
        }

        static::$data = $array;

        return parent::getData();
    }

    protected static string $class = Material::class;

    public static function getReferenceName(): string
    {
        return FileFixtures::getReferenceName().(new \ReflectionClass(BaseMaterial::class))->getShortName().static::$splitter;
    }

    /**
     * @param Material $entity
     * @param array    $record
     */
    protected function expandEntity($entity, array $record): void
    {
        if (\array_key_exists(AppMaterialDtoInterface::FCR, $record)) {
            $shortFcr = FcrFixtures::getReferenceName();
            $entity->setFcr($this->getReference($shortFcr.$record[AppMaterialDtoInterface::FCR]));
        }
        if (\array_key_exists('created_by', $record)) {
            $shortUser = UserFixtures::getReferenceName();
            $entity->setCreatedBy($this->getReference($shortUser.$record['created_by']));
        }
    }

    public static function getGroups(): array
    {
        return [
            FixtureInterface::APP_MATERIAL_FIXTURES,
        ];
    }

    public function getOrder()
    {
        return 5;
    }
}
