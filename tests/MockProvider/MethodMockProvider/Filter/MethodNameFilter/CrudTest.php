<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider\Filter\MethodNameFilter;

use TonyBogdanov\MockableAnnotations\MockProvider\MethodMockProvider\Filter\MethodNameFilter;
use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use TonyBogdanov\MockableAnnotations\Test\Helper\TestClass;

/**
 * Class CrudTest
 *
 * @package TonyBogdanov\MockableAnnotations\Test\MockProvider\MethodMockProvider\Filter\MethodNameFilter
 */
class CrudTest extends AbstractTestCase {

    public function testClassName() {

        $this
            ->crud( new MethodNameFilter( TestClass::class, 'method' ), 'className' )
            ->get( TestClass::class )
            ->set( AbstractTestCase::class );

    }

    public function testMethodName() {

        $this
            ->crud( new MethodNameFilter( TestClass::class, 'method' ), 'methodName' )
            ->get( 'method' )
            ->set( 'method2' );

    }

}
