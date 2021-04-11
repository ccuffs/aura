<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Responders
    |--------------------------------------------------------------------------
    |
    | Lista de classes que serão utilizadas para processar qualquer
    | interação com a Aura. Um responder pode ser visto como uma mini
    | aplicação que atua sobre um texto de entrada (o que o usuário escreveu).
    |
    */
    'responders' => [
        'dummy' => App\Aura\Responders\DummyResponder::class,
    ],
];
