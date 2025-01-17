<?php

declare(strict_types=1);

namespace Bone;

use Closure;

class Exception extends \Exception
{
    const SHIVER_ME_TIMBERS = 'Application Error';
    const LOST_AT_SEA = 'Page not found.';
    const GHOST_SHIP = 'Record not found.';
}

