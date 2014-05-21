<?php
namespace Lazy;
/*
Lazy call method like proxy pattern. This is useful im MCV pattern when you need call severals filters before run the action.

$finder = new \Lazy\Result(array('Model', 'find'), array('Cache','get'), array('Cache','set') );
$result = $finder(array('id'=>1));


$model = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set') );
$result = $model->one(array('id'=>1));

*/

require_once 'EmptyResult.php';

class Result extends \ArrayIterator
{

    private $result             = null;
    private $parameters         = array();
    private $lazyCallback     = null;

    //global
    static public $globalBeforeCallback  = null;
    static public $globalAfterCallback   = null;

    public $beforeCallback        = null;
    public $afterCallback         = null;
    


    public function __construct($lazyCallback = null, $beforeCallback = null, $afterCallback = null, $params = array())
    {
        if (!is_null($beforeCallback)) {
            $this->beforeCallback = $beforeCallback;
        } elseif (!is_null(self::$globalBeforeCallback)) {
            $this->beforeCallback = self::$globalBeforeCallback;
        }

        if (!is_null($lazyCallback)) {
            $this->lazyCallback = $lazyCallback;
        }
        if (!is_null($afterCallback)) {
            $this->afterCallback = $afterCallback;
        } elseif (!is_null(self::$globalAfterCallback)) {
            $this->afterCallback = self::$globalAfterCallback;
        }
        $this->result = new \Lazy\EmptyResult();
        $this->parameters = $params;
    }

    /**
     * 
     */
    public function result()
    {

        if ($this->result instanceof \Lazy\EmptyResult) {
            if (!is_null($this->beforeCallback)) {
                $this->result = call_user_func($this->beforeCallback, $this->parameters, $this->lazyCallback);
            }
            $this->result = call_user_func_array($this->lazyCallback, $this->parameters);

            if (!is_null($this->afterCallback)) {
                call_user_func($this->afterCallback, $this->parameters, $this->lazyCallback, $this->result);
            }
        }

        return $this->result;
    }

    /**
     * Lazy result initializer by loop control
     * @example 
     *  foreach ($proxyResult as $item) {
     *      echo $item->prop;
     *  }
     */
    public function rewind()
    {
        if(is_null($this->result) && $this->result() && is_array($this->result)){
            parent::__construct($this->result);
        }
    }


    /**
     * $proxyResult->successFromResponse
     */
    public function __get($name)
    {
        return $this->result()->$name;
    }

    /**
     * $proxyResult->successFromResponse
     */
    public function __toString()
    {
        return $this->result();
    }



    public function __invoke()
    {
        $lazyResult = new self($this->lazyCallback, $this->beforeCallback, $this->afterCallback, func_get_args());
        return $lazyResult;
    }

    public function __call($name, $params = array())
    {
        if ($this->result instanceof \Lazy\EmptyResult) {
            $lazyResult = new self(array($this->lazyCallback[0], $name), $this->beforeCallback, $this->afterCallback, $params);
            return $lazyResult;
        } elseif (method_exists($this->result(), $name)) {
            return call_user_func_array(array($this->result(), $name), $params);
        }
        throw new \Exception("Method not exist on result");
    }
}