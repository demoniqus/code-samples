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

namespace App\Resource\Serializer\Symfony;

use Enorma\UtilsBundle\Serialize\AbstractConfiguration;

class ConfigurationMaterial extends AbstractConfiguration
{
    protected string $fileName = '/src/Resource/Resources/serializer/Symfony/serializer/Resource/Resource.yml';
}
