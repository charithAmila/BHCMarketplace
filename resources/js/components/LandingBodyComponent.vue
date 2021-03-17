<template>
	<div>
		<h1 class="content-title pl-70 landing-title">Create and sell digital arts & collectibles secured with Binance Smart Chain</h1>
		<div class="btnGroup">
			<a :href="create_collectible_route" class="btn btnPrimary">Create</a>
			<a :href="marketplace_route" class="btn btnSecondary">Explore</a>
		</div>
	</div>
</template>

<script>
	
export default{
	props: [
		'create_collectible_route',
		'marketplace_route',
	],
}
</script>