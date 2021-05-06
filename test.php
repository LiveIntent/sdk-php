<?php

trait MyTrait
{
    public function traitMethod()
    {
        echo "this is a trait method\n";
    }
}

class Person
{
    public function traitMethod()
    {
        echo "this is a not trait method\n";
    }

    public function __call($method, $parameters)
    {
        echo "Should call {$method}";
    }
}

class User extends Person
{
    use MyTrait;

}
$user = new User();

$user->traitMethod();
