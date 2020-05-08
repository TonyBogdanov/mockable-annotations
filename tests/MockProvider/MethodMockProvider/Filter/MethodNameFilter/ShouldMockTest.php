<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider\Filter\MethodNameFilter;

use ReflectionMethod;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter\MethodNameFilter;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestChildClass;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class ShouldMockTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider\Filter\MethodNameFilter
 */
class ShouldMockTest extends AbstractTestCase {

    public function testShouldMock() {

        $filter = new MethodNameFilter( TestClass::class, 'method' );

        $this->assertTrue( $filter->shouldMock( new ReflectionMethod( TestClass::class, 'method' ), [] ) );
        $this->assertTrue( $filter->shouldMock( new ReflectionMethod( TestChildClass::class, 'method' ), [] ) );

        $this->assertFalse( $filter->shouldMock( new ReflectionMethod( TestChildClass::class, 'anotherMethod' ), [] ) );
        $this->assertFalse( $filter->shouldMock( new ReflectionMethod( MethodNameFilter::class, 'shouldMock' ), [] ) );

    }

}
