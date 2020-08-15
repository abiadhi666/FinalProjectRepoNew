<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $questions = Question::all()->sortByDesc('updated_at');
        return view('home', compact('questions'));
    }

    public function showProfile($id)
    {
        $user = User::find($id);
        return view('profile.index', compact('user'));
    }

    public function showQuestions($id)
    {
        $user = User::find($id);
        return view('question.index', compact('user'));
    }
}
