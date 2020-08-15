<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Answer;
use App\Comment;
use App\Question;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;

class AnswerController extends Controller
{

    function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function create($id)
    {
        return view('answer.create', compact('id'));
    }

    public function store(Request $request, $id)
    {
        $answer = Answer::create([
            'content' => $request['description'],
            'user_id' => Auth::id(),
            'question_id' => $id,
        ]);

        return redirect()->action(
            'QuestionController@show', ['question_id' => $id]
        );
    }

    public function edit($id)
    {
        $answer = Answer::find($id);
        return view('answer.edit', compact('answer'));
    }

    public function update(Request $request, $id)
    {
        $result = Answer::find($id)->update([
            'content' => $request['description'],
        ]);

        Alert::success('Success', 'Your Answer Has been Updated');

        $answer = Answer::find($id);
        return redirect()->action(
            'QuestionController@show', ['question_id' => $answer->question_id]
        );
    }

    public function destroy($id)
    {
        $answer = Answer::find($id);
        Comment::where('answer_id', $id)->delete();
        Answer::destroy($id);
        Alert::success('Success', 'Your Answer Has been Deleted');
        return redirect()->action(
            'QuestionController@show', ['question_id' => $answer->question_id]
        );
    }
}
