<?php

namespace App\Aura\Interactions;

/**
 * 
 */
class Interaction
{
    protected string $text;

    public function __construct($text = '')
    {
        $this->text = $text;
    }

    /**
     * Texto puro que descreve essa query.
     *
     * @return string
     */
    public function text()
    {
        return $this->text;
    }

    public function __toString()
    {
        return '[Interaction]' . $this->text();
    }
}
