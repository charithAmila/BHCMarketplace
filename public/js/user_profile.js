$(document).ready(function() {
    $(".close-following-modal").click(function() {
        modalClose($("#following-modal"), $(".following-content"));
    });

    $(".follower").click(function() {
        modalOpen($("#follower-modal"), $(".follower-content"));
    });
    $(".close-follower-modal").click(function() {
        modalClose($("#follower-modal"), $(".follower-content"));
    });

    /*$('.edit-cover').click(function(){
		modalOpen($('#update-cover'), $(".cover-content"));
	});

	$('.edit-cover-mobile').click(function(){
		modalOpen($('#update-cover'), $(".cover-content"));
	});*/

    $(".close-cover-modal").click(function() {
        modalClose($("#update-cover"), $(".cover-content"));
    });

    $(".share-link").click(function() {
        toggleDropdown($(".share-drop"), "d-block");
    });

    $(document).on("submit", "#update-preferences", function() {
        var current = $(".user-logo").attr("src");
        $("#current-display-photo").attr("src", current);
    });

    $(document).click(function(event) {
        if (
            $(event.target).closest(".share-link").length === 0 &&
            $(event.target).closest(".share-drop").length === 0 &&
            $(".share-drop").hasClass("d-block")
        ) {
            toggleDropdown($(".share-drop"), "d-block");
        }
        if (
            $(event.target).closest(".options-link").length === 0 &&
            $(event.target).closest(".options").length === 0 &&
            $(".options").hasClass("d-block")
        ) {
            toggleDropdown($(".options"), "d-block");
        }
        event.stopPropagation();
    });

    $(".options-link").click(function() {
        toggleDropdown($(".options"), "d-block");
    });

    $(".report-page").click(function() {
        $(".report-description").text(
            "Describe why you think this user violates the rules of the site"
        );
        modalOpen($("#reportModal"), $(".report-content"));
    });

    $(".close-report-modal").click(function() {
        modalClose($("#reportModal"), $(".report-content"));
    });

    $(document).on("click", ".report", function() {
        $(".report-description").text(
            "Describe why you think this item should be removed from marketplace"
        );
        modalOpen($("#reportModal"), $(".report-content"));
    });
    $(".close-report-modal").click(function() {
        modalClose($("#reportModal"), $(".report-content"));
    });

    $(".profile-preferences").click(function() {
        //showPreferencesModal();
    });

    /*$('#dp-option').click(function(){
    	$(this).addClass('custom-click');
    	setTimeout(function() {
           $('#dp-option').removeClass('custom-click');
        }, 100);

        setTimeout(function() {
           showPreferencesModal();
        }, 200);
    });*/

    $("#current-display-photo").hover(function() {
        $("#dp-option").removeClass("scale-out");
        $("#dp-option").addClass("scale-in");
    });

    $(document).on("mouseleave", "#dp-option", function() {
        $("#dp-option").addClass("scale-out");
    });

    /*
    $(document).on('click', '.btn-follow', function(){
    	var container = $(this);
    	$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var short_url = $(this).data('user-url');
        $.ajax({
          url: "/follower/"+short_url,
          type: "POST",
          success: function(response){
          	console.log(response.follower);
          	container.siblings('.btn-follow').removeClass('d-none');
          	container.addClass('d-none');
          },
          error: function (xhr) {
            console.log("Something went wrong");
          },
        });
    });
*/
    /*$(document).on("change", "#user-cover", function() {
    	$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var formdata = new FormData();

        formdata.append('cover_photo', $('#user-cover').prop('files')[0]);
        formdata.append('uploaded', 'cover');


        $.ajax({
          url: "/users/update",
          type: "POST",
          data: formdata,
          processData: false,
          contentType: false,
          success: function(response){


            $(".cover-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
            setTimeout(function() {
                $('#update-cover').removeClass('d-block');
            }, 400);

            $('.toast-message').text(response.message);
            setTimeout(function() {
                launch_toast();
            }, 500);

            $('#user-cover').val(null);
            var updated_cover = "{!! asset('storage/user/cover') !!}/"+response.photo;
            $('#current-cover-photo').attr('src', updated_cover);

          },
          error: function (xhr) {
            if (xhr.status == 422) {
                var errors = JSON.parse(xhr.responseText);
                $.each(errors.errors, function(key,value){
                    var html = '';
                    $.each(value, function(key2, value2){
                        html += value2 + '</br>';
                    });
                    $('#update-cover-error').html(html);
                });
            }
          },
        });
	});*/
});