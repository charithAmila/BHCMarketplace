@extends('layout.app')

@section('content')

<div class="about-bhc">
	<bhc-heading-component :asset_url="{{ json_encode(asset('/')) }}"></bhc-heading-component>

	<bhc-body-component></bhc-body-component>

	<bhc-how-component></bhc-how-component>
	
</div>


@include('includes.footer')
@endsection