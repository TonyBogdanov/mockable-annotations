<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter\ClassNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\MergeStrategy as ClassMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\OverrideStrategy as ClassOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter\MethodNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\MergeStrategy as MethodMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\OverrideStrategy as MethodOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\MergeStrategy as PropertyMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\OverrideStrategy as PropertyOverrideStrategy;
use TonyBogdanov\MockableAnnotations\Reader\MockableReader;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\AliasClass;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestClassAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestMethodAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestPropertyAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;
use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;
use ReflectionException;

/**
 * Class ConvenienceTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Reader\MockableReader
 */
class ConvenienceTest extends AbstractTestCase {

    public function provider(): array {

        $classAnnotation = new TestClassAnnotation();
        $classAnnotation->value = 'test';

        $methodAnnotation = new TestMethodAnnotation();
        $methodAnnotation->value = 'test';

        $propertyAnnotation = new TestPropertyAnnotation();
        $propertyAnnotation->value = 'test';

        $aliasClassAnnotation = new TestClassAnnotation();
        $aliasClassAnnotation->value = 'aliased';

        $aliasMethodAnnotation = new TestMethodAnnotation();
        $aliasMethodAnnotation->value = 'aliased';

        $aliasPropertyAnnotation = new TestPropertyAnnotation();
        $aliasPropertyAnnotation->value = 'aliased';

        return [ [

            'override',
            'Class',

            [ TestClass::class, [ $classAnnotation ] ],

            new ClassMockProvider(

                [ $classAnnotation ],
                new ClassOverrideStrategy(),
                new ClassNameFilter( TestClass::class ),
                123

            )

        ], [

            'overrideAlias',
            'Class',

            [ TestClass::class, AliasClass::class ],

            new ClassMockProvider(

                [ $aliasClassAnnotation ],
                new ClassOverrideStrategy(),
                new ClassNameFilter( TestClass::class ),
                123

            )

        ], [

            'merge',
            'Class',

            [ TestClass::class, [ $classAnnotation ] ],

            new ClassMockProvider(

                [ $classAnnotation ],
                new ClassMergeStrategy(),
                new ClassNameFilter( TestClass::class ),
                123

            )

        ], [

            'mergeAlias',
            'Class',

            [ TestClass::class, AliasClass::class ],

            new ClassMockProvider(

                [ $aliasClassAnnotation ],
                new ClassMergeStrategy(),
                new ClassNameFilter( TestClass::class ),
                123

            )

        ], [

            'override',
            'Method',

            [ TestClass::class, 'method', [ $methodAnnotation ] ],

            new MethodMockProvider(

                [ $methodAnnotation ],
                new MethodOverrideStrategy(),
                new MethodNameFilter( TestClass::class, 'method' ),
                123

            )

        ], [

            'overrideAlias',
            'Method',

            [ TestClass::class, 'method', AliasClass::class, 'method' ],

            new MethodMockProvider(

                [ $aliasMethodAnnotation ],
                new MethodOverrideStrategy(),
                new MethodNameFilter( TestClass::class, 'method' ),
                123

            )

        ], [

            'merge',
            'Method',

            [ TestClass::class, 'method', [ $methodAnnotation ] ],

            new MethodMockProvider(

                [ $methodAnnotation ],
                new MethodMergeStrategy(),
                new MethodNameFilter( TestClass::class, 'method' ),
                123

            )

        ], [

            'mergeAlias',
            'Method',

            [ TestClass::class, 'method', AliasClass::class, 'method' ],

            new MethodMockProvider(

                [ $aliasMethodAnnotation ],
                new MethodMergeStrategy(),
                new MethodNameFilter( TestClass::class, 'method' ),
                123

            )

        ], [

            'override',
            'Property',

            [ TestClass::class, 'property', [ $propertyAnnotation ] ],

            new PropertyMockProvider(

                [ $propertyAnnotation ],
                new PropertyOverrideStrategy(),
                new PropertyNameFilter( TestClass::class, 'property' ),
                123

            )

        ], [

            'overrideAlias',
            'Property',

            [ TestClass::class, 'property', AliasClass::class, 'property' ],

            new PropertyMockProvider(

                [ $aliasPropertyAnnotation ],
                new PropertyOverrideStrategy(),
                new PropertyNameFilter( TestClass::class, 'property' ),
                123

            )

        ], [

            'merge',
            'Property',

            [ TestClass::class, 'property', [ $propertyAnnotation ] ],

            new PropertyMockProvider(

                [ $propertyAnnotation ],
                new PropertyMergeStrategy(),
                new PropertyNameFilter( TestClass::class, 'property' ),
                123

            )

        ], [

            'mergeAlias',
            'Property',

            [ TestClass::class, 'property', AliasClass::class, 'property' ],

            new PropertyMockProvider(

                [ $aliasPropertyAnnotation ],
                new PropertyMergeStrategy(),
                new PropertyNameFilter( TestClass::class, 'property' ),
                123

            )

        ] ];

    }

