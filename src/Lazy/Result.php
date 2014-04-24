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
    static public $getCacheCallback   = null;
    static public $setCacheCallback   = null;
    


    function __construct($resultCallback=null, $getCacheCallback=null, $setCacheCallback=null, $params=array()){
        if(!is_null($getCacheCallback))
            self::$getCacheCallback = $getCacheCallback;

        if(!is_null($resultCallback))
            $this->resultCallback = $resultCallback;

        if(!is_null($setCacheCallback))
            self::$setCacheCallback = $setCacheCallback;

        $this->parameters = $params;
    }

    public function result(){
        if(!is_null(self::$getCacheCallback)) 
            $this->result = call_user_func_array(self::$getCacheCallback, $this->parameters);

        if(empty($this->result)){
            $this->result = call_user_func_array($this->resultCallback, $this->parameters);
            if(!is_null(self::$setCacheCallback)) 
                call_user_func(self::$setCacheCallback, $this->parameters, $this->result);
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
        $lazyResult = new self($this->resultCallback, self::$getCacheCallback, self::$setCacheCallback, func_get_args());
        return $lazyResult;
    }

    public function __call($name, $params){
        $lazyResult = new self(array($this->resultCallback[0], $name), self::$getCacheCallback, self::$setCacheCallback, $params);
        return $lazyResult;
    }
}
