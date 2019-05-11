<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function show(News $news)
    {
        return View::make('app', [
            'component' => 'Event',
            'props' => [
                'event' => $news->paginate(),
            ],
        ]);
    }
}
