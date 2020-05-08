<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider\Filter\ClassNameFilter;

use ReflectionClass;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter\ClassNameFilter;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestChildClass;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class ShouldMockTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider\Filter\ClassNameFilter
 */
class ShouldMockTest extends AbstractTestCase {

    public function testLoose() {

        $filter = new ClassNameFilter( TestClass::class );

        $this->assertTrue( $filter->shouldMock( new ReflectionClass( TestClass::class ), [] ) );
        $this->assertTrue( $filter->shouldMock( new ReflectionClass( TestChildClass::class ), [] ) );

        $this->assertFalse( $filter->shouldMock( new ReflectionClass( ClassNameFilter::class ), [] ) );

    }

    public function testStrict() {

        $filter = new ClassNameFilter( TestClass::class, true );

        $this->assertTrue( $filter->shouldMock( new ReflectionClass( TestClass::class ), [] ) );
        $this->assertFalse( $filter->shouldMock( new ReflectionClass( TestChildClass::class ), [] ) );

        $this->assertFalse( $filter->shouldMock( new ReflectionClass( ClassNameFilter::class ), [] ) );

    }

}
