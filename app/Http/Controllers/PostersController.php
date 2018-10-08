<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use Illuminate\Http\Request;

class PostersController extends Controller
{

    // GET 通用-广告展示
    public function show (Poster $poster)
    {
        return view('posters.show', []);
    }
}
