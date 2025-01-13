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

namespace App\Resource\Entity;

use Doctrine\ORM\Mapping as ORM;
use Enorma\CompanyBundle\Entity\Common\CommonCompanyTrait;
use Enorma\CompanyBundle\Model\Company\CompanyInterface as BaseCompanyInterface;
use Enorma\MaterialBundle\Entity\Material\BaseMaterial;
use Enorma\UserBundle\Entity\CreateUpdateByTrait;

/**
 * @ORM\Table(name="e_material")
 * @ORM\Entity
 */
class Material extends BaseMaterial implements MaterialInterface
{
    use CommonCompanyTrait;
    use CreateUpdateByTrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Company\Entity\Company", fetch="EAGER")
     */
    protected ?BaseCompanyInterface $company = null;
}
