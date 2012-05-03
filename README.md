#Simply way to create a RESTful API

RestMeUp is a simple REST framework to simply create a RESTful application in PHP.
I'm using it in a future version of my API since I wanted a simple way to turn it into a "real world" RESTful application.


#How to use ?

You've got only 2 things to do :

1.   create a BRRestMeUp child to hold your clients requests,
2.   create a create a .htaccess to redirect URI to your BRRestMeUP child(ren).

##Create a BRRestMeUp child

Example, a simple API for candies (does nothing usefull but I think it's a good example).
Just put the following code in index.php in the root folder of your webservice.

```php
<?php 
    include_once("RestMeUp/BRRestMeUp.php");

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
```

##Create a .htaccess file

Here is a simple .htaccess wich redirects all traffic to index.php

```
#Turn RewriteEngine on
RewriteEngine on

#Try to rewrite all URI if it's not a file
RewriteCond %{REQUEST_FILENAME} !-f
#Try to rewrite all URI if it's not a folder
#RewriteCond %{REQUEST_FILENAME} !-d

#Rewrite URI and give it to index.php
RewriteRule ^(.*)$ /index.php?uri=$1 [L,QSA]

```


