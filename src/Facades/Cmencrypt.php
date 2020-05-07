<?php
namespace Stardust\crypt\Facades;
use Illuminate\Support\Facades\Facade;
class Crypt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'crypt';
    }
}