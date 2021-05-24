<?php

namespace App\Http\Controllers;

use App\Aura\Support\Facades\Aura;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use \Firebase\JWT\JWT;

class TestController extends Controller
{
    public function credentials(Request $request)
    {
        $app = App::first();

        $passport = Aura::auth()->createPassportFromApp($app, [
            'iduffs' => 'fernando.bevilacqua',
            'email' => 'fernando.bevilacqua@uffs.edu.br',
            'matricula' => '',
        ]);

        $credentials = Aura::auth()->createCredentials($passport);

        return [
            'credentials' => $credentials,
            'passport' => $passport
        ];
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function passport(Request $request)
    {
        $key = 'example_key';
        $payload = array(
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000,
            'app_id' => 0,
            'user' => [
                'name' => 'Fernando Bevilacqua',
                'iduffs' => 'fernando.bevilacqua'
            ]
        );

        $jwt = JWT::encode($payload, $key);
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        return [
            'jwt' => $jwt,
            'decoded' => (array) $decoded
        ];
    }
}