<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader;

use Doctrine\Common\Annotations\AnnotationReader;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\MergeStrategy as ClassMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\OverrideStrategy as ClassOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\MergeStrategy as MethodMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\OverrideStrategy as MethodOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\MergeStrategy as PropertyMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\OverrideStrategy as PropertyOverrideStrategy;
use TonyBogdanov\MockableAnnotations\Reader\MockableReader;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;

/**
 * Class CrudTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader
 */
class CrudTest extends AbstractTestCase {

    public function testDelegate() {

        $this
            ->crud( new MockableReader( $reader = new AnnotationReader() ), 'delegate' )
            ->get( $reader )
            ->set( new AnnotationReader() );

    }

    public function testClassMockProviders() {

        $this
            ->crud( new MockableReader( new AnnotationReader() ), 'classMockProviders' )
            ->get( [] )
            ->set( [ new ClassMockProvider( [], new ClassOverrideStrategy() ) ] )
            ->add( new ClassMockProvider( [], new ClassMergeStrategy() ) )
            ->clear( [] );

    }

    public function testMethodMockProviders() {

        $this
            ->crud( new MockableReader( new AnnotationReader() ), 'methodMockProviders' )
            ->get( [] )
            ->set( [ new MethodMockProvider( [], new MethodOverrideStrategy() ) ] )
            ->add( new MethodMockProvider( [], new MethodMergeStrategy() ) )
            ->clear( [] );

    }

    public function testPropertyMockProviders() {

        $this
            ->crud( new MockableReader( new AnnotationReader() ), 'propertyMockProviders' )
            ->get( [] )
            ->set( [ new PropertyMockProvider( [], new PropertyOverrideStrategy() ) ] )
            ->add( new PropertyMockProvider( [], new PropertyMergeStrategy() ) )
            ->clear( [] );

    }

}
