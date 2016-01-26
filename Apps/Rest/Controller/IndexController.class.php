<?php
namespace Rest\Controller;

class IndexController extends BaseController {

    
    public function get($data){
    

    	echo 'get';
    }

    public function post($data){
    	print_r($data);
    	echo 'post';
    }
    public function put($data){
    	print_r($data);
    	echo 'put';
    }
}