<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Label App') }}
        </h2>

    </x-slot>
    <x-jet-validation-errors />
    @include('tremenheere.labels.actionbar')
    @if($order !== [])
    <table id="order" class="table table-hover table-condensed">
        <thead>
        <tr>
            <th style="width:50%">Product</th>
            <th style="width:8%">Quantity</th>
            <th style="width:10%"></th>
        </tr>
        </thead>
        <tbody>
        @if(session('order'))

            @foreach(session('order') as $id => $products)
            @foreach ($products as $details)
                <tr>
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-9">
                                <span class="nomargin">{{ $details['name'] }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="quantitytd" data-th="Quantity">
                    <form action="{{route('tremenheere.labels.update')}}" method="post">
                        @csrf
                        <input type="text" name='id' data-id="{{$details['productid']}}" value="{{$details['id']}}"hidden>
                        <input type="number" min="0"name="quantity" value="{{ $details['quantity'] }}" class="updatequantity form-control quantity" />
                        </form>
                    </td>
                    <td class="actions">

                        <form id="submitform" action="{{route('tremenheere.labels.delete')}}" method="post">
                        @csrf
                        @method('delete')
                        <input type="text" name='id' value="{{$details['id']}}"hidden> 
                        @if(isset($details['productid']))                       
                        <input type="text" name='productid' value="{{$details['productid']}}"hidden>                        
                        @endif
                        <button type="submit" class="btn btn-info btn-sm update-cart"></i>Delete</button>

                        
                        </form>
                    </td>
                </tr>
            @endforeach
            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr>
          <form action="{{route('tremenheere.labels.completeorder')}}" method="post">
          @csrf
            <td>Add a deadline: <input name="deadline" type="date"></td>
            <td><button type="submit" class="paymentbutton btn btn-primary">Complete</a></td>
            </form>
        </tr>
        </tfoot>
    </table>
    @endif
    <input class="form-control mr-sm-2 my-2" id="search" data-url="{{route('tremenheere.labels.index')}}" placeholder="Search" aria-label="Search">
    <div style="min-height:500px;">
    <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col"></th>
      <th scope="col" width="60%">Name</th>
      <th scope="col" style="text-align:right;">Stock</th>
      <th scope="col" style="text-align:right;">Quantity</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody id="productlist">
  @foreach($stock as $item)
    <tr>
      <th scope="row"></th>
      
      <td>{{$item->name}} 
        @if($item->parent)
          @if(str_contains($item->parent->name, 'Large'))
          <span class="text-secondary"> - large</span> 
          @endif
        @endif
      </td>
      <td style="text-align:right;">
        @if($item->stock === -1)
          In stock
        @else
          {{$item->stock}}
        @endif
      </td>
      <td><input style="width:50px;float:right;" min="1" value="1" name="quantity" type="number"></td>
      <td><button class="btn btn-info addtoorder" data-id="{{$item->id}}" >Add to order</button></td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
<div id="pagelinks">
  {{$stock->links()}}
</div>
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
function addtocart(){
  $('.addtoorder').on('click', function(){
  var id = $(this).attr('data-id')
  var variant_id = $(this).parentsUntil('tr').prev('td').prev('td').find('select').val()
  var quantity = $(this).parent('td').prev('td').find('input').val()
  var route = "{{route('tremenheere.labels.update')}}"
  var _token = $('meta[name="csrf-token"]').attr('content')
  $.ajax({
    url: route,
    method: "POST",
    dataType: "JSON",
    data: {product_id:id, quantity: quantity, _token: _token, variant_id: variant_id },
    success: function(result){
      console.log(result)
      location.reload();
    },
    error: function(error){
      console.log(error)
      if($('.flashalert').is(':animated')){
                return false;
            }else{
            $('.flashalert').attr('hidden', false);
            $('.flashalert').find('span').text('Added to request.')
            $('.flashalert').fadeIn().delay('700').fadeOut('slow')
            }
      location.reload();
      
    }
  })
})
}
$('#search').keyup(delay(function(e){
  var url = $(this).attr('data-url')
  var search = $(this).val()
  var _token = $('meta[name="csrf-token"').attr('content')
  console.log(search)
  $.ajax({
    url:url,
    method:"GET",
    data:{'search': search, '_token': _token},
    success:function(result){
      if(!search){
        $('#pagelinks').attr('hidden', false)
      }else{
        $('#pagelinks').attr('hidden', true)
      }
      console.log(result.data)
      $('#productlist').empty()
      $.each(result.data, function(i,val){
        var product = val
        if(product.parent != 0){
          if(product.parent.name.indexOf('Large') != -1){
            $('#productlist').append('<tr><th scope="row"></th><td>'+val.name+'<span class="text-secondary"> - large</span></td><td><input style="width:50px;float:right;" min="1" value="1" name="quantity" type="number"></td><td><button class="btn btn-info addtoorder" data-id="'+val.id+'" >Add to order</button></td></tr>')
          }else{
            $('#productlist').append('<tr><th scope="row"></th><td>'+val.name+'</td><td><input style="width:50px;float:right;" min="1" value="1" name="quantity" type="number"></td><td><button class="btn btn-info addtoorder" data-id="'+val.id+'" >Add to order</button></td></tr>')
          }
        }
      })
      addtocart()
    },
    error:function(error){
      console.log(error)
    }
  })
}, 500));
addtocart()



$('.updatequantity').on('change', function(e){
    e.preventDefault()
    var currentinput = $(this)
    var _token = $("input[name='_token']").val();
    var quantity = $(this).val()
    var id = $(this).prev('input[name="id"]').val()
    var productid = $(this).prev('input[name="id"]').attr('data-id')

    $.ajax({
        url: "{{route('tremenheere.labels.orderupdate')}}",
        method: "PATCH",
        data: {_token:_token, id:id, quantity:quantity, productid:productid, _method:"PATCH"},
        success: function(data){
            console.log(data)
            if($('.flashalert').is(':animated')){
                return false;
            }else{
            $('.flashalert').attr('hidden', false);
            $('.flashalert').find('span').text(data)
            $('.flashalert').fadeIn().delay('700').fadeOut('slow')
            }
        },
        error:function(error){
          console.log(error)
          if($('.flashalert').is(':animated')){
                return false;
            }else{
            $('.flashalert').attr('hidden', false);
            $('.flashalert').find('span').text('Added to request.')
            $('.flashalert').fadeIn().delay('700').fadeOut('slow')
            }
          location.reload();
        }
    })


})


</script>
</x-app-layout>