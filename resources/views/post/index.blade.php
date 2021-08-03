<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Posts') }}
        </h2>
    </x-slot>
    <x-jet-validation-errors />

    <div class="container" width="80%">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">New Post</a>

        <div id="containerdiv" class="mb-2">
            </div>
            @foreach($posts as $post)
            <div class="card ml-auto mr-auto w-100 rounded border border-primary mb-2">
            <div class="postdate card-header float-right">
            {{$post->updated_at}}
            </div>
            <div class="card-body">
            <blockquote class="blockquote mb-0">
            <a href="{{route('posts.show', $post->slug)}}">{{$post->title}}</a>
            <p>{{$post->description}}</p>
            <footer class="blockquote-footer">{{$post->users->name}}</footer>
            </blockquote>
            <form class="likedislike" data-id="{{$post->id}}">
                @csrf
                <a class="likebutton" id="like{{$post->id}}" href="" data="unliked"><i class="far fa-thumbs-up"></i> 
                <span> {{$post->likes->count()}} </span>
                </a>
                <a class="dislikebutton" id="dislike{{$post->id}}" href="" data="undisliked"><i class="far fa-thumbs-down"></i> 
                <span>{{$post->dislikes->count()}} </span>
                </a>
            </form>
            <a href="{{route('posts.show', $post->slug)}}">Show comments({{$post->comments->count()}})</a>
            </div>
            </div>
            
            @endforeach
            {{$posts->links()}}
</div>
<script type="text/javascript">
    var id = "{{Auth::user()->id}}"
    var posts = {!! str_replace("'", "\'", json_encode($posts)) !!};
    $.each(posts.data, function(i,val){
        $.each(val.likes, function(i,val2){
            var postid = val2.pivot.post_id
            if(val2.id == id){
                $(document).find('a[id="like'+postid+'"]').css({'color': 'green', 'font-weight': 'bold'})
                $(document).find('a[id="like'+postid+'"]').attr("data", "liked")
            }else{
                $(document).find('a[id="like'+postid+'"]').attr("data", "unliked")

            }
        })
        $.each(val.dislikes, function(i,val2){
            var postid = val2.pivot.post_id
            if(val2.id == id){
                $(document).find('a[id="dislike'+postid+'"]').css({'color': 'red', 'font-weight': 'bold'})
                $(document).find('a[id="dislike'+postid+'"]').attr("data", "disliked")

            }else{
                $(document).find('a[id="dislike'+postid+'"]').attr("data", "undisliked")
            }
        })

    })
    $('.likedislike').find('a[class="likebutton"]').on('click', function(e){
        e.preventDefault()
        var button = $(this)
        var postid = $(this).parent('form').attr('data-id')
        var _token = $(this).siblings('input[name="_token"]').val()
        if($(this).attr('data') == 'liked'){
            var route = "{{route('post.unlike', ':id')}}"
            route = route.replace(':id', postid)

            $.ajax({
                url:route,
                method:"PATCH",
                data:{_token:_token},
                success: function(result){
                    $(button).css({'color': '#3490dc', 'font-weight': 'normal'})
                    $(button).attr("data", "unliked")
                    $(button).find('span').text(parseInt($(button).find('span').text())-1) 
                    
                },
                error: function(error){
                    console.log(error)
                }
            })
        }else if($(this).attr('data') == 'unliked'){
            
            var route = "{{route('post.like', ':id')}}"
            route = route.replace(':id', postid)
            $.ajax({
                url:route,
                method:"PATCH",
                data:{_token:_token},
                success: function(result){
                    console.log(result)

                    $(button).css({'color': 'green', 'font-weight': 'bold'})
                    $(button).attr("data", "liked")
                    $(button).find('span').text(parseInt($(button).find('span').text())+1)                 },
                error: function(error){
                    console.log(error)
                }
            })
        }
    })
    $('.likedislike').find('a[class="dislikebutton"]').on('click', function(e){
        e.preventDefault()
        var button = $(this)
        var postid = $(this).parent('form').attr('data-id')
        var _token = $(this).siblings('input[name="_token"]').val()
        if($(this).attr('data') == 'disliked'){
            var route = "{{route('post.undislike', ':id')}}"
            route = route.replace(':id', postid)

            $.ajax({
                url:route,
                method:"PATCH",
                data:{_token:_token},
                success: function(result){
                    
                    $(button).css({'color': '#3490dc', 'font-weight': 'normal'})
                    $(button).attr("data", "undisliked")
                    $(button).find('span').text(parseInt($(button).find('span').text())-1)                 },
                error: function(error){
                    console.log(error)
                }
            })
        }else if($(this).attr('data') == 'undisliked'){
            var route = "{{route('post.dislike', ':id')}}"
            route = route.replace(':id', postid)
            $.ajax({
                url:route,
                method:"PATCH",
                data:{_token:_token},
                success: function(result){
                

                    $(button).css({'color': 'red', 'font-weight': 'bold'})
                    $(button).attr("data", "disliked")
                    $(button).find('span').text(parseInt($(button).find('span').text())+1)                 },
                error: function(error){
                    console.log(error)
                }
            })
        }
    })

</script>
</x-app-layout>