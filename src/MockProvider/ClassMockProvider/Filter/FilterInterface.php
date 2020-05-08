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
 * Interface FilterInterface
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter
 */
interface FilterInterface {

    /**
     * @param ReflectionClass $class
     * @param array $annotations
     *
     * @return bool
     */
    public function shouldMock( ReflectionClass $class, array $annotations ): bool;

}
