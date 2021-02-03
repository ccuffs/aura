<?php

namespace App\Aura\Responders;

use App\Aura\Interactions\Interaction;

/**
 * 
 */
class Responder
{
    /**
     * 
     *
     * @param  App\Interactions\Interaction  $interaction
     * @return bool
     */
    public function shouldEngage(Interaction $interaction)
    {
        // TODO: implementar isso
        return true;
    }

    /**
     * 
     *
     * @param  App\Interactions\Interaction  $interaction
     * @return bool
     */
    public function engage(Interaction $interaction)
    {
    }
}
