<?php

namespace App\Classes;

use Illuminate\Http\Request;
use Pelieth\LaravelEcrecover\EthSigRecover;


class CheckSign
{
    public function checkSign($message, $signature, $address)
    {
        $eth_sig_util = new EthSigRecover;
        $signed = $eth_sig_util->personal_ecRecover($message, $signature);
        if ($address == $signed) {
            return true;
        }
        return abort(403);
    }
}
