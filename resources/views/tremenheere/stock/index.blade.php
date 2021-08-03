<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Tremenheere Stock Management') }}
        </h2>

    </x-slot>
    <x-jet-validation-errors />
    @include('tremenheere.stock.actionbar')
    <table class="productlisthead table table-striped" hidden>
    <thead>
        <tr height="100px">
        <th scope="col" width="5%"></th>
        <th scope="col" width="35%">Name</th>
        <th scope="col" width="25%" style="text-align:center;">Price</th>
        <th scope="col" width="5%" style="text-align:center;">Stock</th>
        </tr>
    </thead>
    <tbody id="productlist">

    </tbody>
    </table>

    <table class="categories table table-striped">
        <thead>
            <tr height="100px">
            <th scope="col" width="5%"></th>
            <th scope="col" width="85%"><a href="{{ route('tremenheere.stock.index') }}?column=name&ascdesc={{$switch}}">Category</a></th>
            <th scope="col" width="5%">Subcategories</th>
            <th scope="col" width="5%">Items</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($categories as $item)
            <tr>
            <th scope="row">
            @if ($item->img_path !== NULL)
            <img class="card-img-top" src="{{$item->img_path}}" alt="{{$item->name}}">
            @else
            <img class="card-img-top" src="/images/products/categoryplaceholder.png" alt="{{$item->name}}">
            @endif
            </th>
            <td><a href="{{route('tremenheere.stock.show', $item['id'])}}">{{$item['name']}}</a></td>
            <td>{{$item->children->count()}}</td>
            <td>{{$item->tremenheerestock->count()}}</td>
            </tr>
        @endforeach
        </tbody>
        </table>

        <script>
            function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                    callback.apply(context, args);
                    }, ms || 0);
                };
                }
            $('#search').keyup(delay(function(e){
                var url = $(this).attr('data-url')
                var search = $(this).val()
                var _token = $('meta[name="csrf-token"').attr('content')
                if(!search){
                    $('.productlisthead').attr('hidden', true)
                    $('.categories').show()
                }else{
                    $.ajax({
                    url:url,
                    method:"GET",
                    data:{'search': search, '_token': _token},
                    success:function(result){
                        $('.productlisthead').attr('hidden', false)
                        console.log(result.data)
                        $('.categories').hide()
                        $('#productlist').empty()
                        $.each(result.data, function(i,val){
                            var product = val
                            if(product.parent != 0){
                            if(product.parent.name.indexOf('Large') != -1){
                                $('#productlist').append('<tr><th scope="row"></th><td><a href="/tremenheere/stock/product/'+val.id+'">'+val.name+'</a><span class="text-secondary"> - large</span></td><td><div class="input-group input-group-lg mb-3" style="width:150px;"><div class="input-group-prepend"><span class="input-group-text">£</span></div><input class="form-control" step=".01" type="number" name="price" placeholder="'+val.price+'"></input></div></td><td><input class="form-control" style="width:50px;float:right;" min="0" placeholder="'+val.stock+'" name="stock" type="number"></td></tr>')
                            }else{
                                $('#productlist').append('<tr><th scope="row"></th><td><a href="/tremenheere/stock/product/'+val.id+'">'+val.name+'</a></td><td><div class="input-group input-group-lg mb-3" style="width:150px;"><div class="input-group-prepend"><span class="input-group-text">£</span></div><input class="form-control" step=".01" type="number" name="price" placeholder="'+val.price+'"></input></div></td><td><input style="width:50px;float:right;" min="0" class="form-control" placeholder="'+val.stock+'" name="stock" type="number"></td></tr>')
                            }
                            }
                        })
                    },
                    error:function(error){
                    console.log(error)

                    }
                })
                }

            }, 500));

        </script>
</x-app-layout>