<?php

namespace App\Aura\Interactions;

use App\Aura\Responders\Responder;
use Illuminate\Support\Str;

/**
 * 
 */
class Interaction
{
    protected string $responseText;
    protected string $inputText;
    protected array $responders;
    protected array $debug;    

    public function __construct($text = '')
    {
        $this->inputText = $text;
        $this->responseText = '';
        $this->responders = [];
        $this->debug = [];
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
    public function addResponse($data, $score, Responder $responder)
    {
        $this->responders[] = [
            'name' => $responder->name(),
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

    public function setDebugInfo($key, $data) {
        if(!$key) {
            $key = Str::uuid()->toString();
        }

        $this->debug[$key] = $data;
    }

    public function sortRespondersByScore() {
        usort($this->responders, function($responseA, $responseB) {
            return strcmp($responseA['score'], $responseB['score']);
        });
    }
}
