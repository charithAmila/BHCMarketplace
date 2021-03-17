<template>
	<div>
		<div class="social-collectible">
			<a href="https://twitter.com/bhc_happiness" target="_blank">
				<img :src="asset_url+'images/twitter.png'"> Follow us on Twitter
			</a>
			<a href="https://t.me/BillionHappinessOfficial" target="_blank">
				<img :src="asset_url+'images/telegram.png'"> Join our Telegram community
			</a>
			<a href="javascript:void(0)">
				<img :src="asset_url+'images/message.png'"> Community Feedback
			</a>
		</div>
	</div>
</template>

<script>
	
export default{
	props: ['asset_url']
}
</script>