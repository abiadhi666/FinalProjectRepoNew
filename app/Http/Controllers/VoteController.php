<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vote;
use App\User;
use App\Answer;
use App\Question;
use App\Reputation;
use RealRashid\SweetAlert\Facades\Alert;
use Auth;

class VoteController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function upvoteQ($id)
    {
        if (Auth::id() == Question::find($id)->user_id) {
            Alert::warning('Cannot Vote', 'You cannot upvote or downvote your own question!');
        } else {
            $isVoteExist = Vote::where('question_id', $id)->where('user_id', Auth::id())->first();
            if ($isVoteExist == null) {
                $vote = Vote::create([
                    'upvote' => 1,
                    'downvote' => 0,
                    'user_id' => Auth::id(),
                    'answer_id' => null,
                    'question_id' => $id
                ]);

                $question = Question::find($id);
                $user = User::find($question->user_id);
                $reputation = Reputation::where('user_id', $user->id)->first();
                $point = $reputation->point;

                $point = $point + 10;

                $reputation->update([
                    'point' => $point
                ]);
            }
            else {
                if ($isVoteExist->downvote == 1) { 
                    $isVoteExist->update([
                        'upvote' => 1,
                        'downvote' => 0
                    ]);

                    $question = Question::find($id);
                    $reputation = Reputation::where('user_id', $question->user_id)->first();
                    $point = $reputation->point;
                    $point = $point + 11;

                    // last, we update the reputation.
                    $reputation->update([
                        'point' => $point
                    ]);
                }
            }
        }

        return redirect()->action(
            'QuestionController@show',
            ['question_id' => $id]
        );
    }

    /**
     * Downvote question
     *
     * @return \Illuminate\Http\Response
     */
    public function downvoteQ($id)
    {
        
        if (Auth::id() == Question::find($id)->user_id) {
            Alert::warning('Cannot Vote', 'You cannot upvote or downvote your own question!');
        } else {
            
            $voterReputation = Reputation::where('user_id', Auth::id())->first();

            
            if ($voterReputation->point >= 15) {
                // check if vote already exist or not
                $isVoteExist = Vote::where('question_id', $id)->where('user_id', Auth::id())->first();

                
                if ($isVoteExist == null) {
                    
                    $vote = Vote::create([
                        'upvote' => 0,
                        'downvote' => 1,
                        'user_id' => Auth::id(),
                        'answer_id' => null,
                        'question_id' => $id
                    ]);

                    
                    $question = Question::find($id);
                    $user = User::find($question->user_id);

                    
                    $reputation = Reputation::where('user_id', $user->id)->first();
                    $point = $reputation->point;

                    
                    $point = $point - 1;

                    
                    $reputation->update([
                        'point' => $point
                    ]);
                }
                
                else {
                    
                    if ($isVoteExist->upvote == 1) { 
                        
                        $isVoteExist->update([
                            'upvote' => 0,
                            'downvote' => 1
                        ]);

                        $question = Question::find($id);
                        $reputation = Reputation::where('user_id', $question->user_id)->first();
                        $point = $reputation->point;
                        $point = $point - 11;

                        $reputation->update([
                            'point' => $point
                        ]);
                    }
                }
            }
            else {
                Alert::warning('Cannot Downvote', 'Your reputation must be 15 or greater to downvote!');
            }
        }

        return redirect()->action(
            'QuestionController@show',
            ['question_id' => $id]
        );
    }

    public function upvoteA($id)
    {
        if (Auth::id() == Answer::find($id)->user_id) {
            Alert::warning('Cannot Vote', 'You cannot upvote or downvote your own answer!');
        } else {
            $isVoteExist = Vote::where('answer_id', $id)->where('user_id', Auth::id())->first();

            if ($isVoteExist == null) {
                $vote = Vote::create([
                    'upvote' => 1,
                    'downvote' => 0,
                    'user_id' => Auth::id(),
                    'answer_id' => $id,
                    'question_id' => null
                ]);

                $answer = Answer::find($id);
                $user = User::find($answer->user_id);

                $reputation = Reputation::where('user_id', $user->id)->first();
                $point = $reputation->point;

                $point = $point + 10;

                $reputation->update([
                    'point' => $point
                ]);
            }
            else {
                if ($isVoteExist->downvote == 1) { 
                    $isVoteExist->update([
                        'upvote' => 1,
                        'downvote' => 0
                    ]);

                    $answer = Answer::find($id);
                    $reputation = Reputation::where('user_id', $answer->user_id)->first();
                    $point = $reputation->point;
                    $point = $point + 11;

                    $reputation->update([
                        'point' => $point
                    ]);
                }
            }
        }

        return redirect()->action(
            'QuestionController@show',
            ['question_id' => Answer::find($id)->question_id]
        );
    }

    public function downvoteA($id)
    {
        if (Auth::id() == Answer::find($id)->user_id) {
            Alert::warning('Cannot Vote', 'You cannot upvote or downvote your own answer!');
        } else {
            $voterReputation = Reputation::where('user_id', Auth::id())->first();
            if ($voterReputation->point >= 15) {
                $isVoteExist = Vote::where('answer_id', $id)->where('user_id', Auth::id())->first();
                if ($isVoteExist == null) {
                    $vote = Vote::create([
                        'upvote' => 0,
                        'downvote' => 1,
                        'user_id' => Auth::id(),
                        'answer_id' => $id,
                        'question_id' => null
                    ]);

                    $answer = Answer::find($id);
                    $user = User::find($answer->user_id);

                    $reputation = Reputation::where('user_id', $user->id)->first();
                    $point = $reputation->point;

                    $point = $point - 1;

                    $reputation->update([
                        'point' => $point
                    ]);
                }
                else {
                    if ($isVoteExist->upvote == 1) { 
                        $isVoteExist->update([
                            'upvote' => 0,
                            'downvote' => 1
                        ]);

                        $answer = Answer::find($id);
                        $reputation = Reputation::where('user_id', $answer->user_id)->first();
                        $point = $reputation->point;
                        $point = $point - 11;


                        $reputation->update([
                            'point' => $point
                        ]);
                    }
                }
            }

            else {
                Alert::warning('Cannot Downvote', 'Your reputation must be 15 or greater to downvote!');
            }
        }

        return redirect()->action(
            'QuestionController@show',
            ['question_id' => Answer::find($id)->question_id]
        );
    }
}
