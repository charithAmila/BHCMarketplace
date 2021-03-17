<template>
	<div>
		<div class="posBtn z-2">
			<a class="btn content-title" :href="wallet_route" id="connectWallet"><i class="fas fa-angle-double-right"></i> {{account}}</a>
		</div>
		<a :href="marketplace_route">
			<img class="sidebarLogo z-2" :src="asset_url+'images/logo.png'">
		</a>
		<div class="my-auto ">
			<div class="imgSmall imgPos1">
				<img class="sidebarImg" :src="asset_url+'images/2_icon1.png'">
			</div>
			<div class="imgLarge imgPos2">
				<img class="sidebarImg" :src="asset_url+'images/2_icon2.png'">
			</div>
				<div class="imgNormal imgPos3">
			<img class="sidebarImg" :src="asset_url+'images/2_icon3.png'">
			</div>
			<div class="imgNormal imgPos4">
				<img class="sidebarImg" :src="asset_url+'images/2_icon4.png'">
			</div>
		</div>
	</div>
</template>

<script>
	
export default{
	data(){
		return{
			selectedAddress:'',
			account:"Connect Wallet"
		}
	},
	props: [
		'wallet_route',
		'marketplace_route',
		'asset_url',
	],
	mounted(){
		this.checkConnection();
	},
	methods:{
		checkConnection:function() {
			const _this =this;
			var connectionInterval = setInterval(function(){
				var acc = window.ethereum.selectedAddress;
				if(acc){
					_this.selectedAddress = acc;
					_this.account = acc.substring(0,10)+'...';
					clearInterval(connectionInterval);
					//console.log(_this.selectedAccount);
				}
			},300)
		},
	}
}
</script>