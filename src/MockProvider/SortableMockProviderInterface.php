<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\MockProvider;

/**
 * Interface SortableMockProviderInterface
 *
 * @package TonyBogdanov\MockableAnnotations\MockProvider
 */
interface SortableMockProviderInterface {

    /**
     * @return int
     */
    public function getPriority(): int;

}
