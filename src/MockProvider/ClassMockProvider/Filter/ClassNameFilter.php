<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter;

use ReflectionClass;

/**
 * Class ClassNameFilter
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter
 */
class ClassNameFilter implements FilterInterface {

    /**
     * @var string
     */
    protected $className;

    /**
     * @var bool
     */
    protected $strict;

    /**
     * ClassNameFilter constructor.
     *
     * @param string $className
     * @param bool $strict
     */
    public function __construct( string $className, bool $strict = false ) {

        $this
            ->setClassName( $className )
            ->setStrict( $strict );

    }

    /**
     * @param ReflectionClass $class
     * @param array $annotations
     *
     * @return bool
     */
    public function shouldMock( ReflectionClass $class, array $annotations ): bool {

        return $this->isStrict() ?
            $this->getClassName() === $class->getName() :
            is_a( $class->getName(), $this->getClassName(), true );

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
     * @return bool
     */
    public function isStrict(): bool {

        return $this->strict;

    }

    /**
     * @param bool $strict
     *
     * @return $this
     */
    public function setStrict( bool $strict ): self {

        $this->strict = $strict;
        return $this;

    }

}
