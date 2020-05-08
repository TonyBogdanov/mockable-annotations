<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\Helper;

use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestClassAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestMethodAnnotation;
use TonyBogdanov\MockableAnnotations\Test\Helper\Annotation\TestPropertyAnnotation;

/**
 * Class TestClass
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Helper
 *
 * @TestClassAnnotation("declared")
 */
class TestClass {

    /**
     * @var string
     *
     * @TestPropertyAnnotation("declared")
     */
    public $property;

    /**
     * @TestMethodAnnotation("declared")
     */
    public function method() {}

}
