<?php

namespace App\Aura\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Aura extends Facade {
    protected static function getFacadeAccessor() { return 'aura'; }
}