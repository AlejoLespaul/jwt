<?php

namespace Jwt\Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Jwt\Facade\JwtFacade;
use Jwt\Http\Middleware\JwtMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Jwt\Service\JwtService;

class MiddlewareTest extends TestCase
{    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIfThereisNotTokenItReturnError401()
    {
        $request = new Request();
        try{
            (new JwtMiddleware())->handle($request, function ($request) { $this->fail();});
        }catch(HttpException $e){
            $this->assertEquals(401, $e->getStatusCode());
            $this->assertEquals(JwtService::INVALID_TOKEN, $e->getMessage());
        }
    }
    
    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMiddlewareWithTokenSuccess()
    {
        $token = JwtFacade::signIn(["test"]);
        
        $request = new Request();
        $request->headers->add([
            "Authorization" => "Bearer " . $token
        ]);
       
        
        (new JwtMiddleware())->handle($request, function ($request) { 
            $this->assertEquals($request->input("dataToken"), ["test"]);    
        });
    }
    
}
