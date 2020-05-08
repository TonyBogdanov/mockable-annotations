<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider;

use ReflectionClass;

/**
 * Interface ClassMockProviderInterface
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider
 */
interface ClassMockProviderInterface {

    /**
     * @param ReflectionClass $class
     * @param array $annotations
     *
     * @return array
     */
    public function mockClassAnnotations( ReflectionClass $class, array $annotations ): array;

}
