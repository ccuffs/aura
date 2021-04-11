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
                // Testa se o responder da vez quer processar a interação.
                // Responder pode escolher não processar uma interação se julgarem
                // que não há nada interessante (por exemplo, não entendeu a pergunta).
                if(!$responder->shouldEngage($interaction)) {
                    continue;
                }

                // O método engage() é onde o responder fará a interação a fará as alterações
                // na interação que está sendo criada.
                $responder->engage($interaction);
            } catch(\Exception $e) {
                // Tivemos um problema severo com algum responder.
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

        return $interaction;
    }
}
