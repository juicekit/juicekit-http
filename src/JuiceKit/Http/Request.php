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

class Request extends Message
{
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_TRACE = 'TRACE';
    const METHOD_HEAD = 'HEAD';
    const METHOD_COPY = 'COPY';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';
    const METHOD_PUT = 'PUT';
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    private $method = self::METHOD_GET;

    private $query = array();
    private $post = array();

    private $server = null;

    function __construct()
    {
        if ($_GET) {
            $this->query = $_GET;
        }

        if ($_POST) {
            $this->post = $_POST;
        }

        $this->setServer($_SERVER);
    }

    protected function setServer($server)
    {
        if (!is_array($server) && !($server instanceof \Traversable)) {
            throw new InvalidArgumentException(sprintf("%s is not a traversable type."));
        }

        $headers = array();
        foreach ($server as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $header = $this->parseHeaderName($name);

                $headers[$header] = $value;
            }
        }

        $this->setHeaders($headers);

        $this->method = $server['REQUEST_METHOD'];

        $this->server = $server;
    }

    protected function parseHeaderName($name)
    {
        $header = str_replace('_', ' ', strtolower(substr($name, 5)));

        return str_replace(' ', '-', ucwords($header));
    }

    public function getContent()
    {
        if ($this->content === null) {
            $this->content = file_get_contents("php://input");
        }

        return $this->content;
    }

    public function getHeaders($key = null)
    {
        $header = parent::getHeaders($key);

        if ($header !== null) {
            return $header;
        }

        $headers = array_change_key_case($this->headers, CASE_LOWER);

        if (isset($headers[strtolower($key)])) {
            return $key[strtolower($key)];
        }

        return null;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPost($key = null)
    {
        if ($key === null) {
            return $this->post;
        } else if (isset($this->post[$key])) {
            return $this->post[$key];
        }

        return null;
    }

    public function getQuery($key = null)
    {
        if ($key === null) {
            return $this->query;
        } else if (isset($this->query[$key])) {
            return $this->query[$key];
        }

        return null;
    }

    public function isPost()
    {
        return $this->method === self::METHOD_POST;
    }

    public function isGet()
    {
        return $this->method === self::METHOD_GET;
    }
}