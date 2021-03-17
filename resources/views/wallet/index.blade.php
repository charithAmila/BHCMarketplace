@extends('layout.default')

@section('content')

	<div class="row row-0 walletSection connect-wallet">
		<div class="col-md-3 d-none d-md-block">
		</div>
		<div class="col-12 col-md-6 withContent my-auto">

			<wallet-sidebar-component
				:url_previous="{{ json_encode(url()->previous()) }}"
				:asset_url="{{ json_encode(asset('/')) }}"
			></wallet-sidebar-component>
			
		</div>

		<div class="col-sm-12 col-md-3 withBg color-change-3x">
			<wallet-body-component :marketplace_route="{{ json_encode(route('marketplace'))}}" :asset_url="{{ json_encode(asset('/')) }}"></wallet-body-component>
		</div>
	</div>


	<custom-social-link-component :asset_url="{{ json_encode(asset('/')) }}"></custom-social-link-component>



    <wallet-component :laravel_asset="{{ json_encode(asset('/')) }}" :create_user="{{ json_encode(route('create.user')) }}"></wallet-component>



	@section('extra_scripts')
		<script src="{{ asset('js/custom.js') }}"></script>
		<!--script>
			$(document).ready(function(){

				$('.wallet-link').click(function(){
		            modalOpen($('#loginModal'), $(".login-content"));
		        });
		        $('.close-login-modal').click(function(){
		            modalClose($('#loginModal'), $(".login-content"));
		        });

			});
		</script-->

	@endsection
    


@endsection
