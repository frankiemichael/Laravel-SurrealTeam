<div class="second-nav float-right" style="width:auto !important;" >
    <div class="input-group">
    @if(Route::current()->getName() == 'training.edit')
        <form action="{{ route('training.delete', $course) }}"><button type="submit" class="btn btn-danger">Delete Course</button></form>
    @endif
        <a href="{{ route('training.create') }}" class="btn btn-success">+</a>
    </div>

</div>