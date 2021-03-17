@extends('layout.app')

@section('extra_css')
	

	<div class="search-page">
		<collectible-component 
			:div_id="'div_id'"
			:collectible_asset="{{ json_encode(asset('storage/collectibles').'/') }}"
			:show_collectible="{{ json_encode(asset('/nft').'/') }}"
			:current_user="{{ json_encode(Auth::check() ? Auth::user()->id : 0) }}"
			:base_url="{{ json_encode(URL::to('/')) }}"
			:asset_url="{{ json_encode(asset('/')) }}"
			:collectibles="{{ json_encode($collectibles) }}"
			:page="'search'"
		></collectible-component>
	</div>

	@section('extra_scripts')
	<script src="{{ asset('js/custom.js') }}"></script>
	@endsection

@endsection