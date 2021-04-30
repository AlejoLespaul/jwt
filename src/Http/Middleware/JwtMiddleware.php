<?php

namespace Jwt\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jwt\Facade\JwtFacade as Jwt;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{ 
            $request->merge(["dataToken" => Jwt::getData($request->bearerToken())]);
        }catch (\Exception $e){
            return abort(401, $e->getMessage());
        }
      
        return $next($request);
    }
}
