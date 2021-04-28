@extends('layout.app')

@section('content')
		
<collectible-page :contract="{{ json_encode($contract) }}" :owner="{{ json_encode($owner) }}" :id="{{ json_encode($id) }}" :asset_url="{{ json_encode(asset('/')) }}" :user_profile="{{ json_encode(URL::to('/profile/')) }}" :base_url="{{ json_encode(URL::to('/')) }}"></collectible-page>

@include('includes.show-modal')


@section('extra_scripts')
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/show-collectible.js') }}"></script>

@endsection

@endsection