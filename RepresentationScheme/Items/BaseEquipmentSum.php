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

namespace ApplicationBundle\RepresentationScheme\Items;

use ApplicationBundle\DataItemScheme\AbstractSchemeItem;

final class BaseEquipmentSum extends AbstractSchemeItem
{
    protected string $title = 'ОБ БЦ';

    public function name(): string
    {
        return 'baseEquipmentSum';
    }
}
