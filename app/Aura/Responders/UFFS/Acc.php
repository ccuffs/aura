<?php

namespace App\Aura\Responders\UFFS;

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
        return true;
    }

    /**
     * 
     *
     * @param  App\Interactions\Interaction  $interaction
     * @return 
     */
    public function engage(Interaction $interaction)
    {
        $interaction->addResponse('Responder acting on: ' . $interaction, 0.3, $this);
    }
}
