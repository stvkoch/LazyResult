<?php
/*

$finder = new \Lazy\Result(array('Model', 'find'), array('Cache','get'), array('Cache','set') );
$result = $finder(array('id'=>1));


$model = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set') );
$result = $model->one(array('id'=>1));


*/
namespace Lazy;
class Result extends \ArrayIterator {

    private $result             = null;
    private $parameters         = array();
    private $resultCallback     = null;

    //global
    static public $globalBeforeCallback  = null;
    static public $globalAfterCallback   = null;

    static public $beforeCallback        = null;
    static public $afterCallback         = null;
    


    function __construct($resultCallback=null, $beforeCallback=null, $afterCallback=null, $params=array()){
        if(!is_null($beforeCallback))
            $this->beforeCallback = $beforeCallback;
        elseif (!is_null(self::$globalBeforeCallback))
            $this->beforeCallback = self::$globalBeforeCallback;

        if(!is_null($resultCallback))
            $this->resultCallback = $resultCallback;

        if(!is_null($afterCallback))
            $this->afterCallback = $afterCallback;
        elseif (!is_null(self::$globalAfterCallback))
            $this->afterCallback = self::$globalAfterCallback;

        $this->parameters = $params;
    }

    public function result(){
        if(!is_null($this->beforeCallback)){
            $this->result = call_user_func($this->beforeCallback, $this->parameters, $this->resultCallback);
        }

        if(empty($this->result)){
            $this->result = call_user_func_array($this->resultCallback, $this->parameters);
            if(!is_null($this->afterCallback)) 
                call_user_func($this->afterCallback, $this->parameters, $this->resultCallback, $this->result);
        }

        return $this->result;
    }

    /**
     * Lazy initializer by loop control
     */
    public function rewind() {
        if(is_null($this->result) && $this->result() && is_array($this->result)){
            parent::__construct($this->result);
        }
    }


    public function __invoke(){
        $lazyResult = new self($this->resultCallback, $this->beforeCallback, $this->afterCallback, func_get_args());
        return $lazyResult;
    }

    public function __call($name, $params=array()){
        $lazyResult = new self(array($this->resultCallback[0], $name), $this->beforeCallback, $this->afterCallback, $params);
        return $lazyResult;
    }
}
