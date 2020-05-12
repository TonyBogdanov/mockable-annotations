<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionException;
use TonyBogdanov\MockableAnnotations\Reader\Exceptions\StateException;
use TonyBogdanov\MockableAnnotations\Reader\MockableReader;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\AliasClass;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class StateTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader
 */
class StateTest extends AbstractTestCase {

    /**
     * @throws StateException
     * @throws AnnotationException
     */
    public function testStatelessPopFails() {

        $reader = new MockableReader( new AnnotationReader() );

        $this->expectException( StateException::class );
        $this->expectExceptionCode( StateException::CANNOT_POP_EMPTY );

        $reader->popState();

    }

    /**
     * @throws StateException
     * @throws AnnotationException
     */
    public function testStatelessRestoreFails() {

        $reader = new MockableReader( new AnnotationReader() );

        $this->expectException( StateException::class );
        $this->expectExceptionCode( StateException::CANNOT_POP_EMPTY );

        $reader->restoreState();

    }

    /**
     * @throws AnnotationException
     * @throws StateException
     * @throws ReflectionException
     */
    public function testPushPop() {

        $reader = new MockableReader( new AnnotationReader() );
        $old = [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ];

        $this->assertSame( $reader, $reader->pushState() );
        $this->assertSame( $old, [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ] );

        $reader->overrideAliasAnnotations( TestClass::class, AliasClass::class );
        $this->assertNotSame( $old, $new = [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ] );

        $this->assertSame( $reader, $reader->popState() );
        $this->assertSame( $new, [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ] );

    }

    /**
     * @throws AnnotationException
     * @throws StateException
     * @throws ReflectionException
     */
    public function testPushRestore() {

        $reader = new MockableReader( new AnnotationReader() );
        $old = [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ];

        $this->assertSame( $reader, $reader->pushState() );
        $this->assertSame( $old, [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ] );

        $reader->overrideAliasAnnotations( TestClass::class, AliasClass::class );
        $this->assertNotSame( $old, $new = [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ] );

        $this->assertSame( $reader, $reader->restoreState() );
        $this->assertSame( $old, [

            $reader->getClassMockProviders(),
            $reader->getMethodMockProviders(),
            $reader->getPropertyMockProviders(),

        ] );

    }

}
