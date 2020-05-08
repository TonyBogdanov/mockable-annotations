<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider\Filter\ClassNameFilter;

use TonyBogdanov\MockableAnnotations\MockProvider\ClassMockProvider\Filter\ClassNameFilter;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class CrudTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\ClassMockProvider\Filter\ClassNameFilter
 */
class CrudTest extends AbstractTestCase {

    public function testClassName() {

        $this
            ->crud( new ClassNameFilter( TestClass::class ), 'className' )
            ->get( TestClass::class )
            ->set( AbstractTestCase::class );

    }

    public function testStrict() {

        $this
            ->crud( new ClassNameFilter( TestClass::class ), 'strict' )
            ->get( false )
            ->set( true );

    }

}
