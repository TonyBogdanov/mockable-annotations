<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider;

use ReflectionClass;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter\ClassNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\OverrideStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestClassAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class MockClassAnnotationsTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider
 */
class MockClassAnnotationsTest extends AbstractTestCase {

    public function testNoFilter() {

        $annotations = [ new TestClassAnnotation() ];
        $provider = new ClassMockProvider( [], new OverrideStrategy() );

        $this->assertSame( [], $provider->mockClassAnnotations(

            new ReflectionClass( TestClass::class ),
            $annotations

        ) );

    }

    public function testIneligibleFilter() {

        $annotations = [ new TestClassAnnotation() ];
        $provider = new ClassMockProvider( [], new OverrideStrategy(), new ClassNameFilter( ClassNameFilter::class ) );

        $this->assertSame( $annotations, $provider->mockClassAnnotations(

            new ReflectionClass( TestClass::class ),
            $annotations

        ) );

    }

    public function testFilter() {

        $annotations = [ new TestClassAnnotation() ];
        $provider = new ClassMockProvider( [], new OverrideStrategy(), new ClassNameFilter( TestClass::class ) );

        $this->assertSame( [], $provider->mockClassAnnotations(

            new ReflectionClass( TestClass::class ),
            $annotations

        ) );

    }

}
