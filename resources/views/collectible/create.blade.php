@extends('layout.app')

@section('extra_css')
	<link rel="stylesheet" href="{{ mix('css/main.css') }}">
@endsection

@section('content')
	
		
	<div class="create-tab">
		<div class="row create-content">
			<div class="col-sm-12 col-md-4 d-none d-md-block"></div>
			<div class="col-sm-12 col-md-4 create-section">
				<div class="inner-section my-auto">
					<index-create-header-component :previous_url="{{ json_encode(url()->previous()) }}"></index-create-header-component>
					
					<index-create-body-component 
						:solo="{{ json_encode(url('/create/solo-collectible')) }}"
						:multiple="{{ json_encode(url('/create/multiple-collectible')) }}"
						:asset_url="{{ json_encode(asset('/')) }}"
					></index-create-body-component>
				</div>
				
			</div>

			<div class="col-sm-12 col-md-4 d-none d-md-block">
				<index-create-right-component :asset_url="{{ json_encode(asset('/')) }}" ></index-create-right-component>
			</div>
		</div>
		
	</div>


	@include('includes.footer')
@endsection