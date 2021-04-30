<?php

namespace App\Aura;

use App\Aura\Interactions\Interaction;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;
use Exception;

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

    /**
     * 
     * @License: using code from https://github.com/firebase/php-jwt/blob/master/src/JWT.php
     */
    protected function parseJwt($jwt) {
        $parts = explode('.', $jwt);

        if (count($parts) != 3) {
            throw new \Exception('Wrong number of segments in JWT');
        }
        
        list($headb64, $bodyb64, $cryptob64) = $parts;

        $header = json_decode(JWT::urlsafeB64Decode($headb64));
        $payload = json_decode(JWT::urlsafeB64Decode($bodyb64));        
        $sig = json_decode(JWT::urlsafeB64Decode($cryptob64));        

        if ($header === null) {
            throw new Exception('Invalid header encoding in JWT');
        }

        if ($payload === null) {
            throw new Exception('Invalid claims encoding in JWT');
        }

        if ($sig === false) {
            throw new Exception('Invalid signature encoding in JWT');
        }        

        return [
            'header' => (array) $header,
            'payload' => (array) $payload,
            'sig' => $sig,
        ];
    }

    protected function getJwtKeyFromAppId($app_id) {
        // TODO: implementar a busca da informação
        return 'example_keyj';
    }

    /**
     * 
     * @License: using code from https://github.com/firebase/php-jwt/blob/master/src/JWT.php
     */
    protected function checkPassport(string $jwt) {
        $infos = $this->parseJwt($jwt);
        $payload = $infos['payload'];

        if (!isset($payload['app_id'])) {
            throw new \Exception('Missing app_id in passport payload');
        }

        $key = $this->getJwtKeyFromAppId($payload['app_id']);
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        return $decoded;
    }

    protected function createCredentials(string $passport) {
        if(empty($passport)) {
            // TODO: criar credenciais padrão
            return [];
        }

        return [
            'jwt' => $this->checkPassport($passport)
        ];
    }

    public function process(string $text, string $passport)
    {
        try {
            $credentials = $this->createCredentials($passport);
            $interaction = $this->createInteraction($text, $credentials);

            $this->runResponders($interaction);
            $this->chooseBestResponse($interaction);

            return $interaction;
        } catch (Exception $e) {
            // TODO: criar e retornar interaction com erro
            throw $e;
        }
    }
}
