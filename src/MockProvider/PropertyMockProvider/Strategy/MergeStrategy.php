<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy;

use ReflectionProperty;

/**
 * Class MergeStrategy
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Strategy
 */
class MergeStrategy implements StrategyInterface {

    /**
     * @param ReflectionProperty $property
     * @param array $annotations
     * @param array $mockedAnnotations
     *
     * @return array
     */
    public function mock( ReflectionProperty $property, array $annotations, array $mockedAnnotations ): array {

        return array_merge( $annotations, $mockedAnnotations );

    }

}
