<?php

include __DIR__.'/../src/Lazy/Result.php';
include 'Cache.php';
include 'Model.php';

/**
 * 
 */
class LazyTest extends PHPUnit_Framework_TestCase
{

    public function testInstance()
    {
        $instance = new \Lazy\Result(array('Model'));
        $this->verifyInstanceResult($instance);
    }


    public function testLazyCallFunc()
    {
        
        $proxy = new \Lazy\Result(array('Model', 'find'));
        $lazyResult = $proxy(array(1));


        $lazyResult = $proxy(array(2,3));
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array('world', 'hello'));
        }

        $proxy = new \Lazy\Result(array('Model', 'find'), array('Cache','get'), array('Cache','set'));
        $lazyResult = $proxy(array(1));

        foreach ($lazyResult as $key => $value) {
            $this->assertEquals('bar', $value);
        }

        $lazyResult = $proxy(array(2,3));
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array('world', 'hello'));
        }
    }


    public function testLazyObjectByResult()
    {
        $proxy = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set'));
        $lazyResult = $proxy->one(1);
        $this->assertEquals('bar', $lazyResult->result());
        foreach ($lazyResult as $key => $value) {
            $this->assertEquals('bahbah', $lazyResult->result());
        }

    }


    public function testLazyObjectByPropResult()
    {
        $proxy = new \Lazy\Result(array('Model'));
        $lazyResult = $proxy->oneObj('bar');

        $this->assertEquals('bar', $lazyResult->value);
        foreach ($lazyResult as $key => $value) {
            $this->assertEquals('bahbah', $lazyResult->value);
        }

    }

    public function testLazyObjectByMethodResult()
    {
        $proxy = new \Lazy\Result(array('Model'));
        $lazyResult = $proxy->oneObj('bar');

        $this->verifyInstanceResult($lazyResult);
        $this->assertEquals('bar', $lazyResult->value);
        $this->assertEquals('bahbah', $lazyResult->get('bahbah'));
    }

    public function testLazyObjectByStringResult()
    {
        $proxy = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set'));
        $lazyResult = $proxy->one(1);

        $this->verifyInstanceResult($lazyResult);
        $this->assertEquals('bar', "$lazyResult");
        foreach ($lazyResult as $key => $value) {
            $this->assertEquals('bahbah', "$lazyResult");
        }

    }

    public function testLazyObjectWithCache()
    {
        $proxy = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set'));
        $lazyResult = $proxy->find(array('hit'=>1));

        $this->verifyInstanceResult($lazyResult);
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array(11,12,13));
        }

        $lazyResult = $proxy->find(array(0,1));
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array('foo', 'bar'));
        }
        $this->verifyInstanceResult($lazyResult);
    }


    public function testLazyObjectWithGlobalCache()
    {
        $proxy = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set'));
        $lazyResult = $proxy->find(array('hit'=>1));
        $this->verifyInstanceResult($lazyResult);
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array(11,12,13));
        }

        $lazyResult = $proxy->find(array(0,1));
        foreach ($lazyResult as $key => $value) {
            $this->assertContains($value, array('foo', 'bar'));
        }
    }

    public function testNotRun()
    {
        $proxy = new \Lazy\Result(array('Model'));
        $lazyResult = $proxy->error();
    }

    protected function verifyInstanceResult($result)
    {
        $this->assertInstanceOf('\Lazy\Result', $result);
    }
}
