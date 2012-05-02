<?php
    /* 
     Copyright (c) 2012, Rene BIGOT
     http://www.brae.fr
     All rights reserved.
     
     Redistribution and use in source and binary forms, with or without
     modification, are permitted provided that the following conditions are met:
     * Redistributions of source code must retain the above copyright
     notice, this list of conditions and the following disclaimer.
     * Redistributions in binary form must reproduce the above copyright
     notice, this list of conditions and the following disclaimer in the
     documentation and/or other materials provided with the distribution.
     * Neither the name of Rene BIGOT nor the
     names of its contributors may be used to endorse or promote products
     derived from this software without specific prior written permission.
     
     THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
     ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
     WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
     DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
     DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
     (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
     LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
     ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
     (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
     SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     */
    
    /** 
     * BRRestMeUpException is the exception class for RestMeUp.
     * To use it, just throw a BRRestMeUpException with HTTP status code and an optional text.
     *
     * @author René BIGOT (http://www.brae.fr)
     */
    class BRRestMeUpException extends Exception {
        
        /**
         * @var $httpStatusCode : HTTP Status codes definitions extrated from RFC 2616
         */
        public static $httpStatusCode = array(
                                              "100" => "Continue",
                                              "101" => "Switching Protocols",
                                              "200" => "OK",
                                              "201" => "Created",
                                              "202" => "Accepted",
                                              "203" => "Non-Authoritative Information",
                                              "204" => "No Content",
                                              "205" => "Reset Content",
                                              "206" => "Partial Content",
                                              "300" => "Multiple Choices",
                                              "301" => "Moved Permanently",
                                              "302" => "Found",
                                              "303" => "See Other",
                                              "304" => "Not Modified",
                                              "305" => "Use Proxy",
                                              "306" => "(Unused)",
                                              "307" => "Temporary Redirect",
                                              "400" => "Bad Request",
                                              "401" => "Unauthorized",
                                              "402" => "Payment Required",
                                              "403" => "Forbidden",
                                              "404" => "Not Found",
                                              "405" => "Method Not Allowed",
                                              "406" => "Not Acceptable",
                                              "407" => "Proxy Authentication Required",
                                              "408" => "Request Timeout",
                                              "409" => "Conflict",
                                              "410" => "Gone",
                                              "411" => "Length Required",
                                              "412" => "Precondition Failed",
                                              "413" => "Request Entity Too Large",
                                              "414" => "Request-URI Too Long",
                                              "415" => "Unsupported Media Type",
                                              "416" => "Requested Range Not Satisfiable",
                                              "417" => "Expectation Failed",
                                              "500" => "Internal Server Error",
                                              "501" => "Not Implemented",
                                              "502" => "Bad Gateway",
                                              "503" => "Service Unavailable",
                                              "504" => "Gateway Timeout",
                                              "505" => "HTTP Version Not Supported"
                                              );
        
        /**
         * Constructor method build a BRRestMeUpException instance.
         * @param $code : HTTP status code as defined in RFC 2616.
         * @param $message : Optional message to show. If null, status from $httpStatusCode is used.
         *
         * @author René BIGOT (http://www.brae.fr)
         */
        public function __construct($code, $message = null) {            
            header("HTTP/1.1 " . $code . " " . BRRestMeUpException::$httpStatusCode[$code]);  
            echo "<html><body><h1>Status " . $code . " : " . BRRestMeUpException::$httpStatusCode[$code] . "</h1>";
            
            $message = $message ? $message : BRRestMeUpException::$httpStatusCode[$code];
            echo $message;
            parent::__construct($message, $code);
        }
        
    }
    
    ?>