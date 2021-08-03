<x-app-layout>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link href="{{ URL::asset('css/main.css') }}" rel='stylesheet' />
        <script src="{{ URL::asset('js/main.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
        <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Calendar') }}
        </h2>
    </x-slot>
    <x-jet-validation-errors />
    <style>
    #overlay {
        position: fixed; /* Sit on top of the page content */
        display: none; /* Hidden by default */
        width: 100%; /* Full width (cover the whole page) */
        height: 100%; /* Full height (cover the whole page) */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5); /* Black background with opacity */
        z-index: 2; /* Specify a stack order in case you're using a different order for other elements */
        cursor: pointer; /* Add a pointer on hover */
        }
    .bg-info{
        cursor: default;
    }
    @media (max-width: 1200.98px) {
        .w-50 {
            width: 100% !important;
            }
        }
    </style>

    <div id='calendar'></div>
    <div id="overlay">
    
    <div class="bg-info w-50 ml-auto mr-auto p-5" style="margin-top:200px;">
    <h2>New event</h2>
    <form id="createEvent">
    <div class="form-group" >
        <label for="title">Title</label>
        <input type="text" class="form-control" name="title" id="title" placeholder="Title">
    </div>
    <div class="form-group">
        <label for="start">Start</label>
        <input type="date" name="start[]" class="form-control" id="startdate">
        <input type="time" name="start[]" class="form-control" id="starttime" value="00:00">

    </div>
    <div class="form-group">
        <label for="end">End time</label>
        <input type="date" name="end[]" class="form-control" id="enddate">
        <input type="time" name="end[]" class="form-control" id="endtime" value="00:00">
    </div>
    <button id="createButton" class="btn btn-primary" type="submit">Create</button>
    </div>
    </form>
    </div>
    <script>
    $(document).ready(function(){
        $.browser.device = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        function createEvent(date){
            $('#overlay').find('button').text('Create')
                $('#overlay').find('h2').text('Create event')

                on();
                var startdate = moment(date.start).format('YYYY-MM-DD')
                var starttime = moment(date.start).format('HH:mm:ss')
                var enddate = moment(date.end).format('YYYY-MM-DD')
                var endtime = moment(date.end).format('HH:mm:ss')
                $('#startdate').val(startdate)
                $('#enddate').val(enddate)
                $('#createButton').on('click', function(e){
                    e.preventDefault()
                    var start = $('#startdate').val() + "T" + $('#starttime').val() + ":00"
                    var end = $('#enddate').val() + "T" + $('#endtime').val() + ":00"
                    var title = $('#overlay').find('#title').val()
                    console.log(end)
                    console.log(start)
                    $.ajax({
                        url:"{{route('calendar.action')}}",
                        dataType: "json",
                        method:"POST",
                        data:{
                            title: title,
                            start: start,
                            end: end,
                            type: 'add'
                        },
                        success: function(result){
                            off()
                            calendar.refetchEvents()
                            if($('.flashalert').is(':animated')){
                                return false;
                            }else{
                                $('.flashalert').attr('hidden', false);
                                $('.flashalert').find('span').text('Event created successfully!')
                                $('.flashalert').fadeIn().delay('700').fadeOut('slow')
                            }
                        },
                        error: function(error){

                            console.log(error)
                        }
                    })
                })
        }

        function on() {
            document.getElementById("overlay").style.display = "block";
        }

        function off() {
            document.getElementById("overlay").style.display = "none";
        }
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')

            }
        })

        var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            eventClick: function(edit) {
                var event = edit.event;
                console.log(event)
                on()
                var editstartdate = moment(event._instance.range.start).subtract(1, 'h').format('YYYY-MM-DD')
                var editstarttime = moment(event._instance.range.start).subtract(1, 'h').format('HH:mm:ss')
                var editenddate = moment(event._instance.range.end).subtract(1, 'h').format('YYYY-MM-DD')
                var editendtime = moment(event._instance.range.end).subtract(1, 'h').format('HH:mm:ss')
                var title = $('#overlay').find('#title').val()
                $('#overlay').find('h2').text('Edit event')
                $('#overlay').find('#startdate').val(editstartdate)
                $('#overlay').find('#starttime').val(editstarttime)
                $('#overlay').find('#enddate').val(editenddate)
                $('#overlay').find('#endtime').val(editendtime)
                $('#overlay').find('#title').val(event._def.title)

                $('#overlay').find('button').text('Edit')
                $('#overlay').find('#createButton').text('Edit')
                $('#overlay').find('#createButton').attr('id', 'editButton')
                $('#overlay').find('#editButton').after('<button id="deleteEvent" class="btn btn-danger">Delete</button>')

                $('#deleteEvent').on('click', function(){
                    $.ajax({
                        url:"{{route('calendar.delete')}}",
                        method:"PATCH",
                        dataType:"json",
                        data:{id:event._def.publicId},
                        success:function(result){
                            console.log(result)
                            
                            off()
                            $('#overlay').find('h2').text('Create event')
                            $('#overlay').find('#startdate').val('')
                            $('#overlay').find('#starttime').val('')
                            $('#overlay').find('#enddate').val('')
                            $('#overlay').find('#endtime').val('')
                            $('#overlay').find('#title').val('')
                            $('#overlay').find('#deleteEvent').remove()
                            alert("Event Deleted Successfully!")
                        }
                    })
                })

                $('#editEvent').on('click', function(e){
                    e.preventDefault()
                    var start = $('#startdate').val() + "T" + $('#starttime').val()
                    var end = $('#enddate').val() + "T" + $('#endtime').val()
                    var title = $('#overlay').find('#title').val()
                    $.ajax({
                        url:"{{route('calendar.editevent')}}",
                        dataType: "json",
                        method:"PATCH",
                        data:{
                            id: event._def.publicId,
                            title: title,
                            start: start,
                            end: end,
                        },
                        success: function(result){

                        },
                        error: function(error){
                            console.log(error)
                            calendar.refetchEvents()

                            off()
                            $('#overlay').find('h2').text('Create event')
                            $('#overlay').find('#startdate').val('')
                            $('#overlay').find('#starttime').val('')
                            $('#overlay').find('#enddate').val('')
                            $('#overlay').find('#endtime').val('')
                            $('#overlay').find('#title').val('')
                            $('#overlay').find('#deleteEvent').remove()
                            $('#overlay').find('button').text('Create')
                            alert("Event Edited Successfully!")
                        }
                    })
                })
            },
            editable: true,
            events:"/calendar/json",
            selectable:true,
            select:function(date){
                createEvent(date)
            }
        });

        calendar.render()
        $('.fc-today-button').text('Today')
        $('button').on('click', function(){
            $('.fc-today-button').text('Today')
            
        })

        if($.browser.device){
            $('.fc-today-button').before('<button id="newEventButton" class="btn btn-success">+</button>')
        }
        $('#newEventButton').click(function(date){
            createEvent(date)
        })
        $(document).mouseup(function(e) {
            var container = $("#overlay").find('.bg-info');
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                if($('#overlay').css('display') == 'block'){
                    off()
                    location.reload()
                }
              
            }
        });
    })

    </script>



   
</x-app-layout>