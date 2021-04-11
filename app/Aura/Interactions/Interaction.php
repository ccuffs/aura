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
    protected array $entities;    

    public function __construct($text = '')
    {
        $this->inputText = $text;
        $this->responseText = '';
        $this->responders = [];
        $this->entities = [];
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
    public function addResponse($data, Responder $responder)
    {
        $this->responders[] = [
            'name' => $responder->name(),
            'instance' => $responder,
            'data' => $data
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
            'responders' => $this->responders
        ];
    }

    public function __toString()
    {
        return '[Interaction inputText="' . $this->inputText() . '"]';
    }
}
