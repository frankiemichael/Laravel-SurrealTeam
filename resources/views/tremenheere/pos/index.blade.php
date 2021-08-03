<x-app-layout>
    <x-slot name="header">
        <h2 id="title" class="h4 font-weight-bold">
            {{ __('Tremenheere Point of Sale') }}
        </h2>
        
    </x-slot>
    <x-jet-validation-errors />
    <style>
        @media only screen and (max-width: 768px) {
            .container{
                max-width:100%;
                
            }
            #categories div{
                display: flex;
                align-items:center;
                overflow:scroll;
            }
            button{
                white-space:nowrap;
            }
        }
        
        #subcategories{
        width:auto !important;
        }
        #categories{
            overflow:hidden;
            display: inline;
            align-items:center;
        }
        #returnBtnDiv{
            position: relative;
            width:100px;
        }
        .innerDiv{
            width:1220px !important;
        }
    </style>
    


    <div class="grid">
    <div class="row row1">
        <div id="categories" class="col">
            <div style="max-width:1220px !important;">
                @foreach($categories as $category)
                <button class="categorybtn btn btn-secondary m-1" data-id="{{$category->id}}" style="width:130px;">{{$category->name}}</button>
                @endforeach
            </div>
        </div>
        <div class="col pt-1" id="returnBtnDiv"  style="display:none;"><button id="returnBtn" class="btn btn-dark">Return</button></div>
    </div>


    <div id="subcategories" class="row">


    </div>

    <div id="products" class="row p-5">


    </div>
    </div>
    <script>
    $('document').ready(function(){
        //$('#title').parent().delay('1500').slideUp()
        const FIRSTBUTTON = $('#categories').first('button')
        $('.categorybtn').on('click', function parentCategoryClick(){
            var button = $(this)
            $(button).removeClass('btn-secondary')
            $(button).addClass('btn-info')
            var oldDivHeight = $('#categories').height()
            var oldDivWidth = $('#categories').width()
            console.log(oldDivWidth)
            var newHeight = $(this).height() + 30
            var newWidth = $(this).width() + 30
            var rowWidthPlus = newWidth + 40
            var rowWidth = rowWidthPlus * 2
            $('.categorybtn').css('position','sticky')
            $("#categories").animate( { height: newHeight + "px",  width: newWidth + "px" }, { queue:false, duration:500 })
            $(".row1").animate( { height: newHeight + "px",  width: rowWidth + "px" }, { queue:false, duration:500 })
            $(button).siblings().fadeOut()

            $('#returnBtnDiv').fadeIn()
            $('#categories').delay(500).queue(function(){             

                $('.categorybtn').css('position','static')
                
            })
            $('#innerDiv').addClass('innerDiv')
            console.log(newHeight)
            var id = $(this).attr('data-id')
            $.ajax({
                url:"{{route('tremenheere.pos.index')}}",
                method:'GET',
                data:{_token:$('meta[name="csrf-token"]').attr('content'), _method:'GET', id: id},
                success:function(result){
                    console.log(result)
                    $('#innerDiv').removeClass('innerDiv')

                    $.each(result.children, function(i, child){
                        $('#subcategories').append('<button class="subcategorybtn btn btn-outline-primary m-1" data-id="'+child.id+'" style="width:130px;">'+child.name+'</button>')
                    })

                    $.each(result.tremenheerestock, function(i, product){

                        $('#products').append('<div class="card" style="width: 18rem;"><img class="card-img-top" src="..." alt="Card image cap"><div class="card-body"><h5 class="card-title">Card title</h5><p class="card-text">Some quick example text to build on the card title and make up the bulk of the cards content.</p><a href="#" class="btn btn-primary">Go somewhere</a></div></div>')
                    })

                    $('.subcategorybtn').click(function subCategoryButton(){
                        $('#subcategories button').removeClass('btn-primary')
                        $('#subcategories button').addClass('btn-outline-primary', 'text-white')
                        $(this).removeClass('btn-outline-primary')
                        $(this).addClass('btn-primary')
                    })

                    $('#returnBtn').click(function(){
                        $(button).siblings().fadeIn()
                        $(button).addClass('btn-secondary')
                        $(button).removeClass('btn-info')

                        $('#returnBtnDiv').hide()
                        $('#subcategories').empty()
                        $('#products').empty()
                        $(".row1").animate( { height: oldDivHeight + "px",  width: "100%" }, { queue:false, duration:500 })
                        $("#categories").animate( { height: oldDivHeight + "px",  width: oldDivWidth + "px" }, { queue:false, duration:500 })
                        $('.subcategorybtn').click(function(){
                            subCategoryButton()
                        })
                    })
                },
                error:function(error){
                    console.log(error)
                }
            })
        })

        
    
    
    })
    </script>
</x-app-layout>