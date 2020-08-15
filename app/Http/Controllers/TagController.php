<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function showQuestions($id)
    {
        $tag = Tag::find($id);
        return view('tag.show', compact('tag'));
    }
}
