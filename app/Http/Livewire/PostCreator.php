<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PostCreator extends Component
{
    public function render()
    {
        return view('livewire.post-creator');
    }
    
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        Post::create([
            'name' => $this->name,
            'description' => $this->description
        ]);
        $this->resetInput();
    }
}
