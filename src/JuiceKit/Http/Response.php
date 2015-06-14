<?php
/**
 * Copyright 2013-2014 Yoel Nunez <dev@nunez.guru>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */


namespace JuiceKit\Http;

use JuiceKit\Http\Exception\InvalidArgumentException;


/**
 * Class Response
 * @package JuiceKit\Http
 *
 * @url StatusCodes http://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html
 */
class Response extends Message
{
    private $contentSent = false;

    // informational
    const STATUS_CODE_100 = 100;
    const STATUS_CODE_101 = 101;
    const STATUS_CODE_102 = 102;
    // success
    const STATUS_CODE_200 = 200;
    const STATUS_CODE_201 = 201;
    const STATUS_CODE_202 = 202;
    const STATUS_CODE_203 = 203;
    const STATUS_CODE_204 = 204;
    const STATUS_CODE_205 = 205;
    const STATUS_CODE_206 = 206;
    const STATUS_CODE_207 = 207;
    const STATUS_CODE_208 = 208;
    const STATUS_CODE_226 = 226;
    // redirection
    const STATUS_CODE_300 = 300;
    const STATUS_CODE_301 = 301;
    const STATUS_CODE_302 = 302;
    const STATUS_CODE_303 = 303;
    const STATUS_CODE_304 = 304;
    const STATUS_CODE_305 = 305;
    const STATUS_CODE_306 = 306;
    const STATUS_CODE_307 = 307;
    const STATUS_CODE_308 = 308;
    // client error
    const STATUS_CODE_400 = 400;
    const STATUS_CODE_401 = 401;
    const STATUS_CODE_402 = 402;
    const STATUS_CODE_403 = 403;
    const STATUS_CODE_404 = 404;
    const STATUS_CODE_405 = 405;
    const STATUS_CODE_406 = 406;
    const STATUS_CODE_407 = 407;
    const STATUS_CODE_408 = 408;
    const STATUS_CODE_409 = 409;
    const STATUS_CODE_410 = 410;
    const STATUS_CODE_411 = 411;
    const STATUS_CODE_412 = 412;
    const STATUS_CODE_413 = 413;
    const STATUS_CODE_414 = 414;
    const STATUS_CODE_415 = 415;
    // server error
    const STATUS_CODE_500 = 500;
    const STATUS_CODE_501 = 501;
    const STATUS_CODE_502 = 502;
    const STATUS_CODE_503 = 503;
    const STATUS_CODE_504 = 504;
    const STATUS_CODE_505 = 505;
    const STATUS_CODE_506 = 506;
    const STATUS_CODE_507 = 507;
    const STATUS_CODE_508 = 508;
    const STATUS_CODE_509 = 509;
    const STATUS_CODE_510 = 510;
    const STATUS_CODE_511 = 511;
    const STATUS_CODE_598 = 598;
    const STATUS_CODE_599 = 599;

    public static $stdReasonPhrases = array(
        // informational
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        226 => 'IM Used',
        // redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        /**
         * @deprecated
         */
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        // client error
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request URI Too Long',
        415 => 'Unsupported Media Type',
        // server error
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        598 => 'Network Read Timeout Error',
        599 => 'Network Connect Timeout Error',
    );

    private $statusCode = self::STATUS_CODE_200;
    private $reasonPhrase = null;


    public function getReasonPhrase()
    {
        if ($this->reasonPhrase === null) {
            if (isset(self::$stdReasonPhrases[$this->statusCode])) {
                return self::$stdReasonPhrases[$this->statusCode];
            } else {
                return "Unknown";
            }
        }

        return $this->reasonPhrase;
    }

    public function setReasonPhrase($reasonPhrase)
    {
        $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        if (is_int($statusCode)) {
            throw new InvalidArgumentException(sprintf("%s is not a integer"));
        }

        $this->statusCode = $statusCode;

        return $this;
    }

    public function isOK()
    {
        return $this->statusCode === self::STATUS_CODE_200;
    }

    public function isSuccess()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    public function isRedirect()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    /**
     * @return boolean
     */
    public function isContentSent()
    {
        return headers_sent();
    }


    public function send()
    {
        if ($this->isContentSent()) {
            return $this;
        }

        // output status line
        header(sprintf('HTTP/1.1 %d %s', $this->getStatusCode(), $this->getReasonPhrase()));

        foreach ($this->headers as $name => $value) {
            header(sprintf("%s: %s", $name, $value));
        }

        echo $this->getContent();

        return $this;
    }

} 