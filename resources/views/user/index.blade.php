@extends('layout.app')

@section('content')

<div class="user-profile-page">
	<profile-page :user_id="{{ json_encode($address) }}" :csrf_token="{{ json_encode(csrf_token()) }}" :collectible_asset="{{ json_encode(asset('storage/collectibles').'/') }}" :show_collectible="{{ json_encode(asset('/nft').'/') }}" :base_url="{{ json_encode(URL::to('/')) }}" :asset_url="{{ json_encode(asset('/')) }}" :marketplace_url="{{ json_encode(route('marketplace')) }}"></profile-page>
</div>


@section('extra_scripts')
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/user_profile.js') }}"></script>
@endsection

@endsection