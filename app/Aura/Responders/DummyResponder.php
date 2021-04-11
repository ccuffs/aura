<?php

namespace App\Aura\Responders;

use App\Aura\Interactions\Interaction;

/**
 * 
 */
class DummyResponder extends Responder
{
    public function init()
    {
    }

    public function name()
    {
        return 'dummy.responder';
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
        $interaction->addResponse('Responder acting on: ' . $interaction, $this);
    }
}
