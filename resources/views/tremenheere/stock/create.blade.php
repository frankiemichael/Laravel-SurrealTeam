<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Create Product') }}
        </h2>

    </x-slot>

    <x-jet-validation-errors />
    <form method="post" action="{{route('tremenheere.stock.store')}}" enctype="multipart/form-data">
    @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" id="name" type="text" name="name" placeholder="{{ old('name', 'Name') }}">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="parent_id">
                @if($categories->count() === 0)
                <option value="" disabled>No categories available</option>
                @else
                <option value="{{$none->id}}" selected>None</option>

                @foreach($categories as $category)
                
                @if($category->id !== 1)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @if($category->children)
                @foreach($category->children as $children)
                <option style="color:grey;" value="{{$children->id}}">&nbsp;&nbsp;&nbsp;{{$children->name}}</option>
                @endforeach
                @endif
                @endif
                @endforeach
                @endif
                <option value="categorycreate" class="createcategory btn-info" style="color:white;">Create new</option>
            </select>
            <div class="bg-secondary categorycreatediv" style="padding:5px 200px 5px 200px; display:none;">
            <input type="text" class="form-control mt-1" id="categoryname" placeholder="Category Name"></input>
            <select type="text" class="form-control" id="categoryparent">
                @if($categories->count() === 0)
                <option value="" disabled>No categories available</option>
                @else
                <option value="{{$none->id}}" selected>None</option>
                @foreach($categories as $category)
                @if($category->id !== 1)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @if($category->children)
                @foreach($category->children as $children)
                <option style="color:grey;" value="{{$children->id}}">&nbsp;&nbsp;&nbsp;{{$children->name}}</option>
                @endforeach
                @endif
                @endif
                @endforeach
                @endif

            </select>
            <input type="file" name="img_path" id="categoryimg_path"></input>
            <button class="btn btn-info" id="categorycreatesubmit">Create category</button>

            </div>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" style="height:20vh;" class="form-control" placeholder="{{old('description', 'Description')}}">{{old('description', '')}}</textarea>
        </div>
        <label for="price">Price</label>
        <div class="input-group input-group-lg mb-3" style="width:300px;">
            <div class="input-group-prepend">
                <span class="input-group-text">£</span>
            </div>
            <input class="form-control" step=".01" type="number" name="price" value="{{old('price', '')}}"></input>
        </div>
        <label for="stock">Stock</label>
        <div class="input-group input-group-lg mb-3" style="width:300px;">
            <input class="form-control" type="number" name="stock" value="{{old('stock', '')}}"></input>
        </div>
        <h3>Attributes</h3>
        <div class="form-group">
            <label for="hardiness_zone">Hardiness Zone</label>
            <select name="hardiness_zone" class="form-control">
                <option value="Red">Red</option>
                <option value="Amber">Amber</option>
                <option value="Green">Green</option>
            </select>
        </div>
        <div class="form-group">
            <label for="soil_type">Soil Type</label>
            <select name="soil_type[]" class="form-control" multiple>
                <option value="acid_free">Acid Free</option>
                <option value="clay">Clay</option>
                <option value="sandy">Sandy</option>
                <option value="silt">Silt</option>
                <option value="loam">Loam</option>
                <option value="chalky">Chalky</option>
                <option value="free-draining">Free draining</option>
            </select>
        </div>
        <div class="form-group">
            <label for="light_aspect">Light Aspect</label>
            <select name="light_aspect[]" class="form-control" multiple>
                <option value="shade">Shade</option>
                <option value="clay">Part shade</option>
                <option value="sandy">Bright windowsill</option>
                <option value="silt">Full sun windowsill</option>
                <option value="loam">Full sun</option>
            </select>
        </div>
        <input type="hidden" name="prevurl" value="{{$prevurl}}">

        <label for="image">Image Upload</label>
        <input type="file" name="img_path" value="NULL"></input>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
    <script>
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
                $('#category option[value='+id+']').attr('selected', 'selected')
                $('#categoryname').val('')
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