<?php

declare(strict_types=1);

/*
 * This file is part of the package Tech product.
 *
 * Developer list:
 * (c) Dmitry Antipov <demoniqus@mail.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IncomeBudgetBundle\Representatives;

use AppBundle\Interfaces\DataItemRepresentativeInterface;
use AppBundle\Interfaces\DataRepresentationSchemeInterface;
use Comment\Entity\Comment;

abstract class AbstractBudgetItemRepresentative implements DataItemRepresentativeInterface
{
    private DataRepresentationSchemeInterface $budgetItemScheme;

    public function __construct(
        DataRepresentationSchemeInterface $budgetItemScheme
    ) {
        $this->budgetItemScheme = $budgetItemScheme;
    }

    /**
     * @param array|Comment $comment
     */
    protected function representComment($comment): ?string
    {
        return $comment ?
            (
                \is_array($comment) ?
                    $comment['text'] :
                    $comment->getText()
            ) :
            null;
    }

    public function getScheme(): DataRepresentationSchemeInterface
    {
        return $this->budgetItemScheme;
    }
}
