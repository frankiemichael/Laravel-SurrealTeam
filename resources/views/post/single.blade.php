<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Posts') }}
        </h2>
    </x-slot>
    <x-jet-validation-errors />

    <style>
    .display-comment .display-comment {
        margin-left: 40px
    }
    </style>
<div class="container">
    <div class="row justify-content-center">
    
        <div class="col-md-8">
        
            <div class="card">
            
                <div class="card-body">
                <form action="{{route('posts.delete', $post->id)}}" method="POST">@csrf <button type="submit" class="btn btn-danger">Delete</button></form><br>
                    <h1>{{ $post->title }}</h1>
                    <p>{{ $post->description }}</p>
                    @if ($post->type->count() === 0)

                    @else
                    <form action="{{route('posts.vote', $post->id)}}" method="POST">
                    @csrf
                    <div class="form-group bg-info px-2 pt-2 pb-2">
                            <label for="exampleFormControlSelect1">Options</label>
                            <select name="vote" class="form-control" id="exampleFormControlSelect1">
                            @foreach($post->type as $option)
                            <option value="{{$option->id}}">{{$option->option}}</option>
                            @endforeach
                            </select>
                            <br>
                            <button class="btn btn-light" type="submit">Vote</button>
                        </div>
                           
                    @endif
                </div>
                </form>
               <div class="card-body border-top">
                <h5>Comments</h5>
            
                @include('post.partials.replies', ['comments' => $post->comments, 'post_id' => $post->id])

                <hr />
               </div>

               <div class="card-body">
                <h5>Leave a comment</h5>
                <form method="post" action="{{ route('comment.add') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="comment" class="form-control" />
                        <input type="hidden" name="post_id" value="{{ $post->id }}" />
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" value="Add Comment" />
                    </div>
                </form>
               </div>

            </div>
        </div>
    </div>
</div>
</x-app-layout>