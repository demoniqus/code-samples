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

final class CommentsCount extends AbstractSchemeItem
{
    protected string $title = 'Количество комментариев';

    public function name(): string
    {
        return 'commentsCount';
    }
}
