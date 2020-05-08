<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;

use ReflectionProperty;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestChildClass;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class ShouldMockTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter
 */
class ShouldMockTest extends AbstractTestCase {

    public function testShouldMock() {

        $filter = new PropertyNameFilter( TestClass::class, 'property' );

        $this->assertTrue( $filter->shouldMock( new ReflectionProperty( TestClass::class, 'property' ), [] ) );
        $this->assertTrue( $filter->shouldMock( new ReflectionProperty( TestChildClass::class, 'property' ), [] ) );

        $this->assertFalse( $filter->shouldMock( new ReflectionProperty( TestChildClass::class, 'anotherProperty' ),
            [] ) );

        $this->assertFalse( $filter->shouldMock( new ReflectionProperty( PropertyNameFilter::class, 'className' ),
            [] ) );

    }

}
