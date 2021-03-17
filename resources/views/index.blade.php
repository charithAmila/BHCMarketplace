@extends('layout.app')

@section('extra_css')
	<link rel="stylesheet" href="{{ mix('css/main.css') }}">
@endsection
@section('content')
<toast-component :asset_url="{{ json_encode(asset('/')) }}"></toast-component>

<div class="main-content">
	<main-page-component
		:collectible_asset="{{ json_encode(asset('storage/collectibles').'/') }}"
		:show_collectible="{{ json_encode(asset('/nft').'/') }}"
		:current_user="{{ json_encode(Auth::check() ? Auth::user()->id : 0) }}"
		:base_url="{{ json_encode(URL::to('/')) }}"
		:asset_url="{{ json_encode(asset('/')) }}"
	>	
	</main-page-component>
</div>


@section('extra_scripts')
	<script src="{{ asset('js/custom.js') }}"></script>
	<script src="{{ asset('js/marketplace.js') }}"></script>
@endsection


@endsection
