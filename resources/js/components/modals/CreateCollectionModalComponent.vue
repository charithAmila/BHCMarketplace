<template>
	<div id="collectionModal" class="custom-modal">
	  <div class="modal-content collection-content">
	    <div class="modal-head">
	      <h3>Collection</h3>
	      <span class="close-custom-modal">&times;</span>
	    </div>
	    <div class="modal-body">
	    	<form id="create-collection" method="POST" :action="store_route">
				<input type="hidden" name="_token" :value="csrf_token" />
				<div class="row">
					<div class="collection-container col-5 col-md-5">
						<img id="collection-logo-form" class="collection-logo" :src="asset_url+'images/avatar2.png'" alt="collection-logo">
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
					<button id="collection-submit" class="form-submit" type="button" @click="generateCollection">Create collection</button>
				</div>
			</form>
	    </div>
	    
	  </div>

	</div>
</template>


<script>

import $ from 'jquery'
	
export default{
	props: [
		'store_route',
		'asset_url',
		'csrf_token'
	],
	methods: {
		generateCollection(){
			var formdata = new FormData();

	        formdata.append('image', $('#collection-file').prop('files')[0]);
	        formdata.append('display_name', $(".collection-input[name='display_name']").val());
	        formdata.append('symbol', $(".collection-input[name='symbol']").val());
	        formdata.append('description', $(".collection-input[name='description']").val());
	        formdata.append('short_url', $(".collection-input[name='short_url']").val());

	        axios.post('/collection', formdata).then((res) => {
	        	$(".collection-content").removeClass("fade-in-bottom").addClass("fade-out-bottom");
	            setTimeout(function() {
	                $('#collectionModal').removeClass('d-block');
	            }, 400);

	            $('.toast-message').text(res.data.message);
	            $('#create-collection').trigger("reset");
	            $('#collection-logo-form').attr('src', this.asset_url+'images/avatar2.png');
	            setTimeout(function() {
	                launch_toast();
	            }, 500);
	            this.$parent.updateCollection(res.data.collections)

	        }).catch((error) => {
	        	console.log(error)
	        })
		}
	}
}
</script>