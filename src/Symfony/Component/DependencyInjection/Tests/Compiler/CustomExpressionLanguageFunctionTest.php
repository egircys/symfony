<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Tests\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class CustomExpressionLanguageFunctionTest extends TestCase
{
    public function testDump()
    {
        $container = new ContainerBuilder();
        $container->register('test', 'stdClass')
            ->setPublic(true)
            ->setArguments([new Expression('custom_func("foobar")')]);

        $container->addExpressionLanguageProvider(new class implements ExpressionFunctionProviderInterface {
            public function getFunctions(): array
            {
                return [
                    ExpressionFunction::fromPhp('strtolower', 'custom_func'),
                ];
            }
        });
        $container->compile();

        $dump = new PhpDumper($container);
        $dumped = $dump->dump();

        $this->assertStringContainsString('strtolower("foobar")', $dumped);
    }
}
