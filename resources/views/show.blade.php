@extends('layout.app')

@section('content')

	<show-collectible-component
		:collectible="{{ json_encode($collectible) }}"
		:transactions="{{ json_encode($transactions) }}"
		:onWishList="{{ json_encode($onWishList) }}"
		:asset_url="{{ json_encode(asset('/')) }}"
		:auth_check="{{ json_encode(Auth::check()) }}"
		:user_profile="{{ json_encode(URL::to('/profile/')) }}"
		:current_user="{{ json_encode(Auth::check() ? Auth::user()->id : 0) }}"
		:base_url="{{ json_encode(URL::to('/')) }}"
	></show-collectible-component>

	<div class="nft-fullscreen d-none">
		@if($collectible->type == 'image')
		<img class="nft-item" src="{{ asset('storage/collectibles/'.$collectible->nft) }}">
		@else
		<video class="nft-item" autoplay loop controls muted>
		      <source src="{{ asset('storage/collectibles/'.$collectible->nft) }}" type="video/mp4">
		</video>
		@endif

		<div class="nft-shrink">
			<i class="fa fa-compress" aria-hidden="true"></i>
		</div>

		<div class="nft-details">
			<h4>{{$collectible->name}}</h4>
			<p>by <span> {{$collectible->creator->user_name}} </span> on <span>{{$collectible->collection}}</span></p>
		</div>
	</div>

	@include('includes.show-modal')


