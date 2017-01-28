<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Resource\Api\PaginationResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
trait Pagination
{
    abstract protected function httpGet($path, array $parameters = [], array $requestHeaders = []);

    abstract protected function safeDeserialize(ResponseInterface $response, $className);

    /**
     * @return string
     */
    abstract protected function getPaginationBase();

    /**
     * @param PaginationResponse $response
     *
     * @return mixed|null
     */
    public function getPaginationNext(PaginationResponse $response)
    {
        return $this->getPaginationUrl($response->getNextUrl(), $this->getPaginationBase());
    }

    /**
     * @param PaginationResponse $response
     *
     * @return mixed|null
     */
    public function getPaginationPrevious(PaginationResponse $response)
    {
        return $this->getPaginationUrl($response->getPreviousUrl(), $this->getPaginationBase());
    }

    /**
     * @param PaginationResponse $response
     *
     * @return mixed|null
     */
    public function getPaginationFirst(PaginationResponse $response)
    {
        return $this->getPaginationUrl($response->getFirstUrl(), $this->getPaginationBase());
    }

    /**
     * @param PaginationResponse $response
     *
     * @return mixed|null
     */
    public function getPaginationLast(PaginationResponse $response)
    {
        return $this->getPaginationUrl($response->getLastUrl(), $this->getPaginationBase());
    }

    /**
     * @param string $url
     * @param string $class
     *
     * @return mixed|null
     */
    public function getPaginationUrl($url, $class)
    {
        Assert::stringNotEmpty($class);

        if (empty($url)) {
            return;
        }

        $response = $this->httpGet($url);

        return $this->safeDeserialize($response, $class);
    }
}
