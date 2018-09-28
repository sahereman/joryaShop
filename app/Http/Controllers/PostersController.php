<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use Illuminate\Http\Request;

class PostersController extends Controller
{
    public function index (Request $request)
    {
        return view('posters.index');
    }

    public function show (Poster $poster)
    {
        return view('posters.show', []);
    }

    public function create ()
    {
        return view('posters.create_and_edit');
    }

    public function store (Request $request)
    {
        // TODO ...
    }

    public function edit (Poster $poster)
    {
        return view('posters.create_and_edit', []);
    }

    public function update (Poster $poster)
    {
        // TODO ...
    }

    public function destroy (Poster $poster)
    {
        // TODO ...
    }
}
