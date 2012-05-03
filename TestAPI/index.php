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
    include_once("../BRRestMeUp.php");
        
    class CandiesAPI extends BRRestMeUp {
        protected $routes = array (
                                   "create" => array (
                                                      array ("uri_preg" => "#^candies(.*)#", "callback" => "createCandies")
                                                      ),
                                   "read" => array (
                                                    array ("uri_preg" => "#^candies(\/*)$#", "callback" => "listCandies"),
                                                    array ("uri_preg" => "#^candies\/chocolates(\/*)#", "callback" => "listChocolates"),
                                                    array ("uri_preg" => "#^candies(.*)#", "callback" => "listOthersCandiesType")
                                                    ),
                                   "update" => array (
                                                      //don't want to be able to update candies (for some reasons)
                                                      ),
                                   "delete" => array (
                                                      array ("uri_preg" => "#^candies(.*)#", "callback" => "deleteCandies")
                                                      )
                                   );
        
        //CREATE
        protected function createCandies() {
            $argNumber = func_num_args();
            $args = func_get_args();
            
            $candiesType = "";
            
            if ($argNumber != 3) throw new BRRestMeUpException(501, "Your request is not implemented in this API");
            
            echo "You are trying to create the " . $args[2] . " candy in the " . $args[3] . " category.";
        }
        
        //READ
        protected function listCandies() {
            echo "This is a list of all known candies\n";
        }
        
        protected function listChocolates() {
            echo "This is a list of all known candies with chocolates\n";
        }
        
        protected function listOthersCandiesType() {
            $argNumber = func_num_args();
            $args = func_get_args();
            
            $candiesType = "";
            
            if ($argNumber >= 2) {
                $candiesType = $args[1];
                if ($candiesType != "fudge") throw new BRRestMeUpException(400, $candiesType . " candies should not be eaten !");
                echo "Your candies are : " . $candiesType . "\n";
            }
            
            if ($argNumber >= 3) {
                echo "This is a list of all ingredients in " . $args[2] . "\n";
            }
            
            if ($argNumber >= 4) {
                throw new BRRestMeUpException(403, "You don't have access to the ingredients\n");
            }
        }
        
        //DELETE
        protected function deleteCandies() {
            $argNumber = func_num_args();
            $args = func_get_args();
            
            $candiesType = "";
            
            if ($argNumber != 3) throw new BRRestMeUpException(501, "Your request is not implemented in this API");
            
            echo "You are trying to delete the " . $args[2] . " candy in the " . $args[3] . " category.\n";
        }
    }
    
    new CandiesAPI($_GET["uri"]);
    
    ?>