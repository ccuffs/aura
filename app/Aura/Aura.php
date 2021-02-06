<?php

namespace App\Aura;

use App\Aura\Interactions\Interaction;
use Illuminate\Support\Facades\Log;

/**
 * 
 */
class Aura
{
    protected array $config;
    protected array $responders;

    protected function createResponderInstances($entries)
    {
        if (!is_array($entries) || !count($entries)) {
            return [];
        }

        $instances = [];

        foreach($entries as $key => $className) {
             $object = app()->make($className);
             $instances[$key] = $object;
        }
        
        return $instances;
    }

    public function __construct($config)
    {
        $this->config = $config;
        $this->responders = $this->createResponderInstances($this->config['responders']);
    }

    protected function createInteraction(string $text)
    {
        $interaction = new Interaction($text);
        return $interaction;
    }

    protected function runResponders(Interaction $interaction)
    {
        foreach($this->responders as $responderKey => $responder) {
            try {
                if(!$responder->shouldEngage($interaction)) {
                    continue;
                }
                $statusCode = $responder->engage($interaction);
            } catch(\Exception $e) {
                Log::error('Responder failed', [
                    'responder' => $responderKey,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    public function process(string $text)
    {
        $interaction = $this->createInteraction($text);
        $this->runResponders($interaction);
    }
}
