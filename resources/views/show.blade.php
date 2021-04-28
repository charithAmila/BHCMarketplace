@extends('layout.app')

@section('content')
		
<collectible-page :contract="{{ json_encode($contract) }}" :owner="{{ json_encode($owner) }}" :id="{{ json_encode($id) }}" :asset_url="{{ json_encode(asset('/')) }}" :user_profile="{{ json_encode(URL::to('/profile/')) }}" :base_url="{{ json_encode(URL::to('/')) }}"></collectible-page>

<button onclick="popupwindow()">Click me</button>




@include('includes.show-modal')


@section('extra_scripts')
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/show-collectible.js') }}"></script>

<script>
	function popupwindow() {
	  var title = "test0";
	  var url = "https://beta.billionhappiness.finance/nft";
	  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=1000, height=500');
	}
</script>

@endsection

@endsection