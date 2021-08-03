<div class="second-nav float-right" style="width:auto !important;" >
        <div class="input-group">
        @if(Route::current()->getName() == 'tremenheere.stock.index')
        <div class="form-outline">
        <input class="form-control" id="search" data-url="{{route('tremenheere.labels.index')}}" placeholder="Search" aria-label="Search">
        </div>
        @endif
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                +
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{route('tremenheere.stock.create')}}">Product</a>
                <a class="dropdown-item" href="{{route('tremenheere.stock.createcategory')}}">Category</a>
            </div>
        </div>
    </div>
</div>