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

namespace App\Tests\Functional\Action;

use App\Material\Dto\AppMaterialDto;
use App\Material\Dto\AppMaterialDtoInterface;
use App\Tests\Functional\DataFixtures\FcrFixtures;
use App\Tests\Functional\Helper\MaterialTestTait;
use App\Tests\Functional\ValueObject\Material\Image;
use App\Tests\Functional\ValueObject\Material\Preview;
use Enorma\FcrBundle\Dto\FcrApiDtoInterface;
use Enorma\MaterialBundle\Dto\MaterialApiDtoInterface;
use Enorma\MaterialBundle\Tests\Functional\Action\BaseMaterial;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Active;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Description;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Id;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Position;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Start;
use Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Title;
use Enorma\UtilsBundle\Model\Rest\PayloadModel;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Material extends BaseMaterial
{
    use MaterialTestTait;

    protected static function getDtoClass(): string
    {
        return AppMaterialDto::class;
    }

    protected static function initFiles(): void
    {
        /*
         * Material не может иметь файлов с одинаковым наименованием.
         * Поэтому сразу делаем разные Image и Preview
         */
        $fileTypes = [
            MaterialApiDtoInterface::IMAGE => 'image',
            MaterialApiDtoInterface::PREVIEW => 'preview',
        ];

        $files = [];

        foreach ($fileTypes as $fileType => $prefix) {
            $path = tempnam(sys_get_temp_dir(), 'http');
            file_put_contents($path, $prefix.'_my_file');
            $files[$fileType] = new UploadedFile($path, $prefix.'_my_file');
        }

        static::$files = [
            static::getDtoClass() => $files,
        ];
    }

    protected static function defaultData(): array
    {
        static::initFiles();

        $default = [
            MaterialApiDtoInterface::DTO_CLASS => static::getDtoClass(),
            MaterialApiDtoInterface::ID => Id::value(),
            MaterialApiDtoInterface::TITLE => Title::default(),
            MaterialApiDtoInterface::POSITION => Position::value(),
            MaterialApiDtoInterface::ACTIVE => Active::value(),
            MaterialApiDtoInterface::DESCRIPTION => Description::default(),
            MaterialApiDtoInterface::START => Start::default(),
            MaterialApiDtoInterface::IMAGE => Image::default(),
            MaterialApiDtoInterface::PREVIEW => Preview::default(),
        ];

        return static::withFcr(static::withId($default));
    }

    protected function createMaterial(): array
    {
        $query = static::getDefault();
        $query[MaterialApiDtoInterface::ID] = null;

        return $this->post($query);
    }

    public function actionPost(): void
    {
        $created = $this->createMaterial();
        $this->testResponseStatusCreated();
        $this->material($created);
    }

    public function actionPostDuplicate(): void
    {
        Assert::markTestIncomplete('This test has not been implemented yet.');
    }

    public function actionPut(): void
    {
        $updated = $this->makePut();
        $this->testResponseStatusOK();
        $this->material($updated);
        Assert::assertEquals(FcrFixtures::AVAILABLE_FCR_190, $updated[PayloadModel::PAYLOAD][0][AppMaterialDtoInterface::FCR][FcrApiDtoInterface::ID]);
    }

    public function actionCriteria(): void
    {
        $this->runCriteria();
    }

    public function actionCriteriaNotFound(): void
    {
        $this->runCriteriaNotFound();
    }
}
