<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function show(Question $question){

        $question->load('answers','category','user');

        return view('questions.show',[
            'question' => $question,
        ]);
    }

    public function create(){
        $categories = Category::all();
        return view('questions.create',[
            'categories' => $categories,
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $question = Question::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Pregunta creada exitosamente.');
    }

    public function edit(Question $question){
        $this->authorize('update', $question);
        $categories = Category::all();
        return view('questions.edit',[
            'question' => $question,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Question $question){
        $this->authorize('update', $question);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $question->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('question.show', $question)->with('success', 'Pregunta actualizada exitosamente.');
    }

    public function destroy(Question $question){
        $this->authorize('delete', $question);
        $question->delete();
        return redirect()->route('home');
    }
}

