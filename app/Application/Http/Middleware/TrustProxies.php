<?php

namespace App\Application\Http\Middleware;

use Fideloper\Proxy\TrustProxies as TrustProxiesBase;
use Illuminate\Http\Request;

class TrustProxies extends TrustProxiesBase
{

    /**
     * @var string
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

}
