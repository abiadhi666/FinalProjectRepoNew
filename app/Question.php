<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the user that owns the question.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'user_id', 'best_answer_id'];

    /**
     * The tags that belong to the question.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * Get the comments for the question post.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get the answers for the question post.
     */
    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    /**
     * Get the votes for the question post.
     */
    public function votes()
    {
        return $this->hasMany('App\Vote');
    }
}
