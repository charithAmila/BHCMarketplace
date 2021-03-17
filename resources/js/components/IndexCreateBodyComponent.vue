<template>
	<div class="main-create">
		<h2>Create Collectible</h2>
		<p class="desc">Choose "Solo" if you want your collectible to be one of a kind or "Batch" if you want to sell one collectible multiple times</p>
		<div class="row">
			<div class="col-md-6">
				<a :href="solo" class="g_select">
					<div id="box">
						<div class="col-content">
							<img :src="asset_url+'images/solo.png'">
							<h4>Solo</h4>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-6">
				<a :href="multiple" class="g_select">
					<div id="box">
						<div class="col-content">
							<img :src="asset_url+'images/batch.png'">
							<h4>Batch</h4>
						</div>
					</div>
				</a>
			</div>
		</div>
		<p class="desc-bottom">We do not own your private keys and cannot access your funds without your confirmation.</p>
	</div>
</template>

<script>
	
export default{
	props: [
		'solo',
		'multiple',
		'asset_url'
	]
}
</script>