<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider;

use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter\ClassNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\MergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\OverrideStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestClassAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class CrudTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider
 */
class CrudTest extends AbstractTestCase {

    public function testAnnotations() {

        $this
            ->crud( new ClassMockProvider( $value = [], new OverrideStrategy() ), 'annotations' )
            ->get( $value )
            ->set( [ new TestClassAnnotation() ] );

    }

    public function testStrategy() {

        $this
            ->crud( new ClassMockProvider( [], $value = new OverrideStrategy() ), 'strategy' )
            ->get( $value )
            ->set( new MergeStrategy() );

    }

    public function testFilter() {

        $this
            ->crud( new ClassMockProvider( [], new OverrideStrategy() ), 'filter' )
            ->has( false )
            ->get( null )
            ->set( new ClassNameFilter( TestClass::class ) )
            ->has( true );

    }

    public function testPriority() {

        $this
            ->crud( new ClassMockProvider( [], new OverrideStrategy() ), 'priority' )
            ->get( 0 )
            ->set( 123 );

    }

}
