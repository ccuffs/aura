<?php

namespace App\Http\Controllers;

use App\Aura\Support\Facades\Aura;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuraController extends Controller
{
    protected function createTestPassport() {
        $app = App::first();

        $passport = Aura::auth()->createPassportFromApp($app, [
            'iduffs' => 'fernando.bevilacqua',
            'email' => 'fernando.bevilacqua@uffs.edu.br'
        ]);

        return $passport;
    }
    
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return view('aura.show', [
            'request' => $request,
            'user' => $user,
            'passport' => $this->createTestPassport()
        ]);
    }
}
