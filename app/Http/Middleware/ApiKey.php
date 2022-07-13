<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKey
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
        $apiKey = $request->header('apiKey');
        if ($apiKey) {
            if ($apiKey != "laravel_sell_shop") {
                return response(["message" => "Your apiKey not correct !"]);
            }
        } else {
            return response(["message" => "This end point need apiKey !"]);
        }

        return $next($request);
    }
}
