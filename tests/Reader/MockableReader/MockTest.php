<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\OverrideStrategy as ClassOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\OverrideStrategy as MethodOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\OverrideStrategy as PropertyOverrideStrategy;
use TonyBogdanov\MockableAnnotations\Reader\MockableReader;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestClassAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestMethodAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestPropertyAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class MockTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader
 */
class MockTest extends AbstractTestCase {

    public function testClassEmpty() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionClass( TestClass::class );

        $this->assertNull( $reader->getClassAnnotation( $reflection, TestMethodAnnotation::class ) );

    }

    public function testMethodEmpty() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionMethod( TestClass::class, 'method' );

        $this->assertNull( $reader->getMethodAnnotation( $reflection, TestClassAnnotation::class ) );

    }

    public function testPropertyEmpty() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionProperty( TestClass::class, 'property' );

        $this->assertNull( $reader->getPropertyAnnotation( $reflection, TestClassAnnotation::class ) );

    }

    public function testClassNoMock() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionClass( TestClass::class );

        /** @var TestClassAnnotation $annotation */
        $annotation = $reader->getClassAnnotation( $reflection, TestClassAnnotation::class );

        $this->assertInstanceOf( TestClassAnnotation::class, $annotation );
        $this->assertSame( 'declared', $annotation->value );

    }

    public function testMethodNoMock() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionMethod( TestClass::class, 'method' );

        /** @var TestMethodAnnotation $annotation */
        $annotation = $reader->getMethodAnnotation( $reflection, TestMethodAnnotation::class );

        $this->assertInstanceOf( TestMethodAnnotation::class, $annotation );
        $this->assertSame( 'declared', $annotation->value );

    }

    public function testPropertyNoMock() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionProperty( TestClass::class, 'property' );

        /** @var TestPropertyAnnotation $annotation */
        $annotation = $reader->getPropertyAnnotation( $reflection, TestPropertyAnnotation::class );

        $this->assertInstanceOf( TestPropertyAnnotation::class, $annotation );
        $this->assertSame( 'declared', $annotation->value );

    }

    public function testClassMock() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionClass( TestClass::class );

        $mockedAnnotation = new TestClassAnnotation();
        $mockedAnnotation->value = 'mocked';

        $reader->addClassMockProvider( new ClassMockProvider( [ $mockedAnnotation ], new ClassOverrideStrategy() ) );

        /** @var TestClassAnnotation $annotation */
        $annotation = $reader->getClassAnnotation( $reflection, TestClassAnnotation::class );

        $this->assertInstanceOf( TestClassAnnotation::class, $annotation );
        $this->assertSame( 'mocked', $annotation->value );

    }

    public function testMethodMock() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionMethod( TestClass::class, 'method' );

        $mockedAnnotation = new TestMethodAnnotation();
        $mockedAnnotation->value = 'mocked';

        $reader->addMethodMockProvider( new MethodMockProvider( [ $mockedAnnotation ], new MethodOverrideStrategy() ) );

        /** @var TestMethodAnnotation $annotation */
        $annotation = $reader->getMethodAnnotation( $reflection, TestMethodAnnotation::class );

        $this->assertInstanceOf( TestMethodAnnotation::class, $annotation );
        $this->assertSame( 'mocked', $annotation->value );

    }

    public function testPropertyMock() {

        $reader = new MockableReader( new AnnotationReader() );
        $reflection = new ReflectionProperty( TestClass::class, 'property' );

        $mockedAnnotation = new TestPropertyAnnotation();
        $mockedAnnotation->value = 'mocked';

        $reader->addPropertyMockProvider( new PropertyMockProvider(

            [ $mockedAnnotation ],
            new PropertyOverrideStrategy()

        ) );

        /** @var TestPropertyAnnotation $annotation */
        $annotation = $reader->getPropertyAnnotation( $reflection, TestPropertyAnnotation::class );

        $this->assertInstanceOf( TestPropertyAnnotation::class, $annotation );
        $this->assertSame( 'mocked', $annotation->value );

    }

}
