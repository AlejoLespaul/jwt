<?php


namespace Jwt\Facade;


use Illuminate\Support\Facades\Facade;

class JwtFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "jwtservice";
    }
}
