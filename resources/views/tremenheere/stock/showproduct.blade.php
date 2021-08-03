<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Product') }}
        </h2>

    </x-slot>

    <x-jet-validation-errors />
    <button id="deleteproduct" class="mb-2 float-right btn btn-danger">Delete</button>

        @if ($product->img_path !== NULL)
            <img class="card-img-top mx-auto mb-4" src="{{$product->img_path}}" alt="{{$product->name}}" style="display:block;width:400px;">
        @endif

    <form method="post" action="{{route('tremenheere.stock.update', $product->id)}}" enctype="multipart/form-data">
    @csrf
    @method('patch')
        <div class="form-group">
            <label for="name" class="mt-2">Name</label>
            <input class="form-control" id="name" type="text" name="name" value="{{$product->name}}">
        </div>
        <div class="form-group">
            <label for="category">Category (Current - {{$parent->name}})</label>
            <select class="form-control" id="category" name="parent_id">
                <option value="{{$product->parent_id}}">No change</option>

                <option value="0">None</option>
                @if($categories->count() === 0)
                <option value="" disabled>No categories available</option>
                @else
                @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
                @endif
                <option value="categorycreate" class="createcategory btn-info" style="color:white;">Create new</option>
            </select>
            <div class="bg-secondary categorycreatediv" style="padding:5px 200px 5px 200px; display:none;">
            <input type="text" class="form-control mt-1" id="categoryname" placeholder="Category Name"></input>
            <select type="text" class="form-control" id="categoryparent">
                <option value="0">None</option>
                @if($categories->count() === 0)
                <option value="" disabled>No categories available</option>
                @else
                <option value="0"selected>None</option>
                @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
                @endif

            </select>
            <input type="file" name="img_path" id="categoryimg_path"></input>
            <button class="btn btn-info" id="categorycreatesubmit">Create category</button>

            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" style="height:20vh;" class="form-control">{{$product->description}}</textarea>
        </div>
        <label for="price">Price</label>
        <div class="input-group input-group-lg mb-3" style="width:300px;">
            <div class="input-group-prepend">
                <span class="input-group-text">£</span>
            </div>
            <input class="form-control" step=".01" type="number" name="price" value="{{$product->price}}"></input>
        </div>
        <label for="stock">Stock</label>
        <div class="input-group input-group-lg mb-3" style="width:300px;">
            <input class="form-control" type="number" name="stock" value="{{$product->stock}}"></input>
        </div>
        <h3>Attributes</h3>
        <div class="form-group">
            <label for="hardiness_zone">Hardiness Zone (Current - {{$product->hardiness_zone}} zone)</label>
            <select name="hardiness_zone" class="form-control">
                <option value="{{$product->hardiness_zone}}">No change</option>
                <option value="Red">Red</option>
                <option value="Amber">Amber</option>
                <option value="Green">Green</option>
            </select>
        </div>
        <div class="form-group">
            <label for="soil_type">Soil Type (Current - <span hidden>{{$soil_type = str_replace(array('"', '[', ']'), '', $product->soil_type)}}</span> {{str_replace(',', ', ', $soil_type)}})</label>
            <select name="soil_type[]" class="form-control" multiple>
                <option value="Acid free">Acid free</option>
                <option value="Clay">Clay</option>
                <option value="Sandy">Sandy</option>
                <option value="Silt">Silt</option>
                <option value="Loam">Loam</option>
                <option value="Chalky">Chalky</option>
                <option value="Free draining">Free draining</option>
            </select>
        </div>
        <div class="form-group">
            <label for="light_aspect">Light Aspect (Current - <span hidden>{{$light_aspect = str_replace(array('"', '[', ']'), '', $product->light_aspect)}}</span> {{str_replace(',', ', ', $light_aspect)}})</label>
            <select name="light_aspect[]" class="form-control" multiple>
                <option value="shade">Shade</option>
                <option value="clay">Part shade</option>
                <option value="sandy">Bright windowsill</option>
                <option value="silt">Full sun windowsill</option>
                <option value="loam">Full sun</option>
            </select>
        </div>
        <label for="image">Change Image</label>
        <input type="file" name="img_path" value="NULL"></input>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <form id="deleteform" action="{{route('tremenheere.stock.deleteproduct', $product->id)}}">@csrf @method('delete')</form>
    <script>
    $('#deleteproduct').click(function(e){
        e.preventDefault()
        window.confirm('Are you sure?')
        $('#deleteform').submit()
    })
    $('#category').on('change', function(){
        if($(this).val() == 'categorycreate'){
            $('.categorycreatediv').slideDown('slow')
        }else{
            $('.categorycreatediv').slideUp('slow')

        }
    })
    $('#categorycreatesubmit').on('click', function(e){
        e.preventDefault()
        var _token = $('meta[name="csrf-token"]').attr('content')
        var formData = new FormData()
        var name = $('#categoryname').val()
        var parentid = $('#categoryparent').val()
        var img_path = $('#categoryimg_path')[0].files
        formData.append('_token', _token)
        formData.append('name', name)
        formData.append('parentid', parentid)
        if(img_path.length !== 0){
            formData.append('img_path', img_path[0])
        }
        $.ajax({
            url:"{{route('tremenheere.stock.categorystore')}}",
            data:formData,
            processData:false,
            method:"POST",
            contentType: false,
            success:function(result){
                $('.categorycreatediv').slideUp('slow')

                var name = result.name
                var id = result.id
                
                $('.createcategory').before($('<option>', {
                 value: "" + id,
                 text: "" + name, 
                }))          
                if($('.flashalert').is(':animated')){
                    return false;
                }else{
                    $('.flashalert').attr('hidden', false);
                    $('.flashalert').find('span').text("Category created.")
                    $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                }

            },
            error:function(error){
                console.log(error)
            }
        })
    })

    </script>
</x-app-layout>