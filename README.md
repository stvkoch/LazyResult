LazyResult
==========

LazyResult are proxy design that only call function of registered object when result expected interact with loop structures or when your code try access properties of result. Addicional you can configure before and after callbacks, for instance, to use cache manager results.

LazyResult can help your project in many ways, you can use LazyResult to manipulate caches, called to be late pre filters common action controllers.


[![Build Status LazyResult stvkoch by travis-ci](https://travis-ci.org/stvkoch/LazyResult.svg)]


Example
-------

Proxy Lazy Call for one function or callback:

    $lazyObject = new \Lazy\Result(array('Model', 'find'));
    $lazyResult = $lazyObject(array('id'=>1)); //not run yeat
    ....
    foreach($lazyResult as $value) { //in first interation find method is call!
        ....
    }


Proxy Lazy Call object:

    $lazyObject = new \Lazy\Result(array('Model'));
    $lazyResult = $lazyObject->find(array('id'=>1)); //not run yeat
    ....
    foreach($lazyResult as $value) { //in first interation find method is call!
        ....
    }



Proxy Lazy Call object/or function with cache:

    $lazyObject = new \Lazy\Result(array('Model'), array('Cache','get'), array('Cache','set'));
    $lazyResult = $lazyObject->find(array('id'=>1)); //not run yeat
    ....
    foreach($lazyResult as $value) { //in first interation find method is call! but before Cache::get is run, if Cache::get not return result 'find' method is run and after Cache::set with array('id'=>1) more $result
        ....
    }





Way to access values from lazy result
=====================================

You can access the results from LazyResult object with interaction loop, with access properties of original result object or call result() method of LazyResult object.

    
    class Model{
        static function words(){
            return 'LazyResult from model';
        }
        static function one($id){
            return (object) array('id'=>$id, 'name'=>'Steven Koch', 'country'=>'Portugal');
        }
        static function all(){
            return array(
                (object) array('id'=>'1', 'name'=>'Steven Koch', 'country'=>'Portugal'),
                (object) array('id'=>'2', 'name'=>'Joan Rodrigues', 'country'=>'France'),
                (object) array('id'=>'3', 'name'=>'Hans De Groot', 'country'=>'Netherlands')
            );
        }
        
    }
    //..
    $lazyObject = new \Lazy\Result(array('Model'));
    $lazyResult = $lazyObject->one(1); //not run yeat
    echo $lazyResult->name; //now is call one on Model
    //...
    $lazyResult = $lazyObject->all(); //not run yeat
    $arrayResult = $lazyResult->result(); //now is call one on Model
    //or...
    foreach($lazyResult as $item){
        echo $item->name;
    }
    //...
    $lazyResult = $lazyObject->words(); //not run yeat
    echo "".$lazyResult; //now is call one on Model and return result from __toString



Definition
==========


    [$object] new \Lazy\Result(array $callableLazy [, array $beforeCallableLazy] [, array $afterCallableLazy]);



$callableLazy
-------------

\Lazy\Result object registe $callableLazy to run when result() function is called. Inside of \Lazy\Result object result() public method run when initialize foreach loop structure.

$callableLazy receive parameters that you pass for you proxy object


Example:

    $lazyFunc = function($param1, $param2){...};
    $lazyObject = new \Lazy\Result($lazyFunc);
    $lazyObject($param1, $param2);



$beforeCallableLazy
-------------------

When definite, $beforeCallableLazy run before $callableLazy. This is useful if you like add cache manager for your results.

Interface that you need implemented for $beforeCallableLazy:

    boolean $beforeCallableLazy(array $parameters, $callableLazy);



$afterCallableLazy
-------------------

When definite, $afterCallableLazy run after $callableLazy. This is useful if you like set your result on cache manager.

Interface that you need implemented for $afterCallableLazy:

    boolean $afterCallableLazy(array $parameters, $callableLazy, array $resultByCallableLazy);



Functions
---------


### result() ###

Result obejct function run callableLazy. When use resultLazy on loop structure, the loop structure run result funtion in first interation and receive values from callback. In some case you like call result() to receive lazy values from your callback or call callback withou loop structures.





Config global before and after callbacks
----------------------------------------


Maybe you like configurate before and after callbacks to run each time the proxy call the lazy callback.


    \Lazy\Result::$globalBeforeCallback = array('Cache', 'get');
    \Lazy\Result::$globalAfterCallback = array('Cache', 'set');

Now all proxy create use this hooks callbacks.



Tests
=====

phpunit .

Any question!


