<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Tests\Test\Constraint;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseHasCookie;

class ResponseHasCookieTest extends TestCase
{
    public function testConstraint()
    {
        $response = new Response();
        $response->headers->setCookie(Cookie::create('foo', 'bar'));
        $constraint = new ResponseHasCookie('foo');
        $this->assertTrue($constraint->evaluate($response, '', true));
        $constraint = new ResponseHasCookie('bar');
        $this->assertFalse($constraint->evaluate($response, '', true));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Failed asserting that the Response has cookie "bar".');

        $constraint->evaluate($response);
    }
}
