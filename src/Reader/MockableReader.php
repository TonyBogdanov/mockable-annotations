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
use ReflectionException;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter\ClassNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\MergeStrategy as ClassMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy\OverrideStrategy as ClassOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProviderInterface;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter\MethodNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\MergeStrategy as MethodMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy\OverrideStrategy as MethodOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProviderInterface;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\MergeStrategy as PropertyMergeStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\OverrideStrategy as PropertyOverrideStrategy;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProviderInterface;
use TonyBogdanov\MockableAnnotations\MockProvider\SortableMockProviderInterface;
use SplStack;
use RuntimeException;
use TonyBogdanov\MockableAnnotations\Reader\Exceptions\StateException;

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
     * @var SplStack
     */
    protected $stateStack;

    /**
     * MockableReader constructor.
     *
     * @param Reader $delegate
     */
    public function __construct( Reader $delegate ) {
        
        $this
            ->setDelegate( $delegate )
            ->setStateStack( new SplStack() );
        
    }

    /**
     * @return $this
     */
    public function pushState(): self {

        $this->getStateStack()->push( [

            $this->classMockProviders,
            $this->methodMockProviders,
            $this->propertyMockProviders,

        ] );

        return $this;

    }

    /**
     * @return $this
     * @throws StateException
     */
    public function popState(): self {

        try {

            $this->getStateStack()->pop();

        } catch ( RuntimeException $e ) {

            throw new StateException( StateException::CANNOT_POP_EMPTY, $e );

        }

        return $this;

    }

    /**
     * @return $this
     * @throws StateException
     */
    public function restoreState(): self {

        try {

            [

                $this->classMockProviders,
                $this->methodMockProviders,
                $this->propertyMockProviders,

            ] = $this->getStateStack()->pop();

        } catch ( RuntimeException $e ) {

            throw new StateException( StateException::CANNOT_POP_EMPTY, $e );

        }

        return $this;

    }

    /**
     * @param string $className
     * @param array $annotations
     * @param int $priority
     *
     * @return $this
     */
    public function overrideClassAnnotations( string $className, array $annotations, int $priority = 0 ): self {

        return $this->addClassMockProvider( new ClassMockProvider(

            $annotations,
            new ClassOverrideStrategy(),
            new ClassNameFilter( $className ),
            $priority

        ) );

    }

    /**
     * @param string $className
     * @param string $aliasClassName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function overrideAliasClassAnnotations( string $className, string $aliasClassName, int $priority = 0): self {

        return $this->overrideClassAnnotations(

            $className,
            $this->getClassAnnotations( new ReflectionClass( $aliasClassName ) ),
            $priority

        );

    }

    /**
     * @param string $className
     * @param array $annotations
     * @param int $priority
     *
     * @return $this
     */
    public function mergeClassAnnotations( string $className, array $annotations, int $priority = 0 ): self {

        return $this->addClassMockProvider( new ClassMockProvider(

            $annotations,
            new ClassMergeStrategy(),
            new ClassNameFilter( $className ),
            $priority

        ) );

    }

    /**
     * @param string $className
     * @param string $aliasClassName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function mergeAliasClassAnnotations( string $className, string $aliasClassName, int $priority = 0 ): self {

        return $this->mergeClassAnnotations(

            $className,
            $this->getClassAnnotations( new ReflectionClass( $aliasClassName ) ),
            $priority

        );

    }

    /**
     * @param string $className
     * @param string $methodName
     * @param array $annotations
     * @param int $priority
     *
     * @return $this
     */
    public function overrideMethodAnnotations(

        string $className,
        string $methodName,
        array $annotations,
        int $priority = 0

    ): self {

        return $this->addMethodMockProvider( new MethodMockProvider(

            $annotations,
            new MethodOverrideStrategy(),
            new MethodNameFilter( $className, $methodName ),
            $priority

        ) );

    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $aliasClassName
     * @param string $aliasMethodName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function overrideAliasMethodAnnotations(

        string $className,
        string $methodName,
        string $aliasClassName,
        string $aliasMethodName,
        int $priority = 0

    ): self {

        return $this->overrideMethodAnnotations(

            $className,
            $methodName,
            $this->getMethodAnnotations( new ReflectionMethod( $aliasClassName, $aliasMethodName ) ),
            $priority

        );

    }

    /**
     * @param string $className
     * @param string $methodName
     * @param array $annotations
     * @param int $priority
     *
     * @return $this
     */
    public function mergeMethodAnnotations(

        string $className,
        string $methodName,
        array $annotations,
        int $priority = 0

    ): self {

        return $this->addMethodMockProvider( new MethodMockProvider(

            $annotations,
            new MethodMergeStrategy(),
            new MethodNameFilter( $className, $methodName ),
            $priority

        ) );

    }

    /**
     * @param string $className
     * @param string $methodName
     * @param string $aliasClassName
     * @param string $aliasMethodName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function mergeAliasMethodAnnotations(

        string $className,
        string $methodName,
        string $aliasClassName,
        string $aliasMethodName,
        int $priority = 0

    ): self {

        return $this->mergeMethodAnnotations(

            $className,
            $methodName,
            $this->getMethodAnnotations( new ReflectionMethod( $aliasClassName, $aliasMethodName ) ),
            $priority

        );

    }

    /**
     * @param string $className
     * @param string $propertyName
     * @param array $annotations
     * @param int $priority
     *
     * @return $this
     */
    public function overridePropertyAnnotations(

        string $className,
        string $propertyName,
        array $annotations,
        int $priority = 0

    ): self {

        return $this->addPropertyMockProvider( new PropertyMockProvider(

            $annotations,
            new PropertyOverrideStrategy(),
            new PropertyNameFilter( $className, $propertyName ),
            $priority

        ) );

    }

    /**
     * @param string $className
     * @param string $propertyName
     * @param string $aliasClassName
     * @param string $aliasPropertyName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function overrideAliasPropertyAnnotations(

        string $className,
        string $propertyName,
        string $aliasClassName,
        string $aliasPropertyName,
        int $priority = 0

    ): self {

        return $this->overridePropertyAnnotations(

            $className,
            $propertyName,
            $this->getPropertyAnnotations( new ReflectionProperty( $aliasClassName, $aliasPropertyName ) ),
            $priority

        );

    }

    /**
     * @param string $className
     * @param string $propertyName
     * @param array $annotations
     * @param int $priority
     *
     * @return $this
     */
    public function mergePropertyAnnotations(

        string $className,
        string $propertyName,
        array $annotations,
        int $priority = 0

    ): self {

        return $this->addPropertyMockProvider( new PropertyMockProvider(

            $annotations,
            new PropertyMergeStrategy(),
            new PropertyNameFilter( $className, $propertyName ),
            $priority

        ) );

    }

    /**
     * @param string $className
     * @param string $propertyName
     * @param string $aliasClassName
     * @param string $aliasPropertyName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function mergeAliasPropertyAnnotations(

        string $className,
        string $propertyName,
        string $aliasClassName,
        string $aliasPropertyName,
        int $priority = 0

    ): self {

        return $this->mergePropertyAnnotations(

            $className,
            $propertyName,
            $this->getPropertyAnnotations( new ReflectionProperty( $aliasClassName, $aliasPropertyName ) ),
            $priority

        );

    }

    /**
     * @param string $className
     * @param string $aliasClassName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function overrideAliasAnnotations( string $className, string $aliasClassName, int $priority = 0 ): self {

        $this->overrideAliasClassAnnotations( $className, $aliasClassName, $priority );

        $reflection = new ReflectionClass( $className );
        $aliasReflection = new ReflectionClass( $aliasClassName );

        foreach ( $reflection->getMethods() as $method ) {

            if ( ! $aliasReflection->hasMethod( $method->getName() ) ) {

                continue;

            }

            $this->overrideAliasMethodAnnotations(

                $className,
                $method->getName(),
                $aliasClassName,
                $method->getName(),
                $priority

            );

        }

        foreach ( $reflection->getProperties() as $property ) {

            if ( ! $aliasReflection->hasProperty( $property->getName() ) ) {

                continue;

            }

            $this->overrideAliasPropertyAnnotations(

                $className,
                $property->getName(),
                $aliasClassName,
                $property->getName(),
                $priority

            );

        }

        return $this;

    }

    /**
     * @param string $className
     * @param string $aliasClassName
     * @param int $priority
     *
     * @return $this
     * @throws ReflectionException
     */
    public function mergeAliasAnnotations( string $className, string $aliasClassName, int $priority = 0 ): self {

        $this->mergeAliasClassAnnotations( $className, $aliasClassName, $priority );

        $reflection = new ReflectionClass( $className );
        $aliasReflection = new ReflectionClass( $aliasClassName );

        foreach ( $reflection->getMethods() as $method ) {

            if ( ! $aliasReflection->hasMethod( $method->getName() ) ) {

                continue;

            }

            $this->mergeAliasMethodAnnotations(

                $className,
                $method->getName(),
                $aliasClassName,
                $method->getName(),
                $priority

            );

        }

        foreach ( $reflection->getProperties() as $property ) {

            if ( ! $aliasReflection->hasProperty( $property->getName() ) ) {

                continue;

            }

            $this->mergeAliasPropertyAnnotations(

                $className,
                $property->getName(),
                $aliasClassName,
                $property->getName(),
                $priority

            );

        }

        return $this;

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

    /**
     * @return SplStack
     */
    public function getStateStack(): SplStack {

        return $this->stateStack;

    }

    /**
     * @param SplStack $stateStack
     *
     * @return $this
     */
    public function setStateStack( SplStack $stateStack ): self {

        $this->stateStack = $stateStack;
        return $this;

    }

}
