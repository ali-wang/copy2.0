<?php
namespace app\facade;
use think\facade;

class Connectother extends facade
{
    protected static function getFacadeClass()
    {
        return '\app\common\Connectother';
    }

}