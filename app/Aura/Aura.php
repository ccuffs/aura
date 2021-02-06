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
            try {
                $object = app()->make($className);
                $instances[$key] = $object;

            } catch(\Exception $e) {
                Log::error('Failed to instantiate responder', [
                    'responder' => $key,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        $this->responders = $instances;
    }

    protected function initResponders() 
    {
        foreach($this->responders as $responder) {
            $responder->init();
        }
    }

    public function __construct($config)
    {
        $this->config = $config;
        
        $this->createResponderInstances($this->config['responders']);
        $this->initResponders();
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
                Log::error('Responder failed to engage', [
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
