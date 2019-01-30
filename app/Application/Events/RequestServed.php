<?php

namespace App\Application\Events;

use App\Core\ValueObjects\HasInitializationConstructor;

final class RequestServed {

    use HasInitializationConstructor;

    /** @var string */
    private $requestUrl;

    /** @var string */
    private $requestIp;

    /** @var int */
    private $responseStatusCode;

    /**
     * @return string
     */
    public function getRequestUrl(): string {
        return $this->requestUrl;
    }

    /**
     * @return string
     */
    public function getRequestIp(): string {
        return $this->requestIp;
    }

    /**
     * @return int
     */
    public function getResponseStatusCode(): int {
        return $this->responseStatusCode;
    }

}
