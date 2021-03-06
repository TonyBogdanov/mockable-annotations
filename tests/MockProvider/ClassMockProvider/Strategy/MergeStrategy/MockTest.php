<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider\Strategy\MergeStrategy;

use ReflectionClass;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\MergeStrategy;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestClassAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestMethodAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class MockTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider\Strategy\MergeStrategy
 */
class MockTest extends AbstractTestCase {

    public function testMock() {

        $annotations = [ new TestClassAnnotation() ];
        $mockedAnnotations = [ new TestMethodAnnotation() ];

        $this->assertSame(

            array_merge( $annotations, $mockedAnnotations ),
            
            ( new MergeStrategy() )
                ->mock( new ReflectionClass( TestClass::class ), $annotations, $mockedAnnotations )

        );

    }

}
