<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Test\Helper;

use TonyBogdanov\MockableAnnotations\Test\AbstractTestCase;
use RuntimeException;

/**
 * Class CrudTestBuilder
 *
 * @package TonyBogdanov\MockableAnnotations\Test\Helper
 */
class CrudTestBuilder {

    /**
     * @var AbstractTestCase
     */
    protected $testCase;

    /**
     * @var object
     */
    protected $object;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return callable
     */
    protected function hasser(): callable {

        $name = 'has' . ucfirst( $this->name );
        if ( method_exists( $this->object, $name ) ) {

            return [ $this->object, $name ];

        }

        throw new RuntimeException( sprintf(

            'Missing hasser for %1$s in %2$s.',
            $this->name,
            get_class( $this->object )

        ) );

    }

    /**
     * @return callable
     */
    protected function getter(): callable {

        $name = 'get' . ucfirst( $this->name );
        if ( method_exists( $this->object, $name ) ) {

            return [ $this->object, $name ];

        }

        $name = 'is' . ucfirst( $this->name );
        if ( method_exists( $this->object, $name ) ) {

            return [ $this->object, $name ];

        }

        throw new RuntimeException( sprintf(

            'Missing getter for %1$s in %2$s.',
            $this->name,
            get_class( $this->object )

        ) );

    }

    /**
     * @return callable
     */
    protected function setter(): callable {

        $name = 'set' . ucfirst( $this->name );
        if ( method_exists( $this->object, $name ) ) {

            return [ $this->object, $name ];

        }

        throw new RuntimeException( sprintf(

            'Missing setter for %1$s in %2$s.',
            $this->name,
            get_class( $this->object )

        ) );

    }

    /**
     * @return callable
     */
    protected function adder(): callable {

        $name = 'add' . rtrim( ucfirst( $this->name ), 's' );
        if ( method_exists( $this->object, $name ) ) {

            return [ $this->object, $name ];

        }

        throw new RuntimeException( sprintf(

            'Missing adder for %1$s in %2$s.',
            $this->name,
            get_class( $this->object )

        ) );

    }

    /**
     * @return callable
     */
    protected function clearer(): callable {

        $name = 'clear' . ucfirst( $this->name );
        if ( method_exists( $this->object, $name ) ) {

            return [ $this->object, $name ];

        }

        throw new RuntimeException( sprintf(

            'Missing clearer for %1$s in %2$s.',
            $this->name,
            get_class( $this->object )

        ) );

    }

    /**
     * CrudTestBuilder constructor.
     *
     * @param AbstractTestCase $testCase
     * @param object $object
     * @param string $name
     */
    public function __construct( AbstractTestCase $testCase, object $object, string $name ) {

        $this->testCase = $testCase;
        $this->object = $object;
        $this->name = $name;

    }

    /**
     * @param bool $expectedValue
     *
     * @return $this
     */
    public function has( bool $expectedValue): self {

        $this->testCase->assertSame( $expectedValue, call_user_func( $this->hasser() ) );
        return $this;

    }

    /**
     * @param $expectValue
     *
     * @return $this
     */
    public function get( $expectValue ): self {

        $this->testCase->assertSame( $expectValue, call_user_func( $this->getter() ) );
        return $this;

    }

    /**
     * @param $newValue
     *
     * @return $this
     */
    public function set( $newValue ): self {

        $this->testCase->assertSame( $this->object, call_user_func( $this->setter(), $newValue ) );
        return $this->get( $newValue );

    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function add( $value ): self {

        $current = call_user_func( $this->getter() );
        $this->testCase->assertSame( $this->object, call_user_func( $this->adder(), $value ) );

        return $this->get( array_merge( $current, [ $value ] ) );

    }

    /**
     * @param $expectedValue
     *
     * @return $this
     */
    public function clear( $expectedValue ): self {

        $this->testCase->assertSame( $this->object, call_user_func( $this->clearer() ) );
        return $this->get( $expectedValue );

    }

}
