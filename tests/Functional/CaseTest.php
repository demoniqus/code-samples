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

namespace App\Tests\Functional;

use App\Tests\Functional\Helper\AuthorizationTrait;
use Enorma\SecurityBundle\Model\SecurityModelInterface;
use Enorma\TestUtilsBundle\Functional\Orm\AbstractFunctionalTest;
use Enorma\TestUtilsBundle\Helper\AbstractSymfony;
use Enorma\TestUtilsBundle\Helper\DoctrineTestTrait;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class CaseTest extends AbstractFunctionalTest
{
    use AuthorizationTrait;
    use DoctrineTestTrait;

    protected int $userAuthorization = 1;

    protected function setUp(): void
    {
        self::bootKernel();

        parent::setUp();

        $container = AbstractSymfony::checkVersion() ? $this->getContainer() : static::$container;

        $this->setEntityManager($container);

        $this->authorization($container);
    }

    protected function authorization(ContainerInterface $container)
    {
        $this->getAuthorization($this->userAuthorization);
        $this->actionService->addCallBack($this->createJwt($container, $this->client));
    }

    protected function createJwt(ContainerInterface $container, $client): \Closure
    {
        $jwtTokenGenerator = $container->get('evrinoma.safety.token.jwt.generator');

        return function ($clientOverride = null) use ($client, $jwtTokenGenerator) {
            $jwtTokenGenerator->generate($this->user);
            $cookie = $jwtTokenGenerator->getRefreshTokenCookie();
            $client->getCookieJar()->set(new Cookie($cookie->getName(), $cookie->getValue(), (string) $cookie->getExpiresTime(), $cookie->getPath(), $cookie->getDomain()));
            $client->setServerParameter('HTTP_AUTHORIZATION', ucfirst(mb_strtolower(SecurityModelInterface::BEARER)).' '.$jwtTokenGenerator->getAccessToken());
        };
    }

    protected function tearDown(): void
    {
        restore_exception_handler();
    }
}
