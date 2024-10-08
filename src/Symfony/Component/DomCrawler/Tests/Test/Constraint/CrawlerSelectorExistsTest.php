<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DomCrawler\Tests\Test\Constraint;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Test\Constraint\CrawlerSelectorExists;

class CrawlerSelectorExistsTest extends TestCase
{
    public function testConstraint()
    {
        $constraint = new CrawlerSelectorExists('title');
        $this->assertTrue($constraint->evaluate(new Crawler('<html><head><title>'), '', true));
        $constraint = new CrawlerSelectorExists('h1');
        $this->assertFalse($constraint->evaluate(new Crawler('<html><head><title>'), '', true));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('Failed asserting that the Crawler matches selector "h1".');

        $constraint->evaluate(new Crawler('<html><head><title>'));
    }
}
