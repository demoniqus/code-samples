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

namespace App\Tests\Functional\Controller;

use App\Material\Dto\AppMaterialDto;
use App\Material\System\FileUploadingPathResolverInterface;
use App\Tests\Functional\CaseTest;
use App\Tests\Functional\DataFixtures\FixtureInterface;
use Enorma\TestUtilsBundle\Action\ActionTestInterface;
use Enorma\TestUtilsBundle\Helper\AbstractSymfony;
use Psr\Container\ContainerInterface;

final class MaterialTest extends CaseTest
{
    protected string $actionServiceName = 'App\Tests\Functional\Action\Material';

    protected function getActionService(ContainerInterface $container): ActionTestInterface
    {
        return $container->get($this->actionServiceName);
    }

    protected function getEntityClass(): string
    {
        return AppMaterialDto::class;
    }

    public static function getFixtures(): array
    {
        return [
            FixtureInterface::APP_FOS_USER_FIXTURES,
            FixtureInterface::APP_FCR_FIXTURES,
            FixtureInterface::APP_MATERIAL_FIXTURES,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $container = AbstractSymfony::checkVersion() ? $this->getContainer() : static::$container;

        /** @var FileUploadingPathResolverInterface $pathResolver */
        $pathResolver = $container->get(FileUploadingPathResolverInterface::class);
        $projectDir = $pathResolver->getPath([$container->getParameter('kernel.project_dir'), 'var']);

        $uploadingPath = $pathResolver->getRootUploadingDir();

        if ('' !== trim(str_replace($projectDir, '', $uploadingPath))) {
            $this->deleteDirectory($uploadingPath);
        }
    }

    private function deleteDirectory($dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ('.' == $item || '..' == $item) {
                continue;
            }

            if (!$this->deleteDirectory($dir.\DIRECTORY_SEPARATOR.$item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}
