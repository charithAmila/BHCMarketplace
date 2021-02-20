@extends('layout.app')

@section('content')

	<div class="profile-cover">
		<img id="current-cover-photo" src="{{ asset($user->cover_path.$user->cover_photo) }}">
		@if(Auth::check())
			@if(Auth::user()->id == $user->id)
			<a href="javascript:void(0)" class="edit-cover d-none d-md-block">Edit Cover</a>
			<a href="javascript:void(0)" class="edit-cover-mobile d-xs-block d-md-none">
				<i class="fa fa-cog"></i>
			</a>
			@endif
		@endif
	</div>

	<div class="profile-section">
		<div class="profile-info">

			<div class="actual-img-container">
			  <img class="profile-photo actual-photo" id="current-display-photo" src="{{ asset($user->photo_path.$user->display_photo) }}">
			  @if(Auth::check())
				  @if(Auth::user()->id == $user->id)
				  <div id="dp-option" class="custom">
				    <i class="fa fa-cog"></i>
				  </div>
				  @endif
			  @endif
			</div>
			<div class="details-container">
				<label id="profile-name">{{ $user->name }}</label>
				<label id="profile-bio">{{ $user->description }}</label>
				<label id="profile-wallet">{{ $user->wallet }}</label>
				<div class="profile-button">
					@if(Auth::check())
						@if(Auth::user()->id != $user->id)
						<a id="btn-follow" class="btn btn-follow {{ $following == false ? '' : 'd-none' }}" data-user-url="{{ $user->link_profile }}" href="javascript:void(0)">Follow</a>

						<a id="btn-following" class="btn btn-follow {{ $following == true ? '' : 'd-none' }}" data-user-url="{{ $user->link_profile }}" href="javascript:void(0)">Following</a>
						@endif
					@endif
					<a class="btn btn-social share-link" href="javascript:void(0)">
						<i class="fa fa-upload"></i>
					</a>
					<a class="btn btn-social options-link" href="javascript:void(0)">
						<i class="fas fa-ellipsis-h"></i>
					</a>

					<div class="share-drop d-none">
						<label class="share-title">Share link to this page</label>
						<div class="row">
							<div class="col-4 col-md-4">
								<a href="javascript:void(0)">
									<i class="fa fa-twitter s-btn"></i>
									<label>Twitter</label>
								</a>
							</div>
							<div class="col-4 col-md-4">
								<a href="javascript:void(0)">
									<i class="fa fa-facebook s-btn"></i>
									<label class="fb-label">Facebook</label>
								</a>
							</div>
							<div class="col-4 col-md-4">
								<a href="javascript:void(0)">
									<i class="fab fa-telegram-plane s-btn"></i>
									<label>Telegram</label>
								</a>
							</div>
							<div class="col-4 col-md-4">
								<a href="javascript:void(0)">
									<i class="fa fa-envelope s-btn"></i>
									<label>Email</label>
								</a>
							</div>
							<div class="col-4 col-md-4">
								<a href="javascript:void(0)">
									<i class="fa fa-copy s-btn"></i>
									<label class="c-link">Copy link</label>
								</a>
							</div>

						</div>
					</div>

					<div class="options d-none">
						<a class="report-page" href="javascript:void(0)">Report page</a>
						<a class="profile-preferences" href="javascript:void(0)">Edit preferences</a>
					</div>

				</div>
			</div>
		</div>
	</div>


	<profile-component
		:collectible_asset="{{ json_encode(asset('storage/collectibles').'/') }}"
		:show_collectible="{{ json_encode(asset('/nft').'/') }}"
		:current_user="{{ json_encode(Auth::check() ? Auth::user()->id : 0) }}"
		:base_url="{{ json_encode(URL::to('/')) }}"
		:asset_url="{{ json_encode(asset('/')) }}"
		:marketplace_url="{{ json_encode(route('marketplace')) }}"
		:user_slug="{{ json_encode( $user->short_url != 'null' ? $user->short_url : $user->wallet ) }}"
	></profile-component>


	

	<div id="update-cover" class="custom-modal">
		<div class="modal-content cover-content">
			<div class="modal-head">
				<h3>Update cover</h3>
				<span class="close-cover-modal">&times;</span>
			</div>
			<div class="modal-body">
				<form id="upload-cover">
					@csrf
					<label class="cover-text">Upload new cover for your profile page. We recommend to upload images in 1440x260 resolution</label>
					<input type="file" id="user-cover" name='cover_photo' accept="image/x-png,image/gif,image/jpeg"/>
					<input class="user-photo-btn choose-image" type="button" value="Choose image" onclick="document.getElementById('user-cover').click();" />
					<span id="update-cover-error" class="custom-error text-danger"></span>
				</form>
			</div>
		</div>
	</div>


	@include('includes.action-modal')

	@include('includes.footer')


	@section('extra_scripts')
	<script src="{{ asset('js/custom.js') }}"></script>
	<script>
		$(document).ready(function(){
			
			$('.close-following-modal').click(function(){
                modalClose($('#following-modal'), $(".following-content"));
			});


			$('.follower').click(function(){
                modalOpen($('#follower-modal'), $(".follower-content"));
			});
			$('.close-follower-modal').click(function(){
                modalClose($('#follower-modal'), $(".follower-content"));
			});

			$('.edit-cover').click(function(){
				modalOpen($('#update-cover'), $(".cover-content"));
			});

			$('.edit-cover-mobile').click(function(){
				modalOpen($('#update-cover'), $(".cover-content"));
			});

			$('.close-cover-modal').click(function(){
                modalClose($('#update-cover'), $(".cover-content"));
			});

			$('.share-link').click(function(){
				toggleDropdown($(".share-drop"), "d-block");
			});

			$(document).click(function(event) {
              if ($(event.target).closest('.share-link').length === 0 && $(event.target).closest('.share-drop').length === 0 && $('.share-drop').hasClass('d-block')) {
                 toggleDropdown($(".share-drop"), "d-block");
              }
              if ($(event.target).closest('.options-link').length === 0 && $(event.target).closest('.options').length === 0 && $('.options').hasClass('d-block')) {
              	toggleDropdown($(".options"), "d-block");
              }
              event.stopPropagation();
            });

			$('.options-link').click(function(){
				toggleDropdown($(".options"), "d-block");
			});

			$('.report-page').click(function(){
				$('.report-description').text('Describe why you think this user violates the rules of the site');
	            modalOpen($('#reportModal'), $(".report-content"));
	        });
	        
	        $('.close-report-modal').click(function(){
	            modalClose($('#reportModal'), $(".report-content"));
	        });


	        $(document).on('click', '.report', function(){
	        	$('.report-description').text('Describe why you think this item should be removed from marketplace');
	            modalOpen($('#reportModal'), $(".report-content"));
	        });
	        $('.close-report-modal').click(function(){
	            modalClose($('#reportModal'), $(".report-content"));
	        });


            $('.profile-preferences').click(function(){
            	showPreferencesModal();
            });



		    $('#dp-option').click(function(){
		    	$(this).addClass('custom-click');
		    	setTimeout(function() {
                   $('#dp-option').removeClass('custom-click');
                }, 100);

                setTimeout(function() {
                   showPreferencesModal();
                }, 200);
		    });

		    $('#current-display-photo').hover(function(){
		    	$('#dp-option').removeClass('scale-out');
		    	$('#dp-option').addClass('scale-in');

		    });

		    $(document).on('mouseleave', '#dp-option', function(){
		    	$('#dp-option').addClass('scale-out');
		    });


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

		    $(document).on("change", "#user-cover", function() {
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
			});

		});



		


	</script>
	@endsection

@endsection