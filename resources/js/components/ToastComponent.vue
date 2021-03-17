<template>
	<div>
		<div id="toast">
			<img :src="asset_url+'images/logo.png'">
			<div id="desc" class="toast-message">A notification message..</div>
		</div>
	</div>
</template>


<script>

export default{
	props: ['asset_url']
}
</script>