    /**
     * @dataProvider provider
     *
     * @param string $strategy
     * @param string $type
     * @param array $args
     * @param object $expectedProvider
     *
     * @throws AnnotationException
     */
    public function testConvenience(

        string $strategy,
        string $type,
        array $args,
        object $expectedProvider

    ) {

        $reader = new MockableReader( new AnnotationReader() );

        $method = $strategy . $type . 'Annotations';
        $getter = 'get' . $type . 'MockProviders';

        $this->assertSame( $reader, call_user_func_array( [ $reader, $method ], array_merge( $args, [ 123 ] ) ) );
        $this->assertEquals( [ $expectedProvider ], call_user_func( [ $reader, $getter ] ) );

    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function testOverrideAlias() {

        $reader = new MockableReader( new AnnotationReader() );
        $reader->overrideAliasAnnotations( TestClass::class, AliasClass::class );

        $classAnnotations = $reader->getClassAnnotations( new ReflectionClass( TestClass::class ) );
        $this->assertCount( 1, $classAnnotations );
        $this->assertInstanceOf( TestClassAnnotation::class, $classAnnotations[0] );
        $this->assertSame( 'aliased', $classAnnotations[0]->value );

        $methodAnnotations = $reader->getMethodAnnotations( new ReflectionMethod( TestClass::class, 'method' ) );
        $this->assertCount( 1, $methodAnnotations );
        $this->assertInstanceOf( TestMethodAnnotation::class, $methodAnnotations[0] );
        $this->assertSame( 'aliased', $methodAnnotations[0]->value );

        $propAnnotations = $reader->getPropertyAnnotations( new ReflectionProperty( TestClass::class, 'property' ) );
        $this->assertCount( 1, $propAnnotations );
        $this->assertInstanceOf( TestPropertyAnnotation::class, $propAnnotations[0] );
        $this->assertSame( 'aliased', $propAnnotations[0]->value );

    }

    /**
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function testMergeAlias() {

        $reader = new MockableReader( new AnnotationReader() );
        $reader->mergeAliasAnnotations( TestClass::class, AliasClass::class );

        $classAnnotations = $reader->getClassAnnotations( new ReflectionClass( TestClass::class ) );
        $this->assertCount( 2, $classAnnotations );

        $this->assertInstanceOf( TestClassAnnotation::class, $classAnnotations[0] );
        $this->assertInstanceOf( TestClassAnnotation::class, $classAnnotations[1] );

        $this->assertSame( 'declared', $classAnnotations[0]->value );
        $this->assertSame( 'aliased', $classAnnotations[1]->value );

        $methodAnnotations = $reader->getMethodAnnotations( new ReflectionMethod( TestClass::class, 'method' ) );
        $this->assertCount( 2, $methodAnnotations );

        $this->assertInstanceOf( TestMethodAnnotation::class, $methodAnnotations[0] );
        $this->assertInstanceOf( TestMethodAnnotation::class, $methodAnnotations[1] );

        $this->assertSame( 'declared', $methodAnnotations[0]->value );
        $this->assertSame( 'aliased', $methodAnnotations[1]->value );

        $propAnnotations = $reader->getPropertyAnnotations( new ReflectionProperty( TestClass::class, 'property' ) );
        $this->assertCount( 2, $propAnnotations );

        $this->assertInstanceOf( TestPropertyAnnotation::class, $propAnnotations[0] );
        $this->assertInstanceOf( TestPropertyAnnotation::class, $propAnnotations[1] );

        $this->assertSame( 'declared', $propAnnotations[0]->value );
        $this->assertSame( 'aliased', $propAnnotations[1]->value );

    }

}
