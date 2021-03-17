@extends('layout.app')

@section('content')

	<div class="collection-page">
		<div class="collection-cover"></div>

		<collection-info-component
			:asset_url="{{ json_encode(asset('/')) }}"
			:collection="{{ json_encode($collection) }}"
		></collection-info-component>


		<div class="collection-collectibles">
			<collection-collectible-component
				:div_id="'collection-page'"
				:collectible_asset="{{ json_encode(URL::to('/storage/collectibles').'/') }}"
				:show_collectible="{{ json_encode(URL::to('/nft').'/') }}"
				:current_user="{{ json_encode(Auth::check() ? Auth::user()->id : 0) }}"
				:base_url="{{ json_encode(URL::to('/')) }}"
				:collectibles="{{ json_encode($collectibles) }}"
				:page="'collection'"
				:filter="'on-sale'"
				:collection="{{ json_encode($collection) }}"
			></collection-collectible-component>
		</div>

	</div>


	@section('extra_scripts')
	<script src="{{ asset('js/custom.js') }}"></script>
	<script src="{{ asset('js/user_profile.js') }}"></script>
	@endsection

@endsection