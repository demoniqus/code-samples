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

namespace App\Resource\Voter;

use Enorma\MaterialBundle\Entity\Material\BaseMaterial;
use Enorma\SecurityBundle\Voter\RoleInterface as SuperUserRoleInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MaterialVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        if (!\in_array($attribute, [RoleInterface::ROLE_MATERIAL_EDIT, RoleInterface::ROLE_MATERIAL_CREATE])) {
            return false;
        }

        if (!$subject instanceof BaseMaterial) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->hasRole(SuperUserRoleInterface::ROLE_SUPER_ADMIN) || $user->hasRole(RoleInterface::ROLE_MATERIAL_EDIT) || $user->hasRole(RoleInterface::ROLE_MATERIAL_CREATE)) {
            return true;
        }

        throw new \LogicException('You don\'t have specific permission');
    }
}
