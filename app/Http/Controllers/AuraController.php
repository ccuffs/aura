<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class AuraController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return view('aura.show', [
            'request' => $request,
            'user' => $request->user(),
            'passport' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDAsImFwcF9pZCI6MCwidXNlciI6eyJuYW1lIjoiRmVybmFuZG8gQmV2aWxhY3F1YSIsImlkdWZmcyI6ImZlcm5hbmRvLmJldmlsYWNxdWEifX0.BzVOfOV7MMrPZf4hdtk-NWzTUmdWNY3hPdh2NqeQ16w'
        ]);
    }
}
