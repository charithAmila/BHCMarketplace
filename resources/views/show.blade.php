@extends('layout.app')

@section('content')
	<show-collectible-component
		:collectible="{{ json_encode($collectible) }}"
		:transactions="{{ json_encode($transactions) }}"
		:onWishList="{{ json_encode($onWishList) }}"
		:is_liked="{{ json_encode($onWishList) }}"
		:asset_url="{{ json_encode(asset('/')) }}"
		:auth_check="{{ json_encode(Auth::check()) }}"
		:user_profile="{{ json_encode(URL::to('/profile/')) }}"
		:current_user="{{ json_encode(Auth::check() ? Auth::user()->id : 0) }}"
		:base_url="{{ json_encode(URL::to('/')) }}"
	></show-collectible-component>

	<show-fullscreen-component
		:collectible="{{ json_encode($collectible) }}"
		:asset_url="{{ json_encode(asset('/')) }}"
	></show-fullscreen-component>

	@include('includes.show-modal')


@section('extra_scripts')
	<script src="{{ asset('js/custom.js') }}"></script>
	<script src="{{ asset('js/show-collectible.js') }}"></script>
	
@endsection

@endsection
