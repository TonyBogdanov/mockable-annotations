<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Reader;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProviderInterface;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProviderInterface;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProviderInterface;
use TonyBogdanov\MockableAnnotations\MockProvider\SortableMockProviderInterface;

/**
 * Class MockableReader
 *
 * @package TonyBogdanov\MockableAnnotations\Reader
 */
class MockableReader implements Reader {

    /**
     * @var Reader
     */
    protected $delegate;

    /**
     * @var ClassMockProviderInterface[]
     */
    protected $classMockProviders = [];

    /**
     * @var MethodMockProviderInterface[]
     */
    protected $methodMockProviders = [];

    /**
     * @var PropertyMockProviderInterface[]
     */
    protected $propertyMockProviders = [];

    /**
     * MockableReader constructor.
     *
     * @param Reader $delegate
     */
    public function __construct( Reader $delegate ) {
        
        $this->setDelegate( $delegate );
        
    }

    /**
     * @param ReflectionClass $class
     *
     * @return array
     */
    public function getClassAnnotations( ReflectionClass $class ) {

        $annotations = $this->getDelegate()->getClassAnnotations( $class );

        foreach ( $this->getClassMockProviders() as $provider ) {

            $annotations = $provider->mockClassAnnotations( $class, $annotations );

        }
        
        return $annotations;
        
    }

    /**
     * @param ReflectionClass $class
     * @param string $annotationName
     *
     * @return mixed|object|null
     */
    public function getClassAnnotation( ReflectionClass $class, $annotationName ) {

        foreach ( $this->getClassAnnotations( $class ) as $annotation ) {

            if ( $annotation instanceof $annotationName ) {

                return $annotation;

            }

        }

        return null;

    }

    /**
     * @param ReflectionMethod $method
     *
     * @return array
     */
    public function getMethodAnnotations( ReflectionMethod $method ) {

        $annotations = $this->getDelegate()->getMethodAnnotations( $method );

        foreach ( $this->getMethodMockProviders() as $provider ) {

            $annotations = $provider->mockMethodAnnotations( $method, $annotations );

        }

        return $annotations;

    }

    /**
     * @param ReflectionMethod $method
     * @param string $annotationName
     *
     * @return mixed|object|null
     */
    public function getMethodAnnotation( ReflectionMethod $method, $annotationName ) {

        foreach ( $this->getMethodAnnotations( $method ) as $annotation ) {

            if ( $annotation instanceof $annotationName ) {

                return $annotation;

            }

        }

        return null;

    }

    /**
     * @param ReflectionProperty $property
     *
     * @return array
     */
    public function getPropertyAnnotations( ReflectionProperty $property ) {

        $annotations = $this->getDelegate()->getPropertyAnnotations( $property );

        foreach ( $this->getPropertyMockProviders() as $provider ) {

            $annotations = $provider->mockPropertyAnnotations( $property, $annotations );

        }

        return $annotations;

    }

    /**
     * @param ReflectionProperty $property
     * @param string $annotationName
     *
     * @return mixed|object|null
     */
    public function getPropertyAnnotation( ReflectionProperty $property, $annotationName ) {

        foreach ( $this->getPropertyAnnotations( $property ) as $annotation ) {

            if ( $annotation instanceof $annotationName ) {

                return $annotation;

            }

        }

        return null;

    }

    /**
     * @return Reader
     */
    public function getDelegate() {

        return $this->delegate;
        
    }

    /**
     * @param Reader $delegate
     *
     * @return $this
     */
    public function setDelegate( $delegate ): self {

        $this->delegate = $delegate;
        return $this;

    }

    /**
     * @return $this
     */
    public function clearClassMockProviders(): self {

        $this->classMockProviders = [];
        return $this;

    }

    /**
     * @param ClassMockProviderInterface $provider
     *
     * @return $this
     */
    public function addClassMockProvider( ClassMockProviderInterface $provider ): self {

        $this->classMockProviders[] = $provider;

        usort(

            $this->classMockProviders,
            function ( ClassMockProviderInterface $left, ClassMockProviderInterface $right ) {

                return
                    ( $left instanceof SortableMockProviderInterface ? $left->getPriority() : PHP_INT_MIN ) <=>
                    ( $right instanceof SortableMockProviderInterface ? $right->getPriority() : PHP_INT_MIN );

            }

        );

        return $this;

    }

    /**
     * @return ClassMockProviderInterface[]
     */
    public function getClassMockProviders(): array {

        return $this->classMockProviders;

    }

    /**
     * @param ClassMockProviderInterface[] $classMockProviders
     *
     * @return $this
     */
    public function setClassMockProviders( array $classMockProviders ): self {

        $this->clearClassMockProviders();

        foreach ( $classMockProviders as $provider ) {

            $this->addClassMockProvider( $provider );

        }

        return $this;

    }

    /**
     * @return $this
     */
    public function clearMethodMockProviders(): self {

        $this->methodMockProviders = [];
        return $this;

    }

    /**
     * @param MethodMockProviderInterface $provider
     *
     * @return $this
     */
    public function addMethodMockProvider( MethodMockProviderInterface $provider ): self {

        $this->methodMockProviders[] = $provider;

        usort(

            $this->methodMockProviders,
            function ( MethodMockProviderInterface $left, MethodMockProviderInterface $right ) {

                return
                    ( $left instanceof SortableMockProviderInterface ? $left->getPriority() : PHP_INT_MIN ) <=>
                    ( $right instanceof SortableMockProviderInterface ? $right->getPriority() : PHP_INT_MIN );

            }

        );

        return $this;

    }

    /**
     * @return MethodMockProviderInterface[]
     */
    public function getMethodMockProviders(): array {

        return $this->methodMockProviders;

    }

    /**
     * @param MethodMockProviderInterface[] $methodMockProviders
     *
     * @return $this
     */
    public function setMethodMockProviders( array $methodMockProviders ): self {

        $this->clearMethodMockProviders();

        foreach ( $methodMockProviders as $provider ) {

            $this->addMethodMockProvider( $provider );

        }

        return $this;

    }

    /**
     * @return $this
     */
    public function clearPropertyMockProviders(): self {

        $this->propertyMockProviders = [];
        return $this;

    }

    /**
     * @param PropertyMockProviderInterface $provider
     *
     * @return $this
     */
    public function addPropertyMockProvider( PropertyMockProviderInterface $provider ): self {

        $this->propertyMockProviders[] = $provider;

        usort(

            $this->propertyMockProviders,
            function ( PropertyMockProviderInterface $left, PropertyMockProviderInterface $right ) {

                return
                    ( $left instanceof SortableMockProviderInterface ? $left->getPriority() : PHP_INT_MIN ) <=>
                    ( $right instanceof SortableMockProviderInterface ? $right->getPriority() : PHP_INT_MIN );

            }

        );

        return $this;

    }

    /**
     * @return PropertyMockProviderInterface[]
     */
    public function getPropertyMockProviders(): array {

        return $this->propertyMockProviders;

    }

    /**
     * @param PropertyMockProviderInterface[] $propertyMockProviders
     *
     * @return $this
     */
    public function setPropertyMockProviders( array $propertyMockProviders ): self {

        $this->clearPropertyMockProviders();

        foreach ( $propertyMockProviders as $provider ) {

            $this->addPropertyMockProvider( $provider );

        }

        return $this;

    }

}
