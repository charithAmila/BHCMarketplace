<template>
	<div class="row main-row">
		<div class="col-md-2 col-lg-2 top-sellers">
			<top-user-component
				ref="topUserRef"
				:asset_url="asset_url"
			></top-user-component>
		</div>
		<div class="col-md-10  col-lg-10  item-content">
			<marketplace-component
				:collectible_asset="collectible_asset"
				:show_collectible="show_collectible"
				:current_user="current_user"
				:base_url="base_url"
				:asset_url="asset_url"
			></marketplace-component>
		</div>
	</div>
</template>


<script>
	
export default{
	props:[
		'collectible_asset',
		'show_collectible',
		'current_user',
		'base_url',
		'asset_url',
	],
	data(){
		return{
			customData: '',
		}
	},
	methods: {
		updateTopUser(){
			this.$refs.topUserRef.fetchFilteredUser();
		}
	}
}
</script>