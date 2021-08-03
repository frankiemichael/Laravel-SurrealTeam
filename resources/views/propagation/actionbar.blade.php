<div class="second-nav float-right" style="width:auto !important;" >
    <div class="input-group">
        <?php
        if(Route::current()->getName() === 'propagation.trereife'){
            $source = 'Trereife';
            $route = route('propagation.trereife');
        }else if(Route::current()->getName() === 'propagation.trewidden'){
            $source = 'Trewidden';
            $route = route('propagation.trewidden');
        }
        ?>
        <div class="form-outline">
            <input class="form-control" id="search" data-url="{{$route}}" placeholder="Search" aria-label="Search">
        </div>
        <a href="{{ route('propagation.create', $source) }}" class="btn btn-success">+</a>
    </div>

</div>