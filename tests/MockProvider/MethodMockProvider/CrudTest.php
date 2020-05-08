<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider;

use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter\MethodNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\MergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\OverrideStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestMethodAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class CrudTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider
 */
class CrudTest extends AbstractTestCase {

    public function testAnnotations() {

        $this
            ->crud( new MethodMockProvider( $value = [], new OverrideStrategy() ), 'annotations' )
            ->get( $value )
            ->set( [ new TestMethodAnnotation() ] );

    }

    public function testStrategy() {

        $this
            ->crud( new MethodMockProvider( [], $value = new OverrideStrategy() ), 'strategy' )
            ->get( $value )
            ->set( new MergeStrategy() );

    }

    public function testFilter() {

        $this
            ->crud( new MethodMockProvider( [], new OverrideStrategy() ), 'filter' )
            ->has( false )
            ->get( null )
            ->set( new MethodNameFilter( TestClass::class, 'method' ) )
            ->has( true );

    }

    public function testPriority() {

        $this
            ->crud( new MethodMockProvider( [], new OverrideStrategy() ), 'priority' )
            ->get( 0 )
            ->set( 123 );

    }

}
