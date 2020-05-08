<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider\Strategy\MergeStrategy;

use ReflectionProperty;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\MergeStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestClassAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestPropertyAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class MockTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider\Strategy\MergeStrategy
 */
class MockTest extends AbstractTestCase {

    public function testMock() {

        $annotations = [ new TestClassAnnotation() ];
        $mockedAnnotations = [ new TestPropertyAnnotation() ];

        $this->assertSame(

            array_merge( $annotations, $mockedAnnotations ),

            ( new MergeStrategy() )
                ->mock( new ReflectionProperty( TestClass::class, 'property' ), $annotations, $mockedAnnotations )

        );

    }

}
