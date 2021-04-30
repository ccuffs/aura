<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enpoints de APIs utilizadas
    |--------------------------------------------------------------------------
    |
    | URLs e afins de APIs utilizadas pela Aura.
    |
    */
   'apicc_url' =>  env('APICC_URL', 'Laravel'),

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
        'dummy' => App\Aura\Responders\Dummy::class,
        'uffs.acc' => App\Aura\Responders\UFFS\Acc::class,
    ],
];
