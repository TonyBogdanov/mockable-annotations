<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter;

use ReflectionMethod;

/**
 * Class MethodNameFilter
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter
 */
class MethodNameFilter implements FilterInterface {

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $methodName;

    /**
     * MethodNameFilter constructor.
     *
     * @param string $className
     * @param string $methodName
     */
    public function __construct( string $className, string $methodName ) {

        $this
            ->setClassName( $className )
            ->setMethodName( $methodName );

    }

    /**
     * @param ReflectionMethod $method
     * @param array $annotations
     *
     * @return bool
     */
    public function shouldMock( ReflectionMethod $method, array $annotations ): bool {

        return
            $this->getClassName() === $method->getDeclaringClass()->getName() &&
            $this->getMethodName() === $method->getName();

    }

    /**
     * @return string
     */
    public function getClassName(): string {

        return $this->className;

    }

    /**
     * @param string $className
     *
     * @return $this
     */
    public function setClassName( string $className ): self {

        $this->className = $className;
        return $this;

    }

    /**
     * @return string
     */
    public function getMethodName(): string {

        return $this->methodName;

    }

    /**
     * @param string $methodName
     *
     * @return $this
     */
    public function setMethodName( string $methodName ): self {

        $this->methodName = $methodName;
        return $this;

    }

}
