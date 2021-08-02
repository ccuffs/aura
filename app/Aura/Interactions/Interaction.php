<?php

namespace App\Aura\Interactions;

use App\Aura\Auth\Credentials;
use App\Aura\Responders\Responder;
use Illuminate\Support\Str;

/**
 * 
 */
class Interaction
{
    protected string $responseText;
    protected array $responseData;
    protected string $inputText;
    protected array $responders;
    protected Credentials $credentials;
    protected array $debug;    

    public function __construct($text = '')
    {
        $this->inputText = $text;
        $this->responseText = '';
        $this->responseData = [];
        $this->responders = [];
        $this->debug = [];
        $this->credentials = new Credentials(['user' => 'guest']);
    }

    /**
     * 
     */
    public function setCredentials(Credentials $credentials) {
        $this->credentials = $credentials;
    }

    /**
     * 
     */
    public function credentials() {
        return $this->credentials;
    }

    /**
     * Texto puro que descreve essa query.
     *
     * @return string
     */
    public function inputText()
    {
        return $this->inputText;
    }

    /**
     * Testo puro que descreve essa query.
     *
     * @return string
     */
    public function addResponse(string $text, array $data, float $score, Responder $responder)
    {
        $this->responders[] = [
            'name' => $responder->name(),
            'text' => $text,            
            'data' => $data,
            'score' => $score,
            'instance' => $responder
        ];
    }    

    public function toJson()
    {
        return [
            'input' => [
                'text' => $this->inputText,
            ],
            'response' => [
                'text' => $this->responseText,
                'data' => $this->responseData,
                'context' => Str::uuid()->toString()
            ],
            'responders' => $this->responders,
            'debug' => $this->debug,
        ];
    }

    public function __toString()
    {
        return '[Interaction inputText="' . $this->inputText() . '"]';
    }

    public function addDebugInfo($key, $data) {
        if(!$key) {
            $key = Str::uuid()->toString();
        }

        $this->debug[$key] = $data;
    }

    public function pickBestResponder() {
        $this->sortRespondersByScore();
        $responder = $this->responders[0];
        $this->responseText = $responder['text'];
        $this->responseData = $responder['data'];
    }

    protected function sortRespondersByScore() {
        usort($this->responders, function($responseA, $responseB) {
            return strcmp($responseB['score'], $responseA['score']);
        });
    }
}
