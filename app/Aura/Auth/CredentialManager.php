<?php

namespace App\Aura\Auth;

use App\Models\App;
use Carbon\Carbon;
use Firebase\JWT\JWT;

/**
 * 
 */
class CredentialManager
{
    /**
     * 
     * @License: using code from https://github.com/firebase/php-jwt/blob/master/src/JWT.php
     */
    public function parseJwt($jwt) {
        $parts = explode('.', $jwt);

        if (count($parts) != 3) {
            throw new \Exception('Wrong number of segments in JWT');
        }
        
        list($headb64, $bodyb64, $cryptob64) = $parts;

        $header = json_decode(JWT::urlsafeB64Decode($headb64));
        $payload = json_decode(JWT::urlsafeB64Decode($bodyb64));        
        $sig = json_decode(JWT::urlsafeB64Decode($cryptob64));        

        if ($header === null) {
            throw new \Exception('Invalid header encoding in JWT');
        }

        if ($payload === null) {
            throw new \Exception('Invalid claims encoding in JWT');
        }

        if ($sig === false) {
            throw new \Exception('Invalid signature encoding in JWT');
        }        

        return [
            'header' => (array) $header,
            'payload' => (array) $payload,
            'sig' => $sig,
        ];
    }

    /**
     * 
     * @param $user array associativo com informações de usuário, por exemplo `['name' => 'Fernando Bevilacqua', 'iduffs' => 'fernando.bevilacqua']`
     * @return string JWT que pode ser utilizado como passaporte.
     */
    public function createPassportFromApp(App $app, array $user)
    {
        $key = $app->secret;
        $payload = array(
            'iss' => $app->name,
            'aud' => $app->domain,
            'iat' => Carbon::now()->timestamp,
            'nbf' => 1357000000,
            'app_id' => $app->id,
            'user' => $user
        );

        $jwt = JWT::encode($payload, $key);

        return $jwt;
    }

    protected function getJwtKeyFromAppId($app_id) {
        $app = App::findOrFail($app_id);
        return $app->secret;
    }

    /**
     * 
     * @License: using code from https://github.com/firebase/php-jwt/blob/master/src/JWT.php
     */
    public function checkPassport(string $jwt) {
        $infos = $this->parseJwt($jwt);
        $payload = $infos['payload'];

        if (!isset($payload['app_id'])) {
            throw new \Exception('Missing app_id in passport payload');
        }

        $key = $this->getJwtKeyFromAppId($payload['app_id']);
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        return $decoded;
    }

    // TODO: retornar uma instancia de Credential ao invés de array.
    public function createCredentials(string $passport) {
        if(empty($passport)) {
            // TODO: criar credenciais padrão
            return new Credentials();
        }

        return new Credentials($this->checkPassport($passport));
    }
}
