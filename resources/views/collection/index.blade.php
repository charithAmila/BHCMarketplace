@extends('layout.app')

@section('content')

<div class="collection-page">
	<div class="collection-cover"></div>

	<collection-page :asset_url="{{ json_encode(asset('/')) }}" :collectible_asset="{{ json_encode(URL::to('/storage/collectibles').'/') }}" :show_collectible="{{ json_encode(URL::to('/nft').'/') }}" :base_url="{{ json_encode(URL::to('/')) }}" :collection="{{ json_encode($collection ?? '') }}"></collection-page>

</div>


@section('extra_scripts')
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/user_profile.js') }}"></script>
@endsection

@endsection