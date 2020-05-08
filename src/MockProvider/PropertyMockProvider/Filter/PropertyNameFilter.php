<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter;

use ReflectionProperty;

/**
 * Class PropertyNameFilter
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter
 */
class PropertyNameFilter implements FilterInterface {

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $propertyName;

    /**
     * PropertyNameFilter constructor.
     *
     * @param string $className
     * @param string $propertyName
     */
    public function __construct( string $className, string $propertyName ) {

        $this
            ->setClassName( $className )
            ->setPropertyName( $propertyName );

    }

    /**
     * @param ReflectionProperty $property
     * @param array $annotations
     *
     * @return bool
     */
    public function shouldMock( ReflectionProperty $property, array $annotations ): bool {

        return
            $this->getClassName() === $property->getDeclaringClass()->getName() &&
            $this->getPropertyName() === $property->getName();

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
    public function getPropertyName(): string {

        return $this->propertyName;

    }

    /**
     * @param string $propertyName
     *
     * @return $this
     */
    public function setPropertyName( string $propertyName ): self {

        $this->propertyName = $propertyName;
        return $this;

    }

}
