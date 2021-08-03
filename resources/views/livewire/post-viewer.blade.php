@foreach($posts as $post)
<div class="card w-100">
<div class="card-header">
{{$post->updated_at}}
</div>
<div class="card-body">
<blockquote class="blockquote mb-0">
<p>{{$post->comment}}</p>
<footer class="blockquote-footer">{{$post->creator}}</footer>
</blockquote>
</div>
</div>
</div>
@endforeach
