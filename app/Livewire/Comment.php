<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class Comment extends Component
{

    public Model $commentable;
    public bool $showForm = false;
    public string $content = '';
    public $comments;

    public function mount()
    {
        $this->comments = $this->commentable->comments()->with('user')->get();
    }

    public function add(){
        $this->validate([
            'content' => 'required|string|max:255',
        ]);

        $this->commentable->comments()->create([
            'content' => $this->content,
            'user_id' => 20, //TODO: Cambiar por el usuario autenticado
        ]);

        $this->comments = $this->commentable->comments()->with('user')->get();

        $this->reset('content', 'showForm');


    }

    public function toggle(){   
        $this->showForm = !$this->showForm;
    }

    public function render()
    {
        return view('livewire.comment',
        [
            'commentable' => $this->commentable,
        ]
     );
    }
}
