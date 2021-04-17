<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Aura\Support\Facades\Aura;
use App\Http\Controllers\Controller;

class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $command = $request->input('q', 'Hello, world!');
        $passport = $request->header('X-Aura-Passport');

        return Aura::process($command, $passport)->toJson();
    }
}
