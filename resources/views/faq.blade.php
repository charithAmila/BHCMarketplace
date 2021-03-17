@extends('layout.app')

@section('content')

<div class="faq-container">
	<faq-heading-component :asset_url="{{ json_encode(asset('/')) }}"></faq-heading-component>
	<faq-body-component></faq-body-component>
</div>



@include('includes.footer')

@section('extra_scripts')
	<script>
		$(document).ready(function(){
			$('.arrow-link').click(function(){
				if ($(this).siblings().children('.answer').hasClass('d-none')) {
					$(this).siblings().children('.answer').removeClass('d-none');
					$('.display-flex').removeClass('lightBlue-bg');
					$(this).parent().addClass('lightBlue-bg');
					$(this).children('.arrow-drop').removeClass('rotate-out').addClass('rotate-in');
				}else{
					$(this).siblings().children('.answer').addClass('d-none');
					$(this).parent().removeClass('lightBlue-bg');
					$(this).children('.arrow-drop').removeClass('rotate-in').addClass('rotate-out');
				}
			});
		});
	</script>
@endsection
@endsection