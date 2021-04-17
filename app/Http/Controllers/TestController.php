<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use \Firebase\JWT\JWT;

class TestController extends Controller
{
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
