<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Answer;
use App\Question;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;

class CommentController extends Controller
{
    function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    public function storeQ(Request $request, $id)
    {
        $comment = Comment::create([
            'content' => $request['comment'],
            'user_id' => Auth::id(),
            'answer_id' => null,
            'question_id' => $id,
        ]);

        Alert::success('Success', 'Your Comment Has been Saved');

        return redirect()->action(
            'QuestionController@show', ['question_id' => $id]
        );
    }

    public function storeA(Request $request, $id)
    {
        $comment = Comment::create([
            'content' => $request['comment'],
            'user_id' => Auth::id(),
            'answer_id' => $id,
            'question_id' => null,
        ]);

        Alert::success('Success', 'Your Comment Has been Saved');
        
        $answer = Answer::find($id);
        
        return redirect()->action(
            'QuestionController@show', ['question_id' => $answer->question_id]
        );
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $question = null;
        if ($comment->question_id != null) {
            $question = Question::find($comment->question_id);
        } 
        else {
            $answer = Answer::find($comment->answer_id);
            $question = Question::find($answer->question_id);
        }

        Comment::destroy($id);

        Alert::success('Success', 'Your Comment Has been Deleted');
        
        return redirect()->action(
            'QuestionController@show', ['question_id' => $question->id]
        );
    }
}
