<?php

    /*
     *   App Core Class
     *   Creates URL & loads core controller
     *   URL FORMAT - /controller/method/params
     */

    class Core {
         protected $currentController = 'Pages';
         protected $currentMethod = 'index';
         protected $params = [];
         
         

         public function __construct() {
            //print_r($this->getUrl());

            $url = $this->getUrl();

            // Check if url array has values inside and then look in controllers for first value
            if(count($url) > 0 && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                //If exists, set as controller
                $this->currentController = ucwords(array_shift($url));
            }

            require_once '../app/controllers/' . $this->currentController . '.php';

            // Instantiate controller class
            $this->currentController = new $this->currentController;

            // Check for second part of url
            if(count($url) > 0) {
                // Check to see if method exists in controller
                if(method_exists($this->currentController, $url[0])) {
                    $this->currentMethod = array_shift($url);
                }
            }

            // Get Params
            $this->params = $url ? array_values($url) : [];

            // Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
         }

         public function getUrl() {
            $url = [];
            if(isset($_GET['url'])) {
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
            }
            return $url;
         }
    }