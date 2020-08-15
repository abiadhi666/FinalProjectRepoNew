<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;
use App\Comment;
use App\Tag;
use App\Reputation;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;

class QuestionController extends Controller
{
    function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('question.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Handle the tags first
        // 1. Remove whitespace and turn to array
        $tags_arr = explode(',', strtolower($request['tags']));

        // 2. Looping into array
        $tag_ids = [];
        foreach ($tags_arr as $tag_name) {
            $tag = Tag::where('name', $tag_name)->first();

            // 3. If tag already exist, take the ID
            if($tag) {
                $tag_ids[] = $tag->id;
            } 
            // 4. If not, save it first and take the ID
            else {
                $new_tag = Tag::create([
                    'name' => trim($tag_name)
                ]);
                $tag_ids[] = $new_tag->id;
            }
        }

        // Save the question
        $question = Question::create([
            'title' => $request['title'],
            'content' => $request['description'],
            'user_id' => Auth::id(),
        ]);

        $question->tags()->sync($tag_ids);
        
        Alert::success('Success', 'Your Question Has been Added');

        return redirect()->action(
            'QuestionController@show', ['question_id' => $question->id]
        );
    }

    public function show($id)
    {
        $question = Question::find($id);
        return view('question.show', compact('question'));
    }

    public function edit($id)
    {
        $question = Question::find($id);
        $tags_name = [];

        foreach ($question->tags as $tag) {
            $tags_name[] = $tag->name;
        }

        return view('question.edit', compact('question', 'tags_name'));
    }

    public function update(Request $request, $id)
    {
        $tags_arr = explode(',', strtolower($request['tags']));

        $tag_ids = [];
        foreach ($tags_arr as $tag_name) {
            $tag = Tag::where('name', $tag_name)->first();

            if($tag) {
                $tag_ids[] = $tag->id;
            } 
            else {
                $new_tag = Tag::create([
                    'name' => trim($tag_name)
                ]);
                $tag_ids[] = $new_tag->id;
            }
        }

        $question = Question::find($id)->update([
            'title' => $request['title'],
            'content' => $request['description'],
            'user_id' => Auth::id(),
        ]);

        Question::find($id)->tags()->sync($tag_ids);

        Alert::success('Success', 'Your Question Has been Updated');

        return redirect()->action(
            'QuestionController@show', ['question_id' => $id]
        );
    }

    public function destroy($id)
    {

        Question::find($id)->tags()->detach();


        Comment::where('question_id', $id)->delete();


        $answers = Answer::where('question_id', $id)->get();
        foreach ($answers as $answer) {
            Comment::where('answer_id', $answer->id)->delete();
        }

        Answer::where('question_id', $id)->delete();

        Question::destroy($id);
        Alert::success('Success', 'Your Question Has been Deleted');
        return redirect('/');
    }


    public function setBestAnswer($id)
    {

        $question = Question::find(Answer::find($id)->question_id);

        if ($question->best_answer_id != null) { 
            $oldAnswer = Answer::find($question->best_answer_id);
            $oldReputation = Reputation::where('user_id', $oldAnswer->user_id)->first();
            $point = $oldReputation->point;
            $point = $point - 15;

            
            $oldReputation->update([
                'point' => $point
            ]);
        }


        $question->update(['best_answer_id' => $id]);

        $answer = Answer::find($id);
        $reputation = Reputation::where('user_id', $answer->user_id)->first();
        $point = $reputation->point;
        $point = $point + 15;


        $reputation->update([
            'point' => $point
        ]);

        Alert::success('Success', 'This answer is set as the best answer!');

        return redirect()->action(
            'QuestionController@show',
            ['question_id' => Answer::find($id)->question_id]
        );
    }
}
