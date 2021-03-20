@extends('layout.app')


@section('content')

<toast-component :asset_url="{{ json_encode(asset('/')) }}"></toast-component>

<div class="create-tab">
	<div class="row create-content">
		<div class="col-md-2 d-none d-md-block">
			<social-link-component :asset_url="{{ json_encode(asset('/')) }}"></social-link-component>
		</div>

		<div class="col-12 col-md-6">
			<create-collectible-component :collections="{{ json_encode($collections)}}" :categories="{{ json_encode($categories)}}" :legends="{{ json_encode($legends)}}" :type="{{ json_encode($type)}}" :asset_url="{{ json_encode(asset('/')) }}" :create_route="{{ json_encode(route('create.collectible.choices')) }}" :store_route="{{ json_encode(route('create.collectible')) }}" :csrf_token="{{ json_encode(csrf_token()) }}"></create-collectible-component>
		</div>

		<div class="col-md-4 d-none d-md-block">
			<right-background-component :asset_url="{{ json_encode(asset('/')) }}"></right-background-component>
		</div>
	</div>

</div>




<create-steps-modal-component></create-steps-modal-component>


@section('extra_scripts')
<!--script src="{{ asset('js/create-collectible.js')}}"></script-->
@endsection


@endsection