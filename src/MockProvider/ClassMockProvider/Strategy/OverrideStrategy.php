<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy;

use ReflectionClass;

/**
 * Class OverrideStrategy
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Strategy
 */
class OverrideStrategy implements StrategyInterface {

    /**
     * @param ReflectionClass $class
     * @param array $annotations
     * @param array $mockedAnnotations
     *
     * @return array
     */
    public function mock( ReflectionClass $class, array $annotations, array $mockedAnnotations ): array {

        return $mockedAnnotations;

    }

}