@section('extra_scripts')
	<script src="{{ asset('js/custom.js') }}"></script>
	<script>
		$(document).ready(function(){
			$(".tabLink").click(function(){
				var id = $(this).attr("href");
				$(".tab-pane").hide();
				$(id).show();
			});


			$('#buyBtn').click(function(){
	            $('#checkoutModal').addClass('d-block');
	            $(".checkout-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
	        });
	        
	        $('.close-checkout-modal').click(function(){
	             $(".checkout-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
	            setTimeout(function() {
	                $('#checkoutModal').removeClass('d-block');
	            }, 400);
	        });

	        $('.checkout-currency').click(function(){
	           toggleDropDown($(".checkout-drop"));
	        });

	        $('.quantity-btn').click(function(){
	           toggleDropDown($(".quantity-drop"));
	        });

			$('.side-drop.currency-item').click(function(){
				$('.currency-check').addClass('opacity-0');
				$(this).siblings().removeClass('opacity-0');
				$('.checkout-currency').html($(this).attr('id')+' <i class="fa fa-angle-down"></i>');
				$('.changeDD').text($(this).attr('id'));
				$('.toggle-currency').text($(this).attr('id'));
				toggleDropDown($(".checkout-drop"));
			});

			$('.quantity-item').click(function(){
				$('.quantity-check').addClass('opacity-0');
				$(this).siblings().removeClass('opacity-0');
				$('.quantity-btn').html($(this).attr('id')+' <i class="fa fa-angle-down"></i>');
				$('.quantity-input').val($(this).attr('id'));
				$('.changeDD').text($(this).attr('id'));
				toggleDropDown($(".quantity-drop"));
			});

			$('.quantity-input').change(function(){
				$('.quantity-btn').html($(this).val()+' <i class="fa fa-angle-down"></i>');
			});

			$("#options-btn").click(function(){
				toggleDropDown($(".show-opt-menu"));
			});
			
			$('.buy-now').click(function(){
	            $('#checkoutModal').addClass('d-block');
	            $(".checkout-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
	        });
	        
	        $('.close-checkout-modal').click(function(){
	             $(".checkout-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
	            setTimeout(function() {
	                $('#checkoutModal').removeClass('d-block');
	            }, 400);
	        });


	        $('#bidBtn').click(function(){
	        	$('#bidModal').addClass('d-block');
	            $(".bid-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
	        });

	        $('.place-bid').click(function(){
	            $('#bidModal').addClass('d-block');
	            $(".bid-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
	        });
	        
	        $('.close-bid-modal').click(function(){
	             $(".bid-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
	            setTimeout(function() {
	                $('#bidModal').removeClass('d-block');
	            }, 400);
	        });


	        $('.report').click(function(){
	            $('#reportModal').addClass('d-block');
	            $(".report-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
	        });
	        
	        $('.close-report-modal').click(function(){
	             $(".report-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
	            setTimeout(function() {
	                $('#reportModal').removeClass('d-block');
	            }, 400);
	        });



	        $('.currency-btn').click(function(){
	           toggleDropDown($(".currency-drop"));
	        });

			$('.bid-drop.currency-item').click(function(){
				$('.currency-check').addClass('opacity-0');
				$(this).siblings().removeClass('opacity-0');
				$('.currency-btn').html($(this).attr('id')+' <i class="fa fa-angle-down"></i>');
				$('.changeDD').text($(this).attr('id'));
				toggleDropDown($(".currency-drop"));
			});


			$('#share-btn').click(function(){
				toggleDropDown($(".show-share-drop"));
			});

			$('#nft-expand').click(function(){
				$('.custom-navbar').addClass('d-none');
				$('.show-collectible').addClass('d-none');
				$('.nft-fullscreen').removeClass('d-none');
			});

			$('.nft-shrink').click(function(){
				$('.custom-navbar').removeClass('d-none');
				$('.show-collectible').removeClass('d-none');
				$('.nft-fullscreen').addClass('d-none');
			});

			$('#like-btn').click(function(){
				var nft_slug = $(this).data('nft-slug');

				$.ajaxSetup({
		            headers: {
		                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		            }
		        });

		        $.ajax({
					url: "/wishlist/"+nft_slug,
					type: "POST",
					success: function(response){
						if (response.action == 'added') {
							$('#like-btn').addClass('nft-liked');
						}else{
							$('#like-btn').removeClass('nft-liked');
						}
					},
					error: function (xhr) {
						console.log('Something went wrong!');
					},
		        });


			});


			// ############ WHEN CLICKED OUTSIDE DROPDOWN #######################
			$(document).click(function(event) {
              if ($(event.target).closest('#options-btn').length === 0 && $(event.target).closest('.show-opt-menu').length === 0 && $('.show-opt-menu').hasClass('fade-in-top')) {
                 toggleDropDown($('.show-opt-menu'));
              }
              if ($(event.target).closest('.checkout-currency').length === 0 && $(event.target).closest('.checkout-drop').length === 0 && $('.checkout-drop').hasClass('fade-in-top')) {
                 toggleDropDown($(".checkout-drop"));
              }
              if ($(event.target).closest('.currency-btn').length === 0 && $(event.target).closest('.currency-drop').length === 0 && $('.currency-drop').hasClass('fade-in-top')) {
                 toggleDropDown($(".currency-drop"));
              }
              if ($(event.target).closest('#share-btn').length === 0 && $(event.target).closest('.show-share-drop').length === 0 && $('.show-share-drop').hasClass('fade-in-top')) {
                 toggleDropDown($('.show-share-drop'));
              }

              if ($(event.target).closest('.quantity-btn').length === 0 && $(event.target).closest('.quantity-drop').length === 0 && $('.quantity-drop').hasClass('fade-in-top')) {
                 toggleDropDown($(".quantity-drop"));
              }

              event.stopPropagation();
            });
			// ##################################################################

		});

		function toggleDropDown(container){
			if (!container.hasClass('fade-in-top')) {
	            container.toggleClass('d-none');
	            container.addClass("fade-in-top").removeClass("fade-out-top");
	        }
	        else 
	        {
	            container.addClass("fade-out-top").removeClass("fade-in-top");
	            setTimeout(function() {
	              container.toggleClass('d-none');
	            }, 400);
	        }
		}

		
	</script>
@endsection

@endsection
