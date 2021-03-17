<template>
	<div>
		<section class="heading">
			<img class="head-img" :src="asset_url+'images/logo.png'">
			<label class="head-text">billionhappiness.com FAQ</label>
		</section>
	</div>
</template>

<script>
export default{
	props: ['asset_url']
}
</script>