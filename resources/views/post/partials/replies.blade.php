@foreach($comments as $comment)
<div class="display-comment">
    <strong>{{ $comment->users->name }}</strong>
    <p>{{ $comment->comment }}</p>
    <a href="" id="reply"></a>
    <form method="post" action="{{ route('reply.add') }}">
        @csrf
        <div class="form-group rounded border border-primary">
            <input type="text" name="comment" class="form-control" required />
            <input type="hidden" name="post_id" value="{{ $post_id }}" required />
            <input type="hidden" name="comment_id" value="{{ $comment->id }}" required />
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-sm btn-outline-danger py-0" style="font-size: 0.8em;" value="Reply" />
        </div>
    </form>
    @include('post.partials.replies', ['comments' => $comment->replies])
</div>
@endforeach 