<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Checkout') }}
        </h2>
    </x-slot>  
    <x-jet-validation-errors />
    <a href="{{ route('tremenheere.pos.index') }}" class="btn btn-warning">Go back</a>
    <table id="cart" class="table table-hover table-condensed">
        <thead>
        <tr>
            <th style="width:50%">Product</th>
            <th style="width:10%">Price</th>
            <th style="width:8%">Quantity</th>
            <th style="width:22%" class="text-center">Subtotal</th>
            <th style="width:10%"></th>
        </tr>
        </thead>
        <tbody>
        <?php $total = 0 ?>
        @if(session('cart'))
            @foreach(session('cart') as $id => $products)
            @foreach ($products as $details)
                <?php $total += $details['price'] * $details['quantity'] ?>
                <tr>
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"><img src="{{ $details['photo'] }}" width="100" height="100" class="img-responsive"/></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">{{ $details['name'] }}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">£{{ $details['price'] }}</td>
                    <td class="quantitytd" data-th="Quantity">{{ $details['quantity'] }}</td>
                    <td data-th="Subtotal" class="text-center">£{{ $details['price'] * $details['quantity'] }}</td>
                    <td class="actions" data-th="">

                        <form action="{{route('tremenheere.pos.removefromcart')}}" method="post">
                        @csrf
                        @method('delete')
                        <input type="text" name='id' value="{{$id}}"hidden>                        
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
        <form action="{{ route('tremenheere.pos.placeorder')}}" method="post">
        @csrf
        <td>
            <textarea type="text" placeholder="Order notes..." name="notes"></textarea> 
            <input type="text" value="{{Auth::user()->name}}" name="creator" hidden>
            <input type="number" id="grandTotal" value="{{$total}}" name="total" hidden>
            <select id="paymentMethod" name="payment_method">
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
            </select>
            <input type="number" name="handled" id="handled">
            <input type="number" id="changecalculator" readonly>
            <input id="items" type="text" name="items" value="
            @if(session('cart'))
            @foreach(session('cart') as $id => $details)
                {{$id}}
            @endforeach
            @endif
            ">
            <button type="submit">Complete order</button>
        </td>
        </tr>
        </form>
        </tfoot>
    </table>
    <script type="text/javascript">
        $('#paymentMethod').on('change', function(){
            if($(this).val() == 'Cash'){
                $('#handled').attr('hidden', false)
            }else{
                $('#handled').attr('hidden', true)
            }
        })
        $('#handled').on('change', function(){
            var grandTotal = $('#grandTotal').attr('value')
            console.log(grandTotal)
            var change = ($('#handled').val() - grandTotal)
            console.log(change)
            $('#changecalculator').attr('value', change)
        })
        console.log($('#items').val())
    </script>
</x-app-layout>