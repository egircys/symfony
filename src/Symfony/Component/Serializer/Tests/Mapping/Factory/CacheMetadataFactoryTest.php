<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer\Tests\Mapping\Factory;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Mapping\ClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\CacheClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Tests\Fixtures\Dummy;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class CacheMetadataFactoryTest extends TestCase
{
    public function testGetMetadataFor()
    {
        $metadata = new ClassMetadata(Dummy::class);

        $decorated = $this->createMock(ClassMetadataFactoryInterface::class);
        $decorated
            ->expects($this->once())
            ->method('getMetadataFor')
            ->willReturn($metadata)
        ;

        $factory = new CacheClassMetadataFactory($decorated, new ArrayAdapter());

        $this->assertEquals($metadata, $factory->getMetadataFor(Dummy::class));
        // The second call should retrieve the value from the cache
        $this->assertEquals($metadata, $factory->getMetadataFor(Dummy::class));
    }

    public function testHasMetadataFor()
    {
        $decorated = $this->createMock(ClassMetadataFactoryInterface::class);
        $decorated
            ->expects($this->once())
            ->method('hasMetadataFor')
            ->willReturn(true)
        ;

        $factory = new CacheClassMetadataFactory($decorated, new ArrayAdapter());

        $this->assertTrue($factory->hasMetadataFor(Dummy::class));
    }

    public function testInvalidClassThrowsException()
    {
        $decorated = $this->createMock(ClassMetadataFactoryInterface::class);
        $factory = new CacheClassMetadataFactory($decorated, new ArrayAdapter());

        $this->expectException(InvalidArgumentException::class);

        $factory->getMetadataFor('Not\Exist');
    }

    public function testAnonymousClass()
    {
        $anonymousObject = new class {
        };

        $metadata = new ClassMetadata($anonymousObject::class);
        $decorated = $this->createMock(ClassMetadataFactoryInterface::class);
        $decorated
            ->expects($this->once())
            ->method('getMetadataFor')
            ->willReturn($metadata);

        $factory = new CacheClassMetadataFactory($decorated, new ArrayAdapter());
        $this->assertEquals($metadata, $factory->getMetadataFor($anonymousObject));
    }
}
