<template>
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

		<a v-for="(collection, index) in collections"
			:key="index"
			:id="collection.id" 
			href="javascript:void(0)" 
			class="g_select"
			:class="index == 0 ? 'active-btn' : 'inactive-btn'"
		>
			<div class="outside">
			  <div class="inside">
			  	<div class="inner-outside">
			  		<div class="inner">
			  			<img v-if="collection.default == 1" 
			  				class="collection-logo" 
			  				:src="asset_url+'collections/'+collection.image"
			  			>
			  			<img v-else 
			  				class="collection-logo" 
							:src="asset_url+'storage/collections/'+collection.image"
						>
			  			<h6>{{ collection.display_name }} {{ collection.symbol }}</h6>
			  		</div>
			  	</div>
			  </div>
			</div>
		</a>

	</div>
</template>


<script>
	
export default{
	props: ['collections', 'asset_url'],
}
</script>