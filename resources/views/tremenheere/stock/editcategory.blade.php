<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Category') }}
        </h2>

    </x-slot>

    <div class="second-nav">
            <ul>
            <li>
                <a href="{{ route('tremenheere.pos.index') }}" class="nav-link">Point of Sale</a>
            </li>
            <li>
                <a href="{{ route('tremenheere.stock.index') }}" class="nav-link">Stock Management</a>
            </li>
            <li >
                <a href="{{ route('tremenheere.labels.index') }}" class="nav-link">Label Management</a>
            </li>
            </ul>
        </div>
    <x-jet-validation-errors />
            @if ($category->img_path !== NULL)
            <img class="card-img-top mx-auto mb-4" src="{{$category->img_path}}" alt="{{$category->name}}" style="display:block;width:400px;">
            @endif
    <form action="{{route('tremenheere.stock.updatecategory', $category->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('patch')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{$category->name}}">
        </div>
        <div class="form-group">
            <label for="parent">Parent Category</label>
            <select name="parent_id" class="form-control" id="category">
                <option value="0" selected>None</option>
                @if($parents->count() === 0)
                <option value="" disabled>No categories available</option>
                @else
                @foreach($parents as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
                @endif
                <option value="categorycreate" class="createcategory btn-info" style="color:white;">Create new</option>
                
            </select>
            <div class="bg-secondary categorycreatediv" style="padding:5px 200px 5px 200px; display:none;">
            <input type="text" class="form-control mt-1" id="categoryname" placeholder="Category Name"></input>
            <select type="text" class="form-control" id="categoryparent">
                <option value="0" selected>None</option>
                @if($parents->count() === 0)
                <option value="" disabled>No categories available</option>
                @else
                <option value="0"selected>None</option>
                @foreach($parents as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
                @endif

            </select>
            <input type="file" name="img_path" id="categoryimg_path"></input>
            <button class="btn btn-info" id="categorycreatesubmit">Create category</button>

            </div>
            <div class="form-group mt-2">
                <label for="image">Change Image</label><br>
                <input type="file" name="img_path" value="NULL"></input>
            </div>
            <div class="form-group float-right mt-5">
            <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
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