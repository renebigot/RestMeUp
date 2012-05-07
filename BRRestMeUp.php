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

    include_once("BRRestMeUpException.php");
    
    /** 
     * BRRestMeUp is the main class for RestMeUp. To use it, simply create a 
     * class that extends BRRestMeUp.
     * The child class must contain :
     *    - a protected variable named $routes
     *    - a set of methods which will be the RESTful API callbacks.
     *
     * @author René BIGOT (http://www.brae.fr)
     */
    class BRRestMeUp {
        /**
         * @var $uri : contains the URI which should be translated thanks to
         * $routes
         */
        protected $uri = "";
        
        /**
         * @var $routes : contains the URIs map for each acceptable CRUD methods
         * For easier maintenance, BRRestMeUp converts :
         *     - HTTP POST to create
         *     - HTTP GET to read
         *     - HTTP PUT to update
         *     - HTTP DELETE to delete
         *
         * $routes definition example for a read only service : 
         * $routes = 
         *   array (
         *          "read" => array (
         *                           array ("uri_preg" => "#^countries(\/*)$#",
         *                                  "callback" => "readCountries"),
         *                           array ("uri_preg" => "#^countries(.*)#",
         *                                  "callback" => "readTowns")
         *                           )
         *          );
         *
         * @var "uri_preg" is the regular expression to translate
         * @var "callback" is the instance method to call if the URI maps to the
         * "uri_preg"
         */
        protected $routes;
        
        /**
         * Constructor method build a BRRestMeUp instance.
         * @param $uri : The URI that we want to map to a valid method
         *
         * @author René BIGOT (http://www.brae.fr)
         */
        public function __construct($uri) {
            //if $routes is not defined in the child class => 501 Error
            if ( ! isset($this->routes) ) {
                $error = "No routes defined in the API, please create some";
                throw new BRRestMeUpException (501, $error);
            }
            
            //if no uri is provided => 501 Error
            $this->uri = $uri;
            if (!$this->uri) {
                throw new BRRestMeUpException(500, "Internal server error.");
            }
            
            //get the CRUD method name from the HTTP method
            $method = $this->getCRUDMethod();
            
            //Execute callback
            $this->crudMe($method);
        }
        
        /**
         * crudMe read the $routes array to translate the URI to a know callback
         * If the callback is not defined for the URI in the routes, RestMeUp 
         * throw a 401 error.
         * If the callback is defined but the callback could not be called for 
         * some reasons, RestMeUp throw a 501 error.
         *
         * @param $method : CRUD method name (create, read, update or delete)
         *
         * @author René BIGOT (http://www.brae.fr)
         */
        protected function crudMe($method) {
            //read each routes for $method in $routes
            foreach ($this->routes[$method] as $route) {
                //try to match the current route to the URI
                if (preg_match($route["uri_preg"], $this->uri, $info)) {
                    $callback = array($this, $route["callback"]);

                    $args = explode("/", $this->uri);
                    //we don't care about the last / if nothing after.
                    if (count($args) > 0 && $args[count($args)-1] == "") 
                        unset($args[count($args)-1]);

                    //501 error if method is not callable
                    if ( ! is_callable($callback) ) {
                        $error = "The method (" . $callback;
                        $error .= ") you're trying to call is not implemented";
                        throw new BRRestMeUpException(501, $error);
                    }
                    
                    //call the callback
                    call_user_func_array($callback, $args);
                    
                    return;
                }
            }
            
            //if we reach this point, we probably want to throw that Exception
            $error = "You are not authorized to access this resource.";
            throw new BRRestMeUpException(401, $error);
        }
        
        /**
         * getCRUDMethod read the HTTP method from PHP $_SERVER variable and 
         * translate it to CRUD.
         * 
         *
         * @author René BIGOT (http://www.brae.fr)
         */
        private function getCRUDMethod() {
            $method = $_SERVER['REQUEST_METHOD'];
            $override = isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) 
            ? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] 
            : (isset($_GET['method']) ? $_GET['method'] : '');
            
            if ($method == 'POST') {
                if (strtoupper($override) == 'PUT') {
                    return "update";
                } elseif (strtoupper($override) == 'DELETE') {
                    return "delete";
                } else {
                    return "create";
                }
            }
            return "read";
        }
    }
    
    ?>