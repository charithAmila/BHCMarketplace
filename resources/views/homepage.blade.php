@extends('layout.default')

@section('content')

	<div class="row row-0 walletSection">
		
		<div id="second" class="col-xs-12 col-sm-12 col-md-9 withContent my-auto">
			<landing-body-component
				:create_collectible_route="{{ json_encode(route('create.collectible.choices')) }}"
				:marketplace_route="{{ json_encode(route('marketplace')) }}"
			></landing-body-component>
		</div>


		<div id="first" class="col-xs-12 col-sm-12 col-md-3 withBg color-change-3x">
			<landing-sidebar-component
				:wallet_route="{{ json_encode(route('connect.wallet')) }}"
				:marketplace_route="{{ json_encode(route('marketplace')) }}"
				:asset_url="{{ json_encode('/') }}"
			></landing-sidebar-component>
		</div>
	</div>

	<social-link-component :asset_url="{{ json_encode(asset('/')) }}"></social-link-component>


	



@endsection
