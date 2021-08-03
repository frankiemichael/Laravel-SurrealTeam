<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{$category['name']}}
        </h2>

    </x-slot>
<style>
    @media (max-width: 767.98px) {
  .w-25 {
    width: 100% !important;
  }
}

</style>
    <x-jet-validation-errors />
    @include('tremenheere.stock.actionbar')
    @if ($category->children->count() !== 0)
    <h4>Subcategories</h4>
    <div class="card-group mt-5 mb-5 " style="width: 100%">
        @foreach($category->children as $item)
        @if ($category->img_path !== NULL)
        <div class="rounded border border-info w-25 p-1 mx-2 card" style="background-image: url({{$item->img_path}});background-size: cover; background-repeat: no-repeat;width:200px;background-position: center;flex: 0% !important; cursor:pointer;" onclick="window.location='/tremenheere/stock/{{$item->id}}'">
        @else
        <div class="rounded border border-info w-25 p-1 mx-2 card" style="background-image: url(/images/products/categoryplaceholder.png);background-size: cover; background-repeat: no-repeat;height:100px;background-position: center;flex: 0% !important; cursor:pointer;"  onclick="window.location='/tremenheere/stock/{{$item->id}}'">
        @endif
        <div class="card-body ">
            <h5 class="card-title bg-primary p-1 text-light rounded" style="opacity:0.8;font-family:verdana; width:110px;font-size:14px;">{{$item->name}}</h5>          
        </div>
        </div>
        @endforeach
    </div>
    @endif
    @if ($category->tremenheerestock->count() !== 0)
    <h4>Products</h4>
        <table class="table table-striped">
        <thead>
            <tr height="100px">
            <th class="d-none d-lg-table-cell" scope="col" width="5%"></th>
            <th scope="col" width="25%"><a href="{{ route('tremenheere.stock.show', $id) }}?column=name&ascdesc={{$switch}}">Name</a></th>
            <th class="d-none d-lg-table-cell" scope="col" width="35%">Price</th>
            <th scope="col" width="5%">Unlimited Stock</th>
            <th scope="col" width="5%">Stock</th>
            </tr>
        </thead>
        <tbody>
        @foreach($category->tremenheerestock as $item)
            <tr>
            <th scope="row" class="d-none d-lg-table-cell" > 
            @if ($item->img_path !== NULL)
                <img class="card-img-top" src="{{asset($item->img_path)}}" alt="{{$item->name}}">
            @else
                <img class="card-img-top" src="{{asset('images/products/placeholder.jpeg')}}" alt="{{$item->name}}">
            @endif</th>
            <td><a href="{{route('tremenheere.stock.showproduct', $item['id'])}}">{{$item['name']}}</a></td>
            <td class="d-none d-lg-table-cell" >
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">£</span>
                </div>
                <input style="width:100px;" id="{{$item->id}}" data-type="product" name="price" type="text" placeholder="{{$item->price}}" class="price form-control">
            </div>
            </td>
            @if($item->stock === -1)
            <td style="align-items:center;"><input id="{{$item->id}}" type="checkbox" class="unlimitedStock" style="margin:auto;width:70%" checked></td>
            @else
            <td style="align-items:center;"><input id="{{$item->id}}" type="checkbox" class="unlimitedStock" style="margin:auto;width:70%"></td>
            @endif

            <td>
                @if($item->stock === -1)
                <input id="{{$item->id}}" data-type="product" type="text" min="0" name="stock" value="∞" class="stock form-control" disabled />
                @else
                <input id="{{$item->id}}" data-type="product" type="number" min="0" name="stock" value="{{ $item->stock }}" class="stock form-control" />
                @endif
            </td>
            
            </tr>
        @endforeach
        </tbody>
        </table>
        @endif
        <script type="text/javascript">
        $(document).ready(function(){
            $('.stock').on('change', function(){
                var _token = $('meta[name="csrf-token"]').attr('content')
                var id = $(this).attr('id')
                var url = "{{route('tremenheere.stock.update', ':id')}}"
                url = url.replace(':id', id)
                var type = $(this).attr('data-type')
                var stock = $(this).val()
                $.ajax({
                    url: url,
                    method: "PATCH",
                    data:{_token:_token, type:type, stock:stock},
                    success: function(result){
                        console.log(result)
                        if($('.flashalert').is(':animated')){
                            return false;
                        }else{
                        $('.flashalert').attr('hidden', false);
                        $('.flashalert').find('span').text(result)
                        $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                        }

                    },
                    error: function(error){
                        console.log(error)
                    }

                })
 
            })
            $('.unlimitedStock').change(function(){
                var input = $(this).parent().next().find('input')
                if($('.unlimitedStock:checked').length > 0){
                    $(input).attr('disabled', true)
                    $(input).attr('type', 'text')
                    $(input).val('∞')
                    var _token = $('meta[name="csrf-token"]').attr('content')
                    var id = $(this).attr('id')
                    var url = "{{route('tremenheere.stock.update', ':id')}}"
                    url = url.replace(':id', id)
                    var type = "product"
                    var stock = -1
                    $.ajax({
                        url: url,
                        method: "PATCH",
                        data:{_token:_token, type:type, stock:stock},
                        success: function(result){
                            if($('.flashalert').is(':animated')){
                                return false;
                            }else{
                            $('.flashalert').attr('hidden', false);
                            $('.flashalert').find('span').text(result)
                            $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                            }

                        },
                        error: function(error){
                            console.log(error)
                        }

                    })

                }else{
                    $(input).attr('disabled', false)
                    $(input).attr('type', 'number')
                    $(input).val(0)
                }
            })
            $('.price').on('change', function(){
                var _token = $('meta[name="csrf-token"]').attr('content')
                var id = $(this).attr('id')
                var url = "{{route('tremenheere.stock.update', ':id')}}"
                url = url.replace(':id', id)
                var type = $(this).attr('data-type')
                var price = $(this).val()
                $.ajax({
                    url: url,
                    method: "PATCH",
                    data:{_token:_token, type:type, price:price},
                    success: function(result){
                        console.log(result)
                        if($('.flashalert').is(':animated')){
                            return false;
                        }else{
                        $('.flashalert').attr('hidden', false);
                        $('.flashalert').find('span').text(result)
                        $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                        }

                    },
                    error: function(error){
                        console.log(error)
                    }

                })

            })
        })
        
        </script>
</x-app-layout>