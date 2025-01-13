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

namespace App\Tests\Functional\ValueObject\Material;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Preview extends \Enorma\MaterialBundle\Tests\Functional\ValueObject\Material\Preview
{
    protected static ?UploadedFile $file = null;
}
