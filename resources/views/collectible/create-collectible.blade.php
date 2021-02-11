@extends('layout.app')


@section('content')

	<div id="toast">
		<img src="{{asset('images/logo.png')}}">
		<div id="desc" class="toast-message">A notification message..</div>
	</div>

	<div class="create-tab">
		<div class="row create-content">
			<div class="col-md-2 d-none d-md-block">
				<div class="social-collectible">
					<a href="https://twitter.com/bhc_happiness" target="_blank">
						<img src="{{ asset('images/twitter.png') }}"> Follow us on Twitter
					</a>
					<a href="https://t.me/BillionHappinessOfficial" target="_blank">
						<img src="{{ asset('images/telegram.png') }}"> Join our Telegram community
					</a>
					<a href="javascript:void(0)">
						<img src="{{ asset('images/message.png') }}"> Community Feedback
					</a>
				</div>
			</div>

			<div class="col-12 col-md-6">
				<div class="back-btn">
					<a class="btn" href="{{ url('/create') }}" id="box">Go back <i class="fas fa-angle-double-left"></i></a>
				</div>
				<div class="solo-create">
					<div class="row">
						<div class="col-12 col-md-8">
							<h4 class="content-title text-head">Create {{ ucwords($type) }} Collectible</h4>
							<form id="create-collectible" data-collectible-type="{{ $type }}" method="POST" action="{{ route('create.collectible') }}">
								@csrf
								<div class="form-group">
									<h6 class="content-title text-upload">Upload file</h6>
									<div class="fileArea">
										<div class="file-input">
											<p id="sizeInvalid" class="text-danger d-none">File too Big, please select a file less than 50mb</p>
											<p id="fileInvalid" class="text-danger d-none">Invalid file format</p>
											<p id="fileLabel">GIF, PNG, JPEG, WEBP, MP4, or MP3. Max size 50mb</p>
											<input type="file" id="selectedFile" name='nft' class="to-check" />
											<input class="fileBtn" type="button" value="Select File" onclick="document.getElementById('selectedFile').click();" />
										</div>
										<p class="this-error text-danger"></p>
										<span id="nft-validation" class="custom-error text-danger"></span>
										<div class="file-output d-none">
											<img src="" class="category-img-tag"/>
											<span class="close-btn"><i class="fa fa-times"></i></span>
										</div>
										<div class="file-output-video d-none">
											<video id="video_here" controls autoplay loop muted>
											  <source src="mov_bbb.mp4" class="category-video-tag" >
											    Your browser does not support HTML5 video.
											</video>

											<span class="close-btn-video"><i class="fa fa-times"></i></span>

										</div>
									</div>
									
								</div>

								<h6 class="content-title text-content">Access once purchased</h6>
								<div class="row">
									<div class="col-9 col-md-10">
										<label class="smallDesc">Content below and media file will be accessible after successfull transaction</label>
									</div>
									<div class="col-3 col-md-2">
										<label class="switch">
										  <input id="accessToggle" name='aop' type="checkbox">
										  <span class="slider round"></span>
										</label>
									</div>
								</div>

								<div class="form-group digital-link d-none">
									<div>
										<input id="d-link" class="inp" type="text" name="aop_link" placeholder="Digital key, code to redeem or link to a file"/>
									</div>
									<p class="this-error text-danger"></p>
									<span id="aop_link-validation" class="custom-error text-danger"></span>
									<small class="faded-text">Tip: Markdown syntax is supported</small>
								</div>

								<h6 class="content-title text-content">Select Collection</h6>


								<div id="collection-group">
								
									<a href="javascript:void(0)" class="generateBtn generateBtn">
										<div class="outside">
										  <div class="inside">
										  	<div class="inner-outside">
										  		<div class="inner generate_collection">
										  			<i class="fa fa-plus"></i>
										  			<h6>Generate collection</h6>
										  		</div>
										  	</div>
										  </div>
										</div>
									</a>

									@foreach($collections as $key=>$collection)

									<a id="{{ $collection->id }}" href="javascript:void(0)" class="g_select {{ $key == 0 ? 'active-btn' : 'inactive-btn' }}">
										<div class="outside">
										  <div class="inside">
										  	<div class="inner-outside">
										  		<div class="inner">
										  			@if($collection->default == 1)
										  			<img class="collection-logo" src="{{ asset('collections/'.$collection->image) }}">
										  			@else
										  			<img class="collection-logo" src="{{ asset('storage/collections/'.$collection->image) }}">
										  			@endif
										  			<h6>{{ $collection->display_name }} {{ $collection->symbol }}</h6>
										  		</div>
										  	</div>
										  </div>
										</div>
									</a>

									@endforeach

								</div>

								

								<div class="form-group mt-8">
									<div>
										<h6 class="content-title text-content mb-0">Name</h6>
										<input class="inp to-check" type="text" name="collectible_name" placeholder="E.g 'Happiness Qualitees'"/>
									</div>
									<p class="this-error text-danger"></p>
									<span id="name-validation" class="custom-error text-danger"></span>
								</div>

								<div class="form-group">
									<h6 class="content-title text-content text-content mb-0">Description <span class="optional">(Optional)</span></h6>
									<input class="inp" type="text" name="description" placeholder="E.g 'Every successfull transaction youll be able to claim this unique tees'"/>
									<small class="faded-text">With preserve line-breaks</small>
								</div>

								 <div class="form-group">
								 	<div class="row">
								 		<div class="legend col-6 col-md-6 col-lg-6 col-xl-6">
								 			<h6 class="content-title text-content text-content mb-0">Category</h6>
								 			<div>
											    <select class="form-control to-check" id="category-drop" name="category_id">
											    	<option value=""> -- Please select category --</option>
											    	@foreach($categories as $category)
											    	<option class="special" value="{{ $category->id }}">{{ ucwords($category->category) }}</option>
											    	@endforeach
											    </select>
								 			</div>
										    <p class="this-error text-danger"></p>
											<span id="legend-validation" class="custom-error text-danger"></span>
								 		</div>
								 		<div class="category col-6 col-md-6 col-lg-6 col-xl-6">
								 			<h6 class="content-title text-content text-content mb-0">Legend</h6>
								 			<div>
											    <select class="form-control to-check" id="legend-drop" name="legend_id">
											    	<option value=""> -- Please select legend --</option>
											     	@foreach($legends as $legend)
											    	<option class="special" value="{{ $legend->id }}">{{ ucwords($legend->legend) }}</option>
											    	@endforeach
											    </select>
								 			</div>
										    <p class="this-error text-danger"></p>
											<span id="category-validation" class="custom-error text-danger"></span>
								 		</div>
								 	</div>
								   
								  </div>

								<div class="form-group">
									<div class="row">
										<div class="col-12 {{ $type == 'multiple' ? 'col-md-6' : 'col-md-12' }}">
											<h6 class="content-title text-content text-content mb-0">Royalties</h6>
											<div>
												<input class="inp to-check" type="number" name="royalties" placeholder="E.g '20%'" value="20" />
											</div>
											<p class="this-error text-danger"></p>
											<span id="royalties-validation" class="custom-error text-danger"></span>
											<small class="faded-text">Recommended 5%, 10%, 15%, 20%, 30%, 35%, 40%, 50%</small>
										</div>
										@if($type == 'multiple')
										<div class="col-12 col-md-6">
											<h6 class="content-title text-content text-content mb-0">Number of copies</h6>
											<div>
												<input class="inp to-check" type="number" name="copies" placeholder="E.g '10'" />
											</div>
											<p class="this-error text-danger"></p>
											<span id="copies-validation" class="custom-error text-danger"></span>
											<small class="faded-text">Amount of tokens</small>
										</div>
										@endif
									</div>
								</div>

								<div class="form-group">
									<h6 class="content-title text-content text-content mb-0">Properties <span class="optional">(Optional)</span></h6>

									<div id="properties-field">

										<div class="row">
											<div class="col-6 col-md-6">
												<input class="inp bold-placeholder provided-key input-head" type="text" name="key[]" placeholder="Provided Key"/>
											</div>
											<div class="col-6 col-md-6">
												<input class="inp bold-placeholder provided-value" type="text" name="value[]" placeholder="Provided Value"/>
											</div>
										</div>

									</div>
									
								</div>


								<div class="form-group row">
									<div class="col-9 col-md-10">
										<h6 class="content-title text-content mb-0">Put on sale</h6>
									</div>
									<div class="col-3 col-md-2 pt-14">
										<label class="switch">
										  <input id="putSale" name="pos" type="checkbox" checked>
										  <span class="slider round"></span>
										</label>
									</div>
								</div>

								<div class="form-group row instant-sale">
									<div class="col-9 col-md-10">
										<h6 class="content-title text-content mb-0">Instant sale price</h6>
										<small>Enter the price for which the item will be instantly sold</small>
									</div>
									<div class="col-3 col-md-2 pt-14">
										<label class="switch">
										  <input id="instantSale" name="isp" type="checkbox">
										  <span class="slider round"></span>
										</label>
									</div>
								</div>

								<div class="form-group row price-tag d-none">
									<div class="col-12 col-md-12">
										<input class="inp sale-price" type="number" name="price" placeholder="Enter price for 1 piece">
										<span id="BHC" class="link-url-end sale-price-btn">BHC <i class="fa fa-angle-down"></i></span>

										<div class="sale-price-drop d-none">
											<div class="drop-group">
												<a href="javascript:void(0)" id="BHC" class="currency-item">BHC</a>
												<i class="fa fa-check currency-check"></i>
											</div>
											<div class="drop-group">
												<a href="javascript:void(0)" id="BNB" class="currency-item">BNB</a>
												<i class="fa fa-check currency-check opacity-0"></i>
											</div>
										</div>
									</div>
									<p class="this-error text-danger pl-15"></p>
									<span id="price-validation" class="custom-error text-danger pl-15"></span>
								</div>

								<div class="form-group row">
									<div class="error-msg col-12 col-md-12 d-none">
										<p class="text-danger sum-error">
											Something wrong. Please fix the errors in fields above and try again.
										</p>
									</div>
									<div class="col-6 col-md-6">
										<input id="createCollectible" class="submitBtn" type="button" name="" value="Generate Item">
									</div>
									<div class="col-2 col-md-2"></div>
									<div class="col-4 col-md-4 p-0">
										<label class="boldFade">disregard update</label>
									</div>
								</div>
							</form>

							<div class="sampleLoad"></div>
						</div>



						<div class="col-md-4 d-none d-md-block">
							<div class="preview-pane">
								<h6 class="content-title">Preview</h6>
								<div class="preview">
									<div class="outside">
									  <div class="inside">
									  	<div class="inner-outside">
									  		<div class="inner">
									  			<div class="preview-desc">
									  				<small class="prevLabel">Preview of your new artworks or collectibles</small>
									  			</div>
									  			<div class="image-container d-none">
									  				<img src="" class="category-img-tag"/>
									  			</div>
									  			<div class="video-container d-none">
									  				<video controls autoplay loop muted>
													  <source src="mov_bbb.mp4" class="category-video-prev" >
													    Your browser does not support HTML5 video.
													</video>
									  				
									  			</div>
									  		</div>
									  	</div>
									  </div>
									</div>
								</div>
							</div>
						</div>

					</div>
					
				</div>
			</div>

			<div class="col-md-4 d-none d-md-block">
				<div class="right-img-solo">
					<img src="{{ asset('images/right.png') }}">
				</div>
			</div>
		</div>
		
	</div>


	<div id="collectionModal" class="custom-modal">
	  <div class="modal-content collection-content">
	    <div class="modal-head">
	      <h3>Collection</h3>
	      <span class="close-custom-modal">&times;</span>
	    </div>
	    <div class="modal-body">
	    	<form id="create-collection" method="POST" action="{{ route('create.collection') }}">
			@csrf
				<div class="row">
					<div class="collection-container col-5 col-md-5">
						<img class="collection-logo" src="{{ asset('images/avatar2.png')}}" alt="collection-logo">
					</div>
					<div class="col-label col-7 col-md-7">
						<label>Allowed png, gif, jpg. 160x160px Recommended</label>
						<input type="file" id="collection-file" name='image' class="collection-check" accept="image/x-png,image/gif,image/jpeg"/>
						<input class="collection-btn" type="button" value="Set picture" onclick="document.getElementById('collection-file').click();" />
						<p class="collection-error text-danger"></p>
						<span id="image-collection" class="custom-error text-danger"></span>
					</div>
				</div>

				<div class="form-section">
					<div class="form-divide">
						<label class="input-label">Display name <small>(required)</small></label>
						<input class="modal-input collection-check collection-input" type="text" name="display_name" placeholder="Enter token name">
						<p class="collection-error text-danger"></p>
						<span id="display_name-collection" class="custom-error text-danger"></span>
						<label class="desc-url">Token name cannot be changed in future</label>
					</div>

					<div class="form-divide">
						<label class="input-label">Symbol <small>(required)</small></label>
						<input class="modal-input collection-check collection-input" type="text" name="symbol" placeholder="Enter token symbol">
						<p class="collection-error text-danger"></p>
						<span id="symbol-collection" class="custom-error text-danger"></span>
					</div>

					<div class="form-divide">
						<label class="input-label">Description</label>
						<input class="modal-input collection-input" type="text" name="description" placeholder="Spread some words about your token collection">
						<span id="image-collection" class="custom-error text-danger"></span>
					</div>

					<div class="form-divide">
						<label class="input-label">Short url</label>
						<span class="link-url">billion.com/</span>
						<input class="modal-input with-link collection-input" type="text" name="short_url" placeholder="Enter short url">
						<span id="short_url-collection" class="custom-error text-danger"></span>
					</div>
					<button id="collection-submit" class="form-submit" type="button">Create collection</button>
				</div>
			</form>
	    </div>
	    
	  </div>

	</div>

	<div id="create-stepsModal" class="custom-modal">

	  <div class="modal-content create-steps-content">
	    <div class="modal-head">
	      <h3>Follow steps</h3>
	      <span class="close-steps-modal">&times;</span>
	    </div>
	    <div class="modal-body">
	    	<div class="row">
	    		<div class="col-3 col-md-3 col-lg-3">
	    			<i class="fa fa-check fa-2x"></i>
	    		</div>
	    		<div class="col-9 col-md-9 col-lg-9">
	    			<h4>Approve</h4>
	    			<label>Approve performaing transactions with your wallet</label>
	    		</div>
	    		<div class="col-12 col-md-12">
	    			<button class="form-submit" type="button">Start</button>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-3 col-md-3 col-lg-3">
	    			<i class="fa fa-check fa-2x"></i>
	    		</div>
	    		<div class="col-9 col-md-9 col-lg-9">
	    			<h4>Upload files</h4>
	    			<label>Prepare files for minting</label>
	    		</div>
	    		<div class="col-12 col-md-12">
	    			<button class="form-submit" type="button">Start</button>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-3 col-md-3 col-lg-3">
	    			<i class="fa fa-check fa-2x"></i>
	    		</div>
	    		<div class="col-9 col-md-9 col-lg-9">
	    			<h4>Mint token</h4>
	    			<label>Call contract method</label>
	    		</div>
	    		<div class="col-12 col-md-12">
	    			<button class="form-submit" type="button">Start</button>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="col-3 col-md-3 col-lg-3">
	    			<i class="fa fa-check fa-2x"></i>
	    		</div>
	    		<div class="col-9 col-md-9 col-lg-9">
	    			<h4>Sign sell order</h4>
	    			<label>Sign sell order using your wallet</label>
	    		</div>
	    		<div class="col-12 col-md-12">
	    			<button class="form-submit" type="button">Start</button>
	    		</div>
	    	</div>
	    </div>
	    
	  </div>

	</div>


	@section('extra_scripts')
		<script>
			$(document).ready(function(){
				 // Collection MODAL
	            $('.generate_collection').click(function(){
	                $('#collectionModal').addClass('d-block');
	                $(".collection-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
	            });

	            $('.close-custom-modal').click(function(){
	                 $(".collection-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
	                setTimeout(function() {
	                    $('#collectionModal').removeClass('d-block');
	                }, 400);
	            });
	            // ######################

	             // Steps MODAL
	            // $('.submitBtn').click(function(){
	            //     $('#create-stepsModal').addClass('d-block');
	            //     $(".create-steps-content").removeClass("fade-out-bottom").addClass("fade-in-bottom");
	            // });

	            $('.close-steps-modal').click(function(){
	                 $(".create-steps-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
	                setTimeout(function() {
	                    $('#create-stepsModal').removeClass('d-block');
	                }, 400);
	            });

	            // ######################


	            $(document).on("change", "#putSale", function(evt) {
	            	$('.instant-sale').toggleClass('d-none');
	            	$("#instantSale").prop("checked", false);
	            	$('.price-tag').addClass('d-none');
	            });

	            $(document).on("change", "#instantSale", function(evt) {
	            	if($(this).prop('checked') == false){
	            		$('.price-tag').addClass('d-none');
	            		$('.sale-price').removeClass('to-check');
					}else{
						$('.sale-price').addClass('to-check');
						$('.price-tag').removeClass('d-none');
					}
					$('.sale-price').removeClass('emptyVal');
	            });

	            $(document).on("change", "#accessToggle", function(evt) {
	            	$('.digital-link').toggleClass('d-none');
	            	$('#d-link').toggleClass('to-check');
	            	$('#d-link').removeClass('emptyVal');
	            });

	            $(document).on('input', '.provided-key',function(e){
	            	
				    if ($(this).val() != '' && $(this).hasClass('input-head')) {
				    	$(this).removeClass('input-head');
				    	var html = '';
				    	html += '<div class="row">\
									<div class="col-6 col-md-6">\
										<input class="inp bold-placeholder provided-key input-head" type="text" name="key[]" placeholder="Provided Key"/>\
									</div>\
									<div class="col-6 col-md-6">\
										<input class="inp bold-placeholder provided-value" type="text" name="value[]" placeholder="Provided Value"/>\
									</div>\
								</div>';
						$('#properties-field').append(html);
				    }
				    if ($(this).val() == '' && !$(this).hasClass('input-head')){
				    	$(this).parent().parent().remove();
				    }
				});
	            


			    $(document).on("change", "#selectedFile", function() {
			    	$('#fileInvalid').addClass('d-none');
			    	$('#sizeInvalid').addClass('d-none');
			    	var correctSize = checkFileSize();
			    	var ext = $("#selectedFile").val().split('.').pop().toLowerCase();
			    	var accepted = ['gif', 'png', 'jpg','mp4', 'webp', 'mp3'];
			    	var images = ['gif', 'png', 'jpg'];
			    	var videos = ['mp4', 'webp', 'mp3'];

			    	if (correctSize) {
				    	if ($.inArray(ext, accepted) !== -1) {
				    		$('.file-input').addClass('d-none');
					    	if ($.inArray(ext, images) !== -1) {
					    		$('.file-output').removeClass('d-none');
					    		$('.image-container').removeClass('d-none');
					    		readURL(this);
					    	}
					    	if ($.inArray(ext, videos) !== -1) {
					    		$('.video-container').removeClass('d-none');
					    		$('.file-output-video').removeClass('d-none');
								var $source = $('.category-video-tag');
								$source[0].src = URL.createObjectURL(this.files[0]);
								$source.parent()[0].load();
								var preview = $('.category-video-prev');
								preview[0].src = URL.createObjectURL(this.files[0]);
								preview.parent()[0].load();
					    	}
					    	$('.preview-desc').toggleClass('d-none');
				    	}
				    	else{
				    		$('#fileInvalid').removeClass('d-none');
				    	}
			    	}
			    });

			    $(document).on("change", "#collection-file", function() {
			    	var accepted = ['gif', 'png', 'jpg'];
			    	var ext = $("#collection-file").val().split('.').pop().toLowerCase();
			    	if ($.inArray(ext, accepted) !== -1) {
			    		readCollectionLogo(this);
			    	}
			    });

			    $(".close-btn").click(function(){
			    	$('.file-input').removeClass('d-none');
			    	$('.file-output').addClass('d-none');
			    	$('#selectedFile').val(null);
			    	$('.category-img-tag').attr('src', '');
			    	$('.image-container').addClass('d-none');
			    	$('.preview-desc').toggleClass('d-none');
			    });

			    $(".close-btn-video").click(function(){
			    	$('.file-input').removeClass('d-none');
			    	$('.file-output-video').addClass('d-none');
			    	$('#selectedFile').val(null);
			    	$('.category-video-tag').attr('src', '');
			    	$('.video-container').addClass('d-none');
			    	$('.preview-desc').toggleClass('d-none');
			    });

			    $('.fileBtn').click(function(){
			    	$(this).addClass('button-click');
			    	setTimeout(function() {
                      $('.fileBtn').removeClass('button-click');
                    }, 80);
			    });


			    $('.sale-price-btn').click(function(){
		           toggleCurrencyDrop();
		        });

				$('.currency-item').click(function(){
					$('.currency-check').addClass('opacity-0');
					$(this).siblings().removeClass('opacity-0');
					$('.sale-price-btn').attr('id', $(this).attr('id'));
					$('.sale-price-btn').html($(this).attr('id')+' <i class="fa fa-angle-down"></i>');
					$('.changeDD').text($(this).attr('id'));
					toggleCurrencyDrop();
				});



				$('#createCollectible').click(function(){
					var count_errors = 0;
					$('.to-check').each(function(){
						if($(this).val() == ''){
							count_errors += 1;
							$(this).addClass('emptyVal');
							$(this).parent().siblings('.this-error').text('This field is not allowed to be empty');
						}
					});
					if (count_errors > 0) {
						$('.error-msg').removeClass('d-none');
					}else{
						$('.to-check').removeClass('emptyVal');
						$('.this-error').text('');
						$('#create-collectible').submit();
					}
				});

				$('#collection-submit').click(function(){
					var count_errors = 0;
					$('.collection-check').each(function(){
						if($(this).val() == ''){
							count_errors += 1;
							$(this).addClass('emptyVal');
							$(this).siblings('.collection-error').text('This field is not allowed to be empty');
						}
					});

					if (count_errors == 0) {
						$('.collection-check').removeClass('emptyVal');
						$('.collection-error').text('');
						$('#create-collection').submit();
					}
				});


				$(document).on('propertychange input', '.to-check', function() {
			    	$(this).removeClass('emptyVal');
			    	$(this).parent().siblings('.this-error').text('');
			    });

			    $(document).on('propertychange input', '.collection-check', function() {
			    	$(this).removeClass('emptyVal');
			    	$(this).parent().siblings('.collection-check').text('');
			    	$(this).siblings('.custom-error').text('');
			    	$(this).siblings('.collection-error').text('');

			    });



			    $('#create-collection').on('submit', function(event){
			        event.preventDefault();
			         $.ajaxSetup({
			            headers: {
			                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			            }
			        });

			        var formdata = new FormData();

			        formdata.append('image', $('#collection-file').prop('files')[0]);
			        formdata.append('display_name', $(".collection-input[name='display_name']").val());
			        formdata.append('symbol', $(".collection-input[name='symbol']").val());
			        formdata.append('description', $(".collection-input[name='description']").val());
			        formdata.append('short_url', $(".collection-input[name='short_url']").val());

			        $.ajax({
			          url: "/collection",
			          type: "POST",
			          data: formdata,
			          processData: false,
  					  contentType: false,
			          success: function(response){
			            

			            $(".collection-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
		                setTimeout(function() {
		                    $('#collectionModal').removeClass('d-block');
		                }, 400);

			            $('.toast-message').text(response.message);
			            $('#create-collection').trigger("reset");
			            setTimeout(function() {
		                    launch_toast();
		                }, 500);

		                
			            var collection = response.collection;
			            var id = collection['id'];
			            var image = collection['image'];
			            var display_name = collection['display_name'];
			            var symbol = collection['symbol'];

		                var source = "{!! asset('storage/collections/"+image+"') !!}";

		                var html = '';
				    	html += '<a id="'+id+'" href="javascript:void(0)" class="g_select active-btn">\
										<div class="outside">\
										  <div class="inside">\
										  	<div class="inner-outside">\
										  		<div class="inner">\
										  			<img src="'+source+'">\
										  			<h6>'+display_name+' '+symbol+'</h6>\
										  		</div>\
										  	</div>\
										  </div>\
										</div>\
									</a>';

						setTimeout(function() {
							$('.g_select').removeClass('active-btn').addClass('inactive-btn');
		                    $('#collection-group').append(html);
		                }, 600);
			            

			          },
			          error: function (xhr) {
						if (xhr.status == 422) {
							var errors = JSON.parse(xhr.responseText);
							$.each(errors.errors, function(key,value){
								var html = '';
								$.each(value, function(key2, value2){
									html += value2 + '</br>';
								});
								$('#'+key+'-collection').html(html);
							});
						}
			          },
			        });
			    });



				$('#create-collectible').on('submit', function(event){
			        event.preventDefault();
			         $.ajaxSetup({
			            headers: {
			                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			            }
			        });

			        var formdata = new FormData();

			        var aop = $("[name='aop']").is(':checked') == true ? 1 : 0;
			        var pos = $("[name='pos']").is(':checked') == true ? 1 : 0;
			        var isp = $("[name='isp']").is(':checked') == true ? 1 : 0;

			        formdata.append('nft', $('#selectedFile').prop('files')[0]);
			        formdata.append('aop', aop);
			        formdata.append('aop_link', $("[name='aop_link']").val());
			        formdata.append('collection_id', $('.active-btn').attr('id'));
			        formdata.append('name', $("[name='collectible_name']").val());
			        formdata.append('description', $("[name='description']").val());
			        formdata.append('royalties', $("[name='royalties']").val());

			        var copies = $("[name='copies']").val() != undefined ? $("[name='copies']").val() : 1;
			        formdata.append('copies',copies);
			        formdata.append('pos', pos);
			        formdata.append('isp', isp);
			        formdata.append('price', $("[name='price']").val());
			        formdata.append('currency', $(".sale-price-btn").attr('id'));
			        formdata.append('category_id', $("#category-drop").val());
			        formdata.append('legend_id', $("#legend-drop").val());


			        var keys = $('.provided-key');
			        var values = $('.provided-value');

				    var arr = [];
				    var this_row={};
				    for(var i = 0; i < keys.length; i++){
				    	var key = $(keys[i]).val();
				    	var value = $(values[i]).val();
				    	this_row[key] = value;
				    }
				    arr.push(this_row);
				    arr = JSON.stringify(arr);
				    formdata.append('properties', arr);

				    formdata.append('type', $('#create-collectible').data('collectible-type'));


			        $.ajax({
			          url: "/create/collectible",
			          type: "POST",
			          data: formdata,
			          processData: false,
  					  contentType: false,
			          success: function(response){
			            $('html, body').animate({
			                scrollTop: $("#main-app").offset().top
			            }, 200);
			            
			            $('.toast-message').text(response.message);
			            launch_toast();

			            $('.file-input').removeClass('d-none');
				    	$('.file-output').addClass('d-none');
				    	$('.file-output-video').addClass('d-none');
				    	$('#selectedFile').val(null);
				    	$('.category-img-tag').attr('src', '');
				    	$('.category-video-tag').attr('src', '');
				    	$('.video-container').addClass('d-none');
				    	$('.preview-desc').toggleClass('d-none');
				    	$('.error-msg').addClass('d-none');
				    	$('#create-collectible').trigger("reset");

				    	var html = '';
				    	html += '<div class="row">\
									<div class="col-6 col-md-6">\
										<input class="inp bold-placeholder provided-key input-head" type="text" name="key[]" placeholder="Provided Key"/>\
									</div>\
									<div class="col-6 col-md-6">\
										<input class="inp bold-placeholder provided-value" type="text" name="value[]" placeholder="Provided Value"/>\
									</div>\
								</div>';
						$('#properties-field').html(html);

			          },
			          error: function (xhr) {
						if (xhr.status == 422) {
							var errors = JSON.parse(xhr.responseText);
							$.each(errors.errors, function(key,value){
								var html = '';
								$.each(value, function(key2, value2){
									html += value2 + '</br>';
								});
								$('#'+key+'-validation').html(html);
							});
						}
			          },
			        });
			    });



				

			    $(document).on('click', '.g_select', function(){
			    	$('.g_select').addClass('inactive-btn');
			    	$('.g_select').removeClass('active-btn');
			    	$(this).addClass('active-btn');
			    });

			});

			function readURL(input) {
				if (input.files && input.files[0]) {

					var reader = new FileReader();
					reader.onload = function(e) {
						$('.category-img-tag').attr('src', e.target.result);
					}
				}
				reader.readAsDataURL(input.files[0]);
			}

			function readCollectionLogo(input) {
				if (input.files && input.files[0]) {

					var reader = new FileReader();
					reader.onload = function(e) {
						$('.collection-logo').attr('src', e.target.result);
					}
				}
				reader.readAsDataURL(input.files[0]);
			}


			function checkFileSize(){
				const fi = document.getElementById('selectedFile');
		        // Check if any file is selected. 
		        if (fi.files.length > 0) { 
		            for (var i = 0; i <= fi.files.length - 1; i++) { 
		  
		                const fsize = fi.files.item(i).size; 
		                const file = Math.round((fsize / 1024)); 
		                if (file >= 51200) {
		                	$('#sizeInvalid').removeClass('d-none');
		                    return false;
		                }
		                return true;
		            } 
		        } 
			}

			function toggleCurrencyDrop(){
				var container = $(".sale-price-drop"); 
		        if (!container.hasClass('fade-in-top')) {
		            container.toggleClass('d-none');
		            container.addClass("fade-in-top").removeClass("fade-out-top");
		        }
		        else 
		        {
		            container.addClass("fade-out-top").removeClass("fade-in-top");
		            setTimeout(function() {
		              container.toggleClass('d-none');
		            }, 400);
		        }
			}

			function launch_toast() {
			    var x = document.getElementById("toast")
			    x.className = "show";
			    setTimeout(function(){ 
			    	x.className = x.className.replace("show", "");
			    }, 5000);
			}
		</script>
	@endsection


@endsection