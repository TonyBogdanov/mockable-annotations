<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider;

use ReflectionProperty;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\OverrideStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestPropertyAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class MockPropertyAnnotationsTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider
 */
class MockPropertyAnnotationsTest extends AbstractTestCase {

    public function testNoFilter() {

        $annotations = [ new TestPropertyAnnotation() ];
        $provider = new PropertyMockProvider( [], new OverrideStrategy() );

        $this->assertSame( [], $provider->mockPropertyAnnotations(

            new ReflectionProperty( TestClass::class, 'property' ),
            $annotations

        ) );

    }

    public function testIneligibleFilter() {

        $annotations = [ new TestPropertyAnnotation() ];
        $provider = new PropertyMockProvider( [], new OverrideStrategy(), new PropertyNameFilter(

            PropertyNameFilter::class,
            'propertyName'

        ) );

        $this->assertSame( $annotations, $provider->mockPropertyAnnotations(

            new ReflectionProperty( TestClass::class, 'property' ),
            $annotations

        ) );

    }

    public function testFilter() {

        $annotations = [ new TestPropertyAnnotation() ];
        $provider = new PropertyMockProvider( [], new OverrideStrategy(), new PropertyNameFilter(

            TestClass::class,
            'property'

        ) );

        $this->assertSame( [], $provider->mockPropertyAnnotations(

            new ReflectionProperty( TestClass::class, 'property' ),
            $annotations

        ) );

    }

}
