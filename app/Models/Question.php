<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasHeart;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory , HasHeart;

    protected $fillable = ['title', 'content', 'category_id', 'user_id'];

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::deleting(function($question){
            $question->hearts()->delete();
            $question->comments()->get()->each(function($comment){
                $comment->hearts()->delete();
                $comment->delete();
            });
            $question->answers()->get()->each(function($answer){
                $answer->hearts()->delete();
                $answer->comments()->get()->each(function($comment){
                    $comment->hearts()->delete();
                    $comment->delete();
                });
            });
        });
    }
}
