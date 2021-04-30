<?php
namespace Jwt\Tests\Unit;
use Jwt\Service\IClock;

class ManualClock implements IClock
{
    private $clock;

    public function __construct(\DateTime $clock)
    {
        $this->clock = $clock;
    }

    public function now(){
        return $this->clock;
    }

    public function passTime($time){
        $this->clock->modify($time);
    }
}
