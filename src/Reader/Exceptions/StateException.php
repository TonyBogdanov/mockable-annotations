<?php

/**
 * Copyright (c) Tony Bogdanov <support@tonybogdanov.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TonyBogdanov\MockableAnnotations\Reader\Exceptions;

use Exception;
use Throwable;

/**
 * Class StateException
 *
 * @package TonyBogdanov\MockableAnnotations\Reader\Exceptions
 */
class StateException extends Exception {

    const CANNOT_POP_EMPTY = 1;

    /**
     * StateException constructor.
     *
     * @param int $code
     * @param Throwable $previous
     */
    public function __construct( int $code, Throwable $previous ) {

        $reason = null;

        switch ( $code ) {

            case static::CANNOT_POP_EMPTY:
                $reason = 'Cannot pop state because the stack is empty. Call pushState() first.';
                break;

        }

        parent::__construct( $reason ?? '', $code, $previous );

    }

}
