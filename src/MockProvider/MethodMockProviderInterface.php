<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider;

use ReflectionMethod;

/**
 * Interface MethodMockProviderInterface
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider
 */
interface MethodMockProviderInterface {

    /**
     * @param ReflectionMethod $method
     * @param array $annotations
     *
     * @return array
     */
    public function mockMethodAnnotations( ReflectionMethod $method, array $annotations ): array;

}
