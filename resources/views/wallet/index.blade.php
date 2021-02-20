@extends('layout.default')

@section('content')

	<div class="row row-0 walletSection connect-wallet">
		<div class="col-md-3 d-none d-md-block">
		</div>
		<div class="col-12 col-md-6 withContent my-auto">
			<div>
				<a class="btn content-title" href="{{ url()->previous() }}" id="box">Go back <i class="fas fa-angle-double-left"></i></a>
			</div>
			<h1 class="content-title mb-0 pr-20 mobile-padding-top">Connect your wallet</h1>
			<p class="text-bold pr-30">Sign up with one of available wallet providers</p>

			<div class="row walletconnect">
				<div class="col-sm-12 col-md-6 wallet-link">
					<div id="wallet_item">
						<img src="{{ asset('images/metamask.png') }}">
						<h6 class="content-title">Metamask</h6>
					</div>
				</div>
				<!-- <div class="col-sm-12 col-md-6">
					<div id="wallet_item">
						<img src="{{ asset('images/fortmatic.png') }}">
						<h6 class="content-title">Fortmatic</h6>
					</div>
				</div> -->
				<div class="col-sm-12 col-md-6 wallet-link">
					<div id="wallet_item">
						<img src="{{ asset('images/walletconnect.png') }}">
						<h6 class="content-title">WalletConnect</h6>
					</div>
				</div>
				<!-- <div class="col-sm-12 col-md-6">
					<div id="wallet_item">
						<img src="{{ asset('images/walletlink.png') }}">
						<h6 class="content-title">WalletLink</h6>
					</div>
				</div> -->
			</div>

			<h4 class="content-title mb-0 mtext-title">Non-custodial & Secure</h4>
			<p class="text-bold pl-40">We do not own your private keys and cannot access your funds without your confirmation.</p>
		</div>

		<div class="col-sm-12 col-md-3 withBg color-change-3x">
			<a class="d-none d-md-block" href="{{ route('marketplace') }}">
				<img class="sidebarLogo" src="{{ asset('images/logo.png') }}">
			</a>
			<div class="my-auto">
				<div class="imgSmall imgPos1">
					<img class="sidebarImg" src="{{ asset('images/1_icon1.png') }}">
				</div>
				<div class="imgLarge imgPos2">
					<img class="sidebarImg" src="{{ asset('images/1_icon2.png') }}">
				</div>
					<div class="imgNormal imgPos3">
				<img class="sidebarImg" src="{{ asset('images/1_icon3.png') }}">
				</div>
				<div class="imgNormal imgPos4">
					<img class="sidebarImg" src="{{ asset('images/1_icon4.png') }}">
				</div>
			</div>
		</div>
	</div>


	<div class="fixed-social-links d-none d-md-block">
        <a href="https://twitter.com/bhc_happiness" target="_blank">
            <img src="{{ asset('images/twitter.png') }}"> Follow us on Twitter
          </a>
          <a href="https://t.me/BillionHappinessOfficial" target="_blank">
            <img src="{{ asset('images/telegram.png') }}"> Join our Telegram community
          </a>
        <a href="javascript:void(0)">
          <img src="{{ asset('images/message.png') }}"> Community Feedback
        </a>
    </div>



    <wallet-component :laravel_asset="{{ json_encode(asset('/')) }}" :create_user="{{ json_encode(route('create.user')) }}"></wallet-component>



	@section('extra_scripts')
		<script src="{{ asset('js/custom.js') }}"></script>
		<script>
			$(document).ready(function(){

				$('.wallet-link').click(function(){
		            modalOpen($('#loginModal'), $(".login-content"));
		        });
		        $('.close-login-modal').click(function(){
		            modalClose($('#loginModal'), $(".login-content"));
		        });

			});
		</script>

	@endsection
    


@endsection
