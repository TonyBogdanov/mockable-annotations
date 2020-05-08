<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use PHPUnit\Framework\TestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\CrudTestBuilder;

/**
 * Class AbstractTestCase
 *
 * @package TonyBogdanov\MockableAnnotations\Test
 */
abstract class AbstractTestCase extends TestCase {

    /**
     * @param object $object
     * @param string $name
     *
     * @return CrudTestBuilder
     */
    public function crud( object $object, string $name ): CrudTestBuilder {

        return new CrudTestBuilder( $this, $object, $name );

    }

    /**
     * @return AnnotationReader
     * @throws AnnotationException
     */
    public function getAnnotationReader(): AnnotationReader {

        return new AnnotationReader();

    }

    /**
     * @return CachedReader
     * @throws AnnotationException
     */
    public function getCachedReader(): CachedReader {

        return new CachedReader( $this->getAnnotationReader(), new ArrayCache() );

    }

}
