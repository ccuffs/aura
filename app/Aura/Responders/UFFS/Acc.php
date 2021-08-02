<?php

namespace App\Aura\Responders\UFFS;

use Illuminate\Support\Facades\Http;
use App\Aura\Interactions\Interaction;
use App\Aura\Responders\Responder;

/**
 * 
 */
class Acc extends Responder
{
    public function init()
    {
    }

    public function name()
    {
        return 'uffs.acc';
    }
    
    /**
     * 
     *
     * @param  App\Interactions\Interaction  $interaction
     * @return bool
     */
    public function shouldEngage(Interaction $interaction)
    {
        return stripos($interaction->inputText(), 'acc') !== false;
    }

    /**
     * 
     *
     * @param  App\Interactions\Interaction  $interaction
     * @return 
     */
    public function engage(Interaction $interaction)
    {
        $apiCCUrl = config('aura.apicc_url');
        $matricula = $interaction->credentials()->data('matricula');
        $response = Http::acceptJson()->get($apiCCUrl . "/alunos/$matricula/historico/");

        if(!$response->ok()) {
            return;
        }

        $interaction->addResponse('Acc', $response->json(), 0.9, $this);
    }
}
