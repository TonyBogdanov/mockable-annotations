<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider;

use ReflectionMethod;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter\MethodNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\OverrideStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestMethodAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class MockMethodAnnotationsTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider
 */
class MockMethodAnnotationsTest extends AbstractTestCase {

    public function testNoFilter() {

        $annotations = [ new TestMethodAnnotation() ];
        $provider = new MethodMockProvider( [], new OverrideStrategy() );

        $this->assertSame( [], $provider->mockMethodAnnotations(

            new ReflectionMethod( TestClass::class, 'method' ),
            $annotations

        ) );

    }

    public function testIneligibleFilter() {

        $annotations = [ new TestMethodAnnotation() ];
        $provider = new MethodMockProvider( [], new OverrideStrategy(), new MethodNameFilter(

            MethodNameFilter::class,
            'shouldMock'

        ) );

        $this->assertSame( $annotations, $provider->mockMethodAnnotations(

            new ReflectionMethod( TestClass::class, 'method' ),
            $annotations

        ) );

    }

    public function testFilter() {

        $annotations = [ new TestMethodAnnotation() ];
        $provider = new MethodMockProvider( [], new OverrideStrategy(), new MethodNameFilter(

            TestClass::class,
            'method'

        ) );

        $this->assertSame( [], $provider->mockMethodAnnotations(

            new ReflectionMethod( TestClass::class, 'method' ),
            $annotations

        ) );

    }

}
