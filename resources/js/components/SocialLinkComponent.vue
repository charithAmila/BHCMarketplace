<template>
	<div>
		<div class="social-collectible">
			<a href="https://twitter.com/bhc_happiness" target="_blank">
				<img :src="asset_url+'images/twitter.png'"> Follow us on Twitter
			</a>
			<a href="https://t.me/BH_NFT_Support" target="_blank">
				<img :src="asset_url+'images/telegram.png'"> Join our Telegram community
			</a>
			<a href="https://rbjrcxhzb4w.typeform.com/to/Ak0nEWnn" target="_blank">
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