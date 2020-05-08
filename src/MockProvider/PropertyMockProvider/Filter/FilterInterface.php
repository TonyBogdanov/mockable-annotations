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
 * Interface FilterInterface
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter
 */
interface FilterInterface {

    /**
     * @param ReflectionProperty $property
     * @param array $annotations
     *
     * @return bool
     */
    public function shouldMock( ReflectionProperty $property, array $annotations ): bool;

}
