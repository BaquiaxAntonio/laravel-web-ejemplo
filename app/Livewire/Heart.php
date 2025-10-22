<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class Heart extends Component
{

    public Model $heartable;

    public function toggle(){

        if($this->heartable->isHearted()){
            $this->heartable->unheart();
        }else{
        $this->heartable->heart();
        }

    }

    public function render()
    {
        return view('livewire.heart');
    }

}