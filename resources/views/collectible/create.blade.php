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
					<div>
						<a class="btn" href="{{ url('/') }}" id="box">Go back <i class="fas fa-angle-double-left"></i></a>
					</div>
					<div class="main-create">
						<h2>Create Collectible</h2>
						<p class="desc">Choose "Solo" if you want your collectible to be one of a kind or "Batch" if you want to sell one collectible multiple times</p>
						<div class="row">
							<div class="col-md-6">
								<a href="{{ url('/create/solo-collectible') }}" class="g_select">
									<div id="box">
										<div class="col-content">
											<img src="{{ asset('images/solo.png') }} ">
											<h4>Solo</h4>
										</div>
									</div>
								</a>
							</div>
							<div class="col-md-6">
								<a href="{{ url('/create/multiple-collectible') }}" class="g_select">
									<div id="box">
										<div class="col-content">
											<img src="{{ asset('images/batch.png') }} ">
											<h4>Batch</h4>
										</div>
									</div>
								</a>
							</div>
						</div>
						<p class="desc-bottom">We do not own your private keys and cannot access your funds without your confirmation.</p>
					</div>
				</div>
				
			</div>

			<div class="col-sm-12 col-md-4 d-none d-md-block">
				<div class="right-img">
					<img src="{{ asset('images/right.png') }}">
				</div>
			</div>
		</div>
		
	</div>


	@include('includes.footer')
@endsection