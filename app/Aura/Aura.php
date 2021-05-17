<?php

namespace App\Aura;

use App\Aura\Auth\CredentialManager;
use App\Aura\Interactions\Interaction;
use Illuminate\Support\Facades\Log;

/**
 * 
 */
class Aura
{
    protected array $config;
    protected array $responders;
    protected CredentialManager $auth;

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
        $this->auth = new CredentialManager();

        $this->createResponderInstances($this->config['responders']);
        $this->initResponders();
    }

    protected function createInteraction(string $text, array $credentials)
    {
        $interaction = new Interaction($text);
        $interaction->setCredentials($credentials);

        return $interaction;
    }

    protected function runResponders(Interaction $interaction)
    {
        $debugResponders = [
            'checked' => count($this->responders),
            'engaged' => 0,
            'not_enganged' => 0,
            'failed' => 0
        ];

        $startTime = hrtime(true);

        foreach($this->responders as $responderKey => $responder) {
            try {
                // Testa se o responder da vez quer processar a interação.
                // Responder pode escolher não processar uma interação se julgarem
                // que não há nada interessante (por exemplo, não entendeu a pergunta).
                if(!$responder->shouldEngage($interaction)) {
                    $debugResponders['not_engaged']++;
                    continue;
                }

                // O método engage() é onde o responder fará a interação a fará as alterações
                // na interação que está sendo criada.
                $responder->engage($interaction);
                $debugResponders['engaged']++;
            } catch(\Exception $e) {
                // Tivemos um problema severo com algum responder.
                $debugResponders['failed']++;
                Log::error('Responder failed to engage', [
                    'responder' => $responderKey,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $endTime = hrtime(true);
        
        $interaction->addDebugInfo('responders', $debugResponders);
        $interaction->addDebugInfo('processing_time_ms', $endTime - $startTime);
    }

    protected function chooseBestResponse(Interaction $interaction) {
        // Ordena o conjunto de responder do maior para o menor score de resposta.
        $interaction->sortRespondersByScore();
    }

    public function process(string $text, string $passport)
    {
        try {
            $credentials = $this->auth->createCredentials($passport);
            $interaction = $this->createInteraction($text, $credentials);

            $this->runResponders($interaction);
            $this->chooseBestResponse($interaction);

            return $interaction;
        } catch (\Exception $e) {
            // TODO: criar e retornar interaction com erro
            throw $e;
        }
    }
}
