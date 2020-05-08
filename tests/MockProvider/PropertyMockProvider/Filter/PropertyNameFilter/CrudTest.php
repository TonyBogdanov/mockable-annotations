<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;

use TonyBogdanov\MockableAnnotations\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class CrudTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\PropertyMockProvider\Filter\PropertyNameFilter
 */
class CrudTest extends AbstractTestCase {

    public function testClassName() {

        $this
            ->crud( new PropertyNameFilter( TestClass::class, 'property' ), 'className' )
            ->get( TestClass::class )
            ->set( AbstractTestCase::class );

    }

    public function testPropertyName() {

        $this
            ->crud( new PropertyNameFilter( TestClass::class, 'property' ), 'propertyName' )
            ->get( 'property' )
            ->set( 'property2' );

    }

}
