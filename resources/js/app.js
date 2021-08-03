
require('./bootstrap');

require('alpinejs'); 

require('./jquery.textfill');

require('./jquery-convert');
var moment = require('moment');
$(document).ready(function(){
    var date = new Date()
    var hours = date.getHours()
    var minutes = date.getMinutes()
    var month = date.getMonth()
    var day = date.getDate()
    $( ".deadlinemonth" ).each(function( index ) {
        if($(this).text() == ++month){
            var current = $(this)
            if($(current).siblings('span[class="deadlineday"]').text() == day){
                $(this).parent().css('color', '#FF7800')
                if ($(current).siblings('span[class="deadlinehour"]').text() == --hours){
                    $(this).parent().css('color', '#E60000')
                    if ($(current).siblings('span[class="deadlinehour"]').text() == hours && $(current).siblings('span[class="deadlineminute"]').text() < minutes){
                        $(this).parent().parent().css({'background-color': 'rgba(255, 0, 0, 0.4)'})
                    }

                }
            }
            
        }
    
    });

    $( ".postdate" ).each(function( index ) {
        $(this).text(moment($(this).text()).fromNow())
    })
    $('.overlayclose').on('click', function(e) {
        off();
        $(".cardcontainer").empty()
    });
    $('form').on('submit', function(){
            $("body").addClass("loading")
    })
    $('.posttype').on('change', function(){
        var posttype = $(this).val()
        if (posttype == 2){
            $('.optiondiv').attr('hidden', false)
            $('.prioritydiv').attr('hidden', true)
        }else if(posttype == 3){
            $('.optiondiv').attr('hidden', true)
            $('.prioritydiv').attr('hidden', false)

        }else {
            $('.prioritydiv').attr('hidden', true)
            $('.optiondiv').attr('hidden', true)
        }

    })
    $('.addoption').on('click', function(e){
        e.preventDefault()
        $(this).before('<input type="text" name="option[]" class="optioninput form-control"/>')
    })

})

var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
triggerTabList.forEach(function (triggerEl) {
  var tabTrigger = new bootstrap.Tab(triggerEl)

  triggerEl.addEventListener('click', function (event) {
    event.preventDefault()
    tabTrigger.show()
  })
})
