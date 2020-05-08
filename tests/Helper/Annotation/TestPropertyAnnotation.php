<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\Helper\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class TestPropertyAnnotation
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Helper\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
class TestPropertyAnnotation {

    /**
     * @var string
     */
    public $value;

}
