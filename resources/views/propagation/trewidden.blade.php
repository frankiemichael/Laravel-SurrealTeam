<x-app-layout>
<x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Trewidden Propagation Database') }}
        </h2>
        <link href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css" rel="stylesheet">
        <link href="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css" rel="stylesheet">

        <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
        <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/fixed-columns/bootstrap-table-fixed-columns.min.js"></script>
    </x-slot>
    <x-jet-validation-errors />
    @include('propagation.actionbar')
    <div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <th style="min-width:300px;">Name</th>
            <th width="5%">Location</th>
            <th width="5%">Quantity</th>
            <th style="min-width:400px;">Notes</th>
            <th width="5%"></th>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr data-id="{{$item->id}}" data-site="{{Route::current()->getName()}}">
                <th class="data-fixed-columns"><input type="text" class="form-control" name="name" value="{{$item->name}}"></th>
                <td><input type="text" class="form-control" name="location" value="{{$item->location}}"></td>
                <td><input type="text" class="form-control" name="quantity" value="{{$item->quantity}}"></td>
                <td><input type="text" class="form-control" name="notes" value="{{$item->notes}}"></td>
                <td><button class="btn deletePlant" data-id="{{$item->id}}"><i style="color:red;" class="fa fa-trash" aria-hidden="true"></i></button></td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
    <script>
        function deletePlant(){
            $('.deletePlant').click(function(e){
            var id = $(this).attr('data-id')
            e.preventDefault()
            confirm('Are you sure?')
            $.ajax({
                url: "{{route('propagation.delete')}}",
                method:"POST",
                data:{_method:'DELETE', _token:$('meta[name="csrf-token"]').attr('content'), id:id},
                success:function(result){
                    if($('.flashalert').is(':animated')){
                            return false;
                        }else{
                        $('.flashalert').attr('hidden', false);
                        $('.flashalert').find('span').text(result)
                        $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                        }
                    $('tr[data-id="'+id+'"').remove()
                },
                error:function(error){
                    
                }
            })
        })
        
        }
        deletePlant()
        $('table').find('input').keyup(function(){
            var id = $(this).parent().parent().attr('data-id')
            var column = $(this).attr('name')
            var data = $(this).val()
            var site = $(this).parent().parent().attr('data-site')
            $.ajax({
                url: "{{route('propagation.update')}}",
                method:"POST",
                data: {site:site, id:id, column:column, data:data, _token:$('meta[name="csrf-token"]').attr('content'), _method:'PUT'},
                success:function(result){
                    if($('.flashalert').is(':animated')){
                            return false;
                        }else{
                        $('.flashalert').attr('hidden', false);
                        $('.flashalert').find('span').text(result)
                        $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                        }
                },
                error:function(error){
                    console.log(error)
                }

            })
        })
        $('input[type=radio]').change(function() {
            var method = $(this).attr('data-method')
            var id = $(this).parent().parent().parent().attr('data-id')
            if(method == 'air'){
                $('table').find('input[name="grit'+id+'"]').prop('checked', false)
            }else{
                $('table').find('input[name="air'+id+'"]').prop('checked', false)
            }
            $.ajax({
                    url: "{{route('propagation.update')}}",
                    method:"POST",
                    data: {id:id, column:"method", data:method, _token:$('meta[name="csrf-token"]').attr('content'), _method:'PUT'},
                    success:function(result){
                        if($('.flashalert').is(':animated')){
                            return false;
                        }else{
                        $('.flashalert').attr('hidden', false);
                        $('.flashalert').find('span').text(result)
                        $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                        }
                    },
                    error:function(error){
                        console.log(error)
                    }

                })
        })
        $('.cuttings').change(function(){
            var id = $(this).parent().parent().parent().attr('data-id')
            if($(this).attr('checked')){
                var cuttings = 1
            }else{
                var cuttings = 0
            }
            $.ajax({
                    url: "{{route('propagation.update')}}",
                    method:"POST",
                    data: {id:id, column:"cuttings", data:cuttings, _token:$('meta[name="csrf-token"]').attr('content'), _method:'PUT'},
                    success:function(result){
                        if($('.flashalert').is(':animated')){
                            return false;
                        }else{
                        $('.flashalert').attr('hidden', false);
                        $('.flashalert').find('span').text(result)
                        $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                        }
                    },
                    error:function(error){
                        console.log(error)
                    }

                })

        })
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
            $.ajax({
                url:url,
                method:"GET",
                data:{'search': search, '_token': _token},
                success:function(result){
                    console.log(result)
                    $('tbody').empty()
                    $.each(result, function(i,plant){
                        $('tbody').append('<tr data-id="'+plant.id+'" data-site="{{Route::current()->getName()}}"><th class="data-fixed-columns"><input type="text" class="form-control" name="name" value="'+plant.name+'"></th><td><input type="text" class="form-control" name="location" value="'+plant.location+'"></td><td><input type="text" class="form-control" name="quantity" value="'+plant.quantity+'"></td>' + '<td><input type="text" class="form-control" name="notes" value="'+plant.notes+'"></td><td><button class="btn deletePlant" data-id="'+plant.id+'"><i style="color:red;" class="fa fa-trash" aria-hidden="true"></i></button></td></tr>')
                        deletePlant()
                        $('.deletePlant').click(function(){
                            $('#search').val('')
                        $.ajax({
                            url:url,
                            method:"GET",
                            data:{'search': '', '_token': _token},
                            success:function(result){
                                $('tbody').empty()
                                $.each(result, function(i,plant){
                                    $('tbody').append('<tr data-id="'+plant.id+'" data-site="{{Route::current()->getName()}}"><th class="data-fixed-columns"><input type="text" class="form-control" name="name" value="'+plant.name+'"></th><td><input type="text" class="form-control" name="location" value="'+plant.location+'"></td><td><input type="text" class="form-control" name="quantity" value="'+plant.quantity+'"></td>' + '<td><input type="text" class="form-control" name="notes" value="'+plant.notes+'"></td><td><button class="btn deletePlant" data-id="'+plant.id+'"><i style="color:red;" class="fa fa-trash" aria-hidden="true"></i></button></td></tr>')
                                    deletePlant()
                                })
                            },
                        })

                        })
                    })
                },
                error:function(error){
                console.log(error)
                }
            })
        }, 500));

    </script>
</x-app-layout>