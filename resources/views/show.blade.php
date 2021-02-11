@extends('layout.app')

@section('content')

	<div class="row row-0 show-collectible">

		<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 custom-lg-4 withBgGray second-mobile">
			<div class="title-container">
				<div class="row margin-0">
					<div class="col-8 col-md-8 col-lg-8 col-xl-8 collectibleTitle col-title">
						<h3 class="inlineDiv inline-title">{{ $collectible->name }}</h3>
					</div>
					<div class="col-4 col-md-4 col-lg-4 col-xl-4 collectibleTitle col-option">
						<h3 class="inlineDiv inline-btn">
							<a id="options-btn" class="show-drop" href="javascript:void(0)"> <i class="fas fa-ellipsis-h titleIcon"></i></a>
							<a id="share-btn" class="show-drop" href="javascript:void(0)"> <i class="fa fa-share-square-o titleIcon"></i></a>
						</h3>

						<div class="show-opt-menu d-none">
							@if($collectible->isp == 1)
							<a class="buy-now" href="javascript:void(0)">Buy now</a>
							@endif
							<a class="place-bid" href="javascript:void(0)">Place a bid</a>
							<a class="report" href="javascript:void(0)">Report</a>
						</div>

						<div class="show-share-drop d-none">
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

					</div>
				</div>
			</div>
			<div class="priceTag">
				<label class="showCurrency">{{ $collectible->price }}</label>
				<!-- <label class="showCurrencyPlain">$135.20</label> -->
			</div>
			<h5 class="itemType">
				<div class="legend">
					<div class="{{ $collectible->legend }}">
						<i class="{{ $collectible->icon }}"></i> {{ ucwords($collectible->legend) }}
					</div>
				</div>
			</h5>
			<p class="itemDesc">{{ $collectible->description }}</p>

			<div class="details-tab">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#home" class="tabLink">Details</a></li>
					<li><a href="#menu1" class="tabLink">Holder</a></li>
					<li><a href="#menu2" class="tabLink">Past Transactions</a></li>
				</ul>

				<div class="tabGroup">
					<div id="home" class="tab-pane tab-active">

						<div class="row dtab">
							<div class="col-3 col-md-2">
								<div class="inlineDiv">
									<a href="{{ route('user.profile', ['slug' => $collectible->designer_profile]) }}">
										<img class="br-50" src="{{ asset('/'.$collectible->asset_url) }}" width="50" class="">
									</a>
									<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
								</div>
							</div>
							<div class="col-9 col-md-10">
								<label class="position">Designer</label>
								<label class="positionHolder"><a href="{{ route('user.profile', ['slug' => $collectible->designer_profile]) }}">{{ $collectible->designer }}</a></label>
							</div>
						</div>

						@foreach($collectible->owners as $owner)

						<div class="row dtab">
							<div class="col-3 col-md-2">
								<div class="inlineDiv">
									<a href="{{ route('user.profile', ['slug' => $owner->owner_url]) }}">
										<img class="br-50" src="{{ asset('/'.$owner->asset_url) }}" width="50" class="">
									</a>
									<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
								</div>
							</div>
							<div class="col-9 col-md-10">
								<label class="position">Holder</label>
								<label class="positionHolder"><a href="{{ route('user.profile', ['slug' => $owner->owner_url]) }}">{{ $owner->owner_name }}</a></label>
							</div>
						</div>

						@endforeach
						

						<div class="row dtab">
							<div class="col-3 col-md-2">
								<div class="inlineDiv">
									<img class="br-50" src="{{ asset('/'.$collectible->collection_image) }}" width="50" class="">
									<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
								</div>
							</div>
							<div class="col-9 col-md-10">
								<label class="position">Collection</label>
								<label class="positionHolder">{{ $collectible->collection }}</label>
							</div>
						</div>


					</div>


					<div id="menu1" class="tab-pane">
						@foreach($collectible->owners as $owner)

						<div class="row dtab">
							<div class="col-3 col-md-2">
								<div class="inlineDiv">
									<a href="{{ route('user.profile', ['slug' => $owner->owner_url]) }}">
										<img class="br-50" src="{{ asset('/'.$owner->asset_url) }}" width="50" class="">
									</a>
									<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
								</div>
							</div>
							<div class="col-9 col-md-10">
								<label class="position">Holder</label>
								<label class="positionHolder"><a href="{{ route('user.profile', ['slug' => $owner->owner_url]) }}">{{ $owner->owner_name }}</a></label>
							</div>
						</div>

						@endforeach
					</div>


					<div id="menu2" class="tab-pane">
						@foreach($transactions as $transac)
						<div class="row dtab">
							<div class="col-3 col-md-2">
								<div class="inlineDiv">
									<a href="{{ route('user.profile', ['slug' => $transac->user_profile]) }}">
										<img class="br-50" src="{{ asset('/'.$transac->user_image) }}" width="50" class="">
									</a>
									<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
								</div>
							</div>
							<div class="col-9 col-md-10">
								<label class="position">{{ $transac->action }} <span class="positionHolder">{{ $transac->price }}</span> {{ $transac->sub_context }}{{ $transac->time_ago}} by <a href="{{ route('user.profile', ['slug' => $transac->user_profile]) }}"><span class="positionHolder">{{ $transac->user_name }}</span></a></label>
							</div>
						</div>
						@endforeach
					</div>
				</div>

			</div>

			<div class="row m-20 text-center end-content">
				<div class="col-4 col-md-4">
					<label class="position">Available</label>
					<label class="positionHolder">{{ $collectible->available }}</label>
				</div>
				<div class="col-4 col-md-4">
					<label class="position">Quantity</label>
					<span class="quantity-btn positionHolder">1 <i class="fa fa-angle-down"></i></span>

					<div class="quantity-drop d-none">
						@for($x=1; $x<=$collectible->quantity; $x++)
						<div class="drop-group">
							<a href="javascript:void(0)" id="{{ $x }}" class="quantity-item">{{ $x }}</a>
						</div>
						@endfor
					</div>
				</div>
				<div class="col-4 col-md-4">
					<label class="position">Pay with</label>
					<span class="checkout-currency positionHolder">BHC <i class="fa fa-angle-down"></i></span>

					<div class="checkout-drop d-none">
						<div class="drop-group">
							<a href="javascript:void(0)" id="BHC" class="side-drop currency-item">BHC</a>
							<i class="fa fa-check currency-check"></i>
						</div>
						<div class="drop-group">
							<a href="javascript:void(0)" id="BNB" class="side-drop currency-item">BNB</a>
							<i class="fa fa-check currency-check opacity-0"></i>
						</div>
					</div>
				</div>
			</div>

			<div class="buy-container {{ $collectible->isp == 0 ? 'd-none' : '' }} ">
				<button id="buyBtn" class="buyBtn d-none d-md-block">Buy 1 for {{ $collectible->price }}</button>

				<p class="text-gray text-center d-none d-md-block">Service fee 1.5% {{ $collectible->price }} = <span class="text-dark-gray">$137.228</span></p>

				<div class="show-footer-btn d-block d-md-none">
					<button class="buyBtn">Buy 1 for 110.15 BHC</button>

					<p class="text-gray text-center">Service fee 1.5% 110.15 BHC = <span class="text-dark-gray">$137.228</span></p>
				</div>
			</div>

			<div class="bid-container {{ $collectible->isp == 1 ? 'd-none' : '' }}">
				<button id="bidBtn" class="buyBtn d-none d-md-block">Place a bid</button>
			</div>


		</div>

		<div class="col-sm-12 col-md-8 col-lg-8 col-xl-8 custom-lg-8 showContent my-auto first-mobile">
			<div class="inner-img">

				<div class="mobile-imgHead">
					<a class="mobile-show-link" href="javascript:void(0)">
						<i class="fa fa-heart"></i>
					</a>

					<a class="mobile-show-link" href="javascript:void(0)">
						<i class="fa fa-arrows-alt"></i>
					</a>
				</div>
				<div class="collectible-container">

					@if($collectible->type == 'image')
					<img class="showImg" src="{{ asset('storage/collectibles/'.$collectible->nft) }}">
					@else
					<video class="showImg" autoplay loop controls muted>
					      <source src="{{ asset('storage/collectibles/'.$collectible->nft) }}" type="video/mp4">
					</video>
					@endif

					<div class="show-nft-option imgHead d-none d-md-block">
						@if(Auth::check())
						<a id="like-btn" data-nft-slug="{{ $collectible->nft_slug }}" class="imgHead-link {{ $onWishList == true ? 'nft-liked' : '' }}" href="javascript:void(0)">
							<i class="fa fa-heart nft-option"></i>
						</a>
						@endif

						<a id="nft-expand" class="imgHead-link" href="javascript:void(0)">
							<i class="fa fa-arrows-alt expand nft-option"></i>
						</a>
					</div>
				</div>
			</div>
			
			<img class="showImgBg d-none d-md-block" src="{{ asset('images/right.png') }}">
		</div>


	</div>

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
			<p>by <span> {{$collectible->designer}} </span> on <span>{{$collectible->collection}}</span></p>
		</div>
	</div>

	@include('includes.show-modal')


@section('extra_scripts')
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
				console.log("hey");
	            container.toggleClass('d-none');
	            container.addClass("fade-in-top").removeClass("fade-out-top");
	        }
	        else 
	        {
	        	console.log("here");
	            container.addClass("fade-out-top").removeClass("fade-in-top");
	            setTimeout(function() {
	              container.toggleClass('d-none');
	            }, 400);
	        }
		}

		
	</script>
@endsection

@endsection
