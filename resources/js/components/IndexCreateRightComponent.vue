<template>
	<div class="right-img">
		<img :src="asset_url+'images/right.png'">
	</div>
</template>

<script>
	
export default{
	props: [
		'asset_url'
	]
}
</script>