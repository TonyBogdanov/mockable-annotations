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
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\MergeStrategy as MethodMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\MergeStrategy as PropertyMergeStrategy;
use TonyBogdanov\MockableAnnotations\Reader\MockableReader;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;

/**
 * Class PriorityTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader
 */
class PriorityTest extends AbstractTestCase {

    public function testClass() {

        $reader = new MockableReader( new AnnotationReader() );

        $reader->addClassMockProvider( $provider1 = new ClassMockProvider( [], new ClassMergeStrategy(), null, 2 ) );
        $this->assertSame( [ $provider1 ], $reader->getClassMockProviders() );

        $reader->addClassMockProvider( $provider2 = new ClassMockProvider( [], new ClassMergeStrategy(), null, 1 ) );
        $this->assertSame( [ $provider2, $provider1 ], $reader->getClassMockProviders() );

    }

    public function testMethod() {

        $reader = new MockableReader( new AnnotationReader() );

        $reader->addMethodMockProvider( $provider1 = new MethodMockProvider( [], new MethodMergeStrategy(), null, 2 ) );
        $this->assertSame( [ $provider1 ], $reader->getMethodMockProviders() );

        $reader->addMethodMockProvider( $provider2 = new MethodMockProvider( [], new MethodMergeStrategy(), null, 1 ) );
        $this->assertSame( [ $provider2, $provider1 ], $reader->getMethodMockProviders() );

    }

    public function testProperty() {

        $reader = new MockableReader( new AnnotationReader() );

        $reader->addPropertyMockProvider( $provider1 = new PropertyMockProvider( [], new PropertyMergeStrategy(),
            null, 2 ) );

        $this->assertSame( [ $provider1 ], $reader->getPropertyMockProviders() );

        $reader->addPropertyMockProvider( $provider2 = new PropertyMockProvider( [], new PropertyMergeStrategy(),
            null, 1 ) );

        $this->assertSame( [ $provider2, $provider1 ], $reader->getPropertyMockProviders() );

    }

}
