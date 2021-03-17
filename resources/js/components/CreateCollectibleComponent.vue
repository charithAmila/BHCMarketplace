<template>
	<div>
		<div class="back-btn">
			<a class="btn" :href="create_route" id="box">Go back <i class="fas fa-angle-double-left"></i></a>
		</div>
		<div class="solo-create">
			<div class="row">
				<div class="col-12 col-md-8">
					<h4 class="content-title text-head">Create {{ capitalizeFirstLetter(type) }} Collectible</h4>
					<form id="create-collectible" :data-collectible-type="type" method="POST" :action="store_route">
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


						
						<generate-collection-component :collections="setCollections" :asset_url="asset_url"></generate-collection-component>


						

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
						 				<category-component :categories="categories"></category-component>
						 			</div>
								    <p class="this-error text-danger"></p>
									<span id="legend-validation" class="custom-error text-danger"></span>
						 		</div>
						 		<div class="category col-6 col-md-6 col-lg-6 col-xl-6">
						 			<h6 class="content-title text-content text-content mb-0">Legend</h6>
						 			<div>
									    <legend-component :legends="legends"></legend-component>
						 			</div>
								    <p class="this-error text-danger"></p>
									<span id="category-validation" class="custom-error text-danger"></span>
						 		</div>
						 	</div>
						   
						  </div>

						<div class="form-group">
							<div class="row">
								<div class="col-12" :class="type == 'multiple' ? 'col-md-6' : 'col-md-12'">
									<h6 class="content-title text-content text-content mb-0">Royalties</h6>
									<div>
										<input class="inp to-check" type="number" name="royalties" placeholder="E.g '20%'" value="20" />
									</div>
									<p class="this-error text-danger"></p>
									<span id="royalties-validation" class="custom-error text-danger"></span>
									<small class="faded-text">Recommended 5%, 10%, 15%, 20%, 30%, 35%, 40%, 50%</small>
								</div>
								<div v-if="type == 'multiple'" class="col-12 col-md-6">
									<h6 class="content-title text-content text-content mb-0">Number of copies</h6>
									<div>
										<input class="inp to-check" type="number" name="copies" placeholder="E.g '10'" />
									</div>
									<p class="this-error text-danger"></p>
									<span id="copies-validation" class="custom-error text-danger"></span>
									<small class="faded-text">Amount of tokens</small>
								</div>
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


		<create-collection-modal-component
			:store_route="store_route"
			:asset_url="asset_url"
			:csrf_token="csrf_token"
		></create-collection-modal-component>
	</div>
</template>


<script>
	
export default{
	props: [
		'collections',
		'categories',
		'legends',
		'type',
		'asset_url',
		'create_route',
		'store_route',
		'csrf_token'
	],
	data () {
		return {
			setCollections: [],
		}		
	},
	methods: {
		capitalizeFirstLetter(string){
	    	return string.charAt(0).toUpperCase() + string.slice(1);
	    },
	    updateCollection(passedCollection){
	    	this.setCollections = passedCollection
	    },
	},
	mounted(){
		this.setCollections = this.collections
	}
}
</script>