<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider;

use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\MergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\OverrideStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestPropertyAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class CrudTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider
 */
class CrudTest extends AbstractTestCase {

    public function testAnnotations() {

        $this
            ->crud( new PropertyMockProvider( $value = [], new OverrideStrategy() ), 'annotations' )
            ->get( $value )
            ->set( [ new TestPropertyAnnotation() ] );

    }

    public function testStrategy() {

        $this
            ->crud( new PropertyMockProvider( [], $value = new OverrideStrategy() ), 'strategy' )
            ->get( $value )
            ->set( new MergeStrategy() );

    }

    public function testFilter() {

        $this
            ->crud( new PropertyMockProvider( [], new OverrideStrategy() ), 'filter' )
            ->has( false )
            ->get( null )
            ->set( new PropertyNameFilter( TestClass::class, 'method' ) )
            ->has( true );

    }

    public function testPriority() {

        $this
            ->crud( new PropertyMockProvider( [], new OverrideStrategy() ), 'priority' )
            ->get( 0 )
            ->set( 123 );

    }

}
