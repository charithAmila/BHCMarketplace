@extends('layout.app')

@section('extra_css')


<search-page :query="{{ json_encode($query) }}" :csrf_token="{{ json_encode(csrf_token()) }}" :collectible_asset="'collectible_aasest'" :show_collectible="{{ json_encode(asset('/search').'/') }}" :base_url="{{ json_encode(URL::to('/')) }}" :asset_url="{{ json_encode(asset('/')) }}" :marketplace_url="{{ json_encode(route('marketplace')) }}"></search-page>


@section('extra_scripts')
<script src="{{ asset('js/custom.js') }}"></script>
@endsection

@endsection
