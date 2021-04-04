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
		        <button class="form-send" v-on:click="subscribe()">Let's go!</button>
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

},
	methods: {

	async subscribe(){
		console.log("Subscribe");
	},}
	

}
</script>