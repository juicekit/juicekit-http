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

abstract class Message implements MessageInterface
{
    protected $headers = array();
    protected $content = null;
    protected $length = 0;

    public function setHeaders($headers)
    {
        if (!is_array($headers) && !($headers instanceof \Traversable)) {
            throw new InvalidArgumentException(sprintf("%s is not an array or traversable object.", $headers));
        }

        $this->headers = $headers;
    }

    public function getHeaders($key = null)
    {
        if ($key === null) {
            return $this->headers;
        } else if (!is_scalar($key)) {
            throw new InvalidArgumentException(sprintf("%s is not a valid key type.", $key));
        } else if (isset($this->headers[$key])) {
            return $this->headers[$key];
        }

        return null;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    public function getLength()
    {
        return $this->length;
    }
}