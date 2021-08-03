<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
class PostViewer extends Component
{
    public function render()
    {
        return view('livewire.post-viewer',[
            'posts' => Post::orderBy('updated_at', 'DESC')->get()
        ]);
    }
}
