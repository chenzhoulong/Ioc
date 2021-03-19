<?php

include 'Loader.php';
spl_autoload_register('Loader::autoload');
require_once 'src/core/Container.php';

//use \core\Container;


class B {

    public function __constructor()
    {

    }

    public function test()
    {
        echo 'B test function';
    }
}

class A {

    public $b;

    public function __constructor(B $b)
    {
        $this->b = $b;
    }

    public function test()
    {
        echo $this->b->test();
    }
}


$c = new core\Container();
$c->a = A::class;
$a = $c->a;
$a->b->test();
//// 从容器中取得company
//// 测试未知依赖关系，直接使用的方法
//$di = new Container();
//$di->company = 'Company';
//$company = $di->company;
//$company->doSomething();//输出: Group:hello|Department:hello|Company:hello|