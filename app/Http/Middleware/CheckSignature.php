<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Pelieth\LaravelEcrecover\EthSigRecover;


class CheckSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$message,$address)
    {
        $eth_sig_util = new EthSigRecover;
        $signed = $eth_sig_util->personal_ecRecover($message, $request->sign);
        if($signed==$address){
            return $next($request);
        }
    }
}
