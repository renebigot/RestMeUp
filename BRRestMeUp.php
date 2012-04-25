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
    
    class BRRestMeUp {
        protected $uri = "";
        
        public function __construct($uri) {
            $this->uri = $uri;
            
            if (!$this->uri) {
                throw new BRRestMeUpException(500, "Internal server error.");
            }
            
            $method = $this->getCRUDMethod();
            
            $this->crudMe($method);
        }
        
        protected function crudMe($method) {
            foreach ($this->routes[$method] as $route) {
                if (preg_match($route["uri_preg"], $this->uri, $info)) {
                    $callback = $route["callback"];
                    call_user_func(array($this, $callback), $info[1]);
                    return;
                }
            }
            throw new BRRestMeUpException(401, "You are not authorized to access this resource.");
        }
        
        private function getCRUDMethod() {
            $method = $_SERVER['REQUEST_METHOD'];
            $override = isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) ? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] : (isset($_GET['method']) ? $_GET['method'] : '');
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