$(document).ready(function(){
	$(".sorter").click(function(){
		toggleDropdown($(".sortfilter"), "d-none");
	});



    $('.report').click(function(){
        modalOpen($('#reportModal'), $(".report-content"));
    });
    $('.close-report-modal').click(function(){
        modalClose($('#reportModal'), $(".report-content"));
    });

    $(document).click(function(event) {

      if ($(event.target).closest('.sorter').length === 0 && $(event.target).closest('.sortfilter').length === 0 && $('.sortfilter').hasClass('fade-in-top')) {
         toggleDropdown($(".sortfilter"), "d-none");
      }
      event.stopPropagation();
    });


    $(".filter-btn").click(function(){
    	$(".checkLabel").hide();
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
    });

    $(".sortItem").click(function(){
		$(".checkLabel").hide();
		$(this).find( ".checkLabel" ).show();
	});

	$('#user-type').change(function(){
		var day = $('#filter-time').val();
		var type = $(this).val();
	});
	$('#filter-time').change(function(){
		var day = $(this).val();
		var type = $('#user-type').val();

	});

});

function filterUser(day, type){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
      url: "/nft/user/filter",
      type: "GET",
      data: {
      		day : day,
      		type : type,
      },
      beforeSend: function() {
	    $("#preloader-top-user").removeClass('d-none');
	    $('#actual-top-user').addClass('d-none');
	  },
      success: function(response){
      	$("#preloader-top-user").addClass('d-none');
	    $('#actual-top-user').removeClass('d-none');
      	var html = '';

      	$.each(response, function(index, user){
      		var totalPrice = user['totalPrice'];
      		var display_name = user['display_name'];
      		var asset_url = user['asset_url'];
      		var source = "{!! asset('/"+asset_url+"') !!}";

      		html += '<div class="row topUserList">\
				<div class="col-3 col-sm-3 col-md-3 col-lg-3">\
					<img class="filterImg" src="'+source+'">\
				</div>\
				<div class="col-9 col-sm-9 col-md-9 col-lg-9">\
					<h6>'+display_name+'</h6>\
					<label>'+totalPrice+' BHC</label>\
				</div>\
			</div>';

      	});
      	$('.filterList').html(html);
      },
      error: function (xhr) {
		console.log('Something went wrong!');
      },
    });

}