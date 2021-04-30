<?php
namespace Jwt\Service;

use DateTime;

interface IClock
{
    /**
     * @return DateTime
     */
    public function now();
}
