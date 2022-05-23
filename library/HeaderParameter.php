<?php namespace PHPBook\Http;

class HeaderParameter extends \Stdclass {

    private $vars = null;

    function __construct($vars) {

        $this->vars = $vars;
    }

    public function __get($var) {

        foreach($this->vars as $key => $value) {
            if(strtolower($var) == strtolower($key)) {
                return $value;
                break;
            }
        }
        return null;
    }

    public function __set($name, $value){

        $this->vars[$name] = $value;

    }


    public function __isset($var) {

        foreach($this->vars as $key => $value) {
            if(strtolower($var) == strtolower($key)) {
                return true;
            }
        }
        return false;
    }

}