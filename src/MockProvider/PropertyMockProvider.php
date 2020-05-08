<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider;

use ReflectionProperty;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter\FilterInterface;
use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy\StrategyInterface;

/**
 * Class PropertyMockProvider
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider
 */
class PropertyMockProvider implements
    PropertyMockProviderInterface,
    SortableMockProviderInterface
{

    /**
     * @var array
     */
    protected $annotations;

    /**
     * @var StrategyInterface
     */
    protected $strategy;

    /**
     * @var FilterInterface|null
     */
    protected $filter;

    /**
     * @var int
     */
    protected $priority;

    /**
     * PropertyMockProvider constructor.
     *
     * @param array $annotations
     * @param StrategyInterface $strategy
     * @param FilterInterface|null $filter
     * @param int $priority
     */
    public function __construct(

        array $annotations,
        StrategyInterface $strategy,
        FilterInterface $filter = null,
        int $priority = 0

    ) {

        $this
            ->setAnnotations( $annotations )
            ->setStrategy( $strategy )
            ->setFilter( $filter )
            ->setPriority( $priority );

    }

    /**
     * @param ReflectionProperty $property
     * @param array $annotations
     *
     * @return array
     */
    public function mockPropertyAnnotations( ReflectionProperty $property, array $annotations ): array {

        if ( $this->hasFilter() && ! $this->getFilter()->shouldMock( $property, $annotations ) ) {

            return $annotations;

        }

        return $this->getStrategy()->mock( $property, $annotations, $this->getAnnotations() );

    }

    /**
     * @return array
     */
    public function getAnnotations(): array {

        return $this->annotations;

    }

    /**
     * @param array $annotations
     *
     * @return $this
     */
    public function setAnnotations( array $annotations ): self {

        $this->annotations = $annotations;
        return $this;

    }

    /**
     * @return StrategyInterface
     */
    public function getStrategy(): StrategyInterface {

        return $this->strategy;

    }

    /**
     * @param StrategyInterface $strategy
     *
     * @return $this
     */
    public function setStrategy( StrategyInterface $strategy ): self {

        $this->strategy = $strategy;
        return $this;

    }

    /**
     * @return bool
     */
    public function hasFilter(): bool {

        return isset( $this->filter );

    }

    /**
     * @return FilterInterface|null
     */
    public function getFilter(): ?FilterInterface {

        return $this->filter;

    }

    /**
     * @param FilterInterface|null $filter
     *
     * @return $this
     */
    public function setFilter( FilterInterface $filter = null ): self {

        $this->filter = $filter;
        return $this;

    }

    /**
     * @return int
     */
    public function getPriority(): int {

        return $this->priority;

    }

    /**
     * @param int $priority
     *
     * @return $this
     */
    public function setPriority( int $priority ): self {

        $this->priority = $priority;
        return $this;

    }

}
