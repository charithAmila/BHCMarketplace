<template>
	<div>
		<section class="custom-footer d-none d-md-block">
		  <div class="row">

		    <div class="col-md-4">
		      <div class="social-links">
		          <a href="https://twitter.com/bhc_happiness" target="_blank">
		            <img :src="base_url+'/images/twitter.png'"> Follow us on Twitter
		          </a>
		          <a href="https://t.me/BillionHappinessOfficial" target="_blank">
		            <img :src="base_url+'/images/telegram.png'"> Join our Telegram community
		          </a>
		          <a href="javascript:void(0)">
		            <img :src="base_url+'/images/message.png'"> Community Feedback
		          </a>
		      </div>
		    </div>

		    <div class="col-md-8">
		      <div class="newsletter">
		        <h3>Subscribe with Happiness newsletter</h3>
		        <label class="small-text">Don't miss out our latest updates and new created artworks</label>
		        <input class="newsletter-input" type="email" name="" placeholder="sample@protonmail.com">
		        <button class="form-send" v-on:click="AllBids()">Let's go!</button>
		      </div>
		    </div>

		  </div>
		</section>

		<section class="footer-mobile d-sm-block d-md-none">

		
		  <div class="newsletter">
		    <h3>Subscribe with Happiness newsletter</h3>
		    <label class="small-text">Don't miss out our latest updates and new created artworks</label>
		    <input class="newsletter-input" type="email" name="" placeholder="sample@protonmail.com">
		    <button class="form-send">Let's go!</button>
		  </div>
		  <div class="footer-social">
		    <a href="https://twitter.com/bhc_happiness" target="_blank">
				<img :src="base_url+'/images/twitter.png'"> Follow us on Twitter
			</a>
			<a href="https://t.me/BillionHappinessOfficial" target="_blank">
				<img :src="base_url+'/images/telegram.png'"> Join our Telegram community
			</a>
			<a href="javascript:void(0)">
				<img :src="base_url+'/images/message.png'"> Community Feedback
			</a>
		  </div>
		  <div class="copyright-section">
		    <p>&#169; 2020 Billion, Inc.</p>
		  </div>
		</section>

		{{highestBid}}
	</div>
	
</template>

<script>

import { bid , startBidding, getHighestBid, getBiddingStatus,getAllBids,endBidding } from "././../bidFunc";
export default{
	props: [
		'base_url',
	],

data(){
return{ 
	highestBid:{},
	highestBidder:{},
	biddingStatus:{}
}
 
},

async mounted() {
  //this.highestBid = await getHighestBid(1,1,1,1);
 //this.highestBidder = await getHighestBidder('18');
 //this.biddingStatus = await getBiddingStatus('18');

},
	methods: {

	async startBid(){
		let res = await startBidding('18');
		console.log(res);
	},
	
	async placeBid(){

	let res = await bid(1,1,1,1,'18','HPS','2');
	console.log(res);
	
	}
,
	async getMaxBid(){
     let res = await getHighestBid(1,1,1,18);
	},

	async AllBids(){
let res = await getAllBids('0x0c7117eCEFc947df6507D0fC47E8C80EbA62E7DC','0x41fFF2b6F20a6b7E9F27764f092264B30053D4d4',21)
console.log(res);
	},

	async stopBid(){
let res = await endBidding(1,1,1,1,18)
	}

	}

}
</script>