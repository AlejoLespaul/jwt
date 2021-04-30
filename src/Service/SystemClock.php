<?php

namespace Jwt\Service;

use DateTime;

class SystemClock implements IClock
{
    public function now(){
        return new DateTime();
    }
}
