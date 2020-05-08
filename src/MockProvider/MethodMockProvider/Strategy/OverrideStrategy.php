<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy;

use ReflectionMethod;

/**
 * Class OverrideStrategy
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Strategy
 */
class OverrideStrategy implements StrategyInterface {

    /**
     * @param ReflectionMethod $method
     * @param array $annotations
     * @param array $mockedAnnotations
     *
     * @return array
     */
    public function mock( ReflectionMethod $method, array $annotations, array $mockedAnnotations ): array {

        return $mockedAnnotations;

    }

}
