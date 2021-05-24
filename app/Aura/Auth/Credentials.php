<?php

namespace App\Aura\Auth;

/**
 * 
 */
class Credentials
{
    protected array $data;

    public function __construct($decodedJwt = [])
    {
        if(!is_array($decodedJwt)) {
            throw new \Exception('Constructor must receive an array');
        }

        $this->data = isset($decodedJwt['user']) ? $decodedJwt['user'] : [];
    }

    public function data($field)
    {
        return isset($this->data[$field]) ? $this->data[$field] : null;
    }
}
