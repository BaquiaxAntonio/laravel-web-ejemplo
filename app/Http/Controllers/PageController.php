<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class PageController extends Controller
{
    public function index(){
        $questions = Question::with('category','user')->get();
        return view('pages.home',[
            'questions' => $questions,
        ]);
    }
}
