<template>
	<div>
		<div class="row row-0 show-collectible">
			<div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 custom-lg-4 withBgGray second-mobile">
				<div class="title-container">
					<div class="row margin-0">
						<div class="col-8 col-md-8 col-lg-8 col-xl-8 collectibleTitle col-title">
							<h3 class="inlineDiv inline-title">{{ collectible.name }}</h3>
						</div>
						<div class="col-4 col-md-4 col-lg-4 col-xl-4 collectibleTitle col-option">
							<h3 class="inlineDiv inline-btn">
								<a v-if="current_user != current_owner.user_id" id="options-btn" class="show-drop" href="javascript:void(0)"> <i class="fas fa-ellipsis-h titleIcon"></i></a>
								<a id="share-btn" class="show-drop" href="javascript:void(0)"> <i class="fa fa-share-square-o titleIcon"></i></a>
							</h3>

							<div class="show-opt-menu d-none">
								<a :v-if="collectible.isp == 1" class="buy-now" href="javascript:void(0)" @click="fetchSingleNft('checkout')">Buy now</a>
								<a class="place-bid" href="javascript:void(0)"@click="fetchSingleNft('bid')">Place a bid</a>
								<a class="report" href="javascript:void(0)"@click="fetchSingleNft('report')">Report</a>
							</div>

							<div class="show-share-drop d-none">
								<label class="share-title">Share link to this page</label>
								<div class="row">
									<div class="col-4 col-md-4">
										<a href="javascript:void(0)">
											<i class="fa fa-twitter s-btn"></i>
											<label>Twitter</label>
										</a>
									</div>
									<div class="col-4 col-md-4">
										<a href="javascript:void(0)">
											<i class="fa fa-facebook s-btn"></i>
											<label class="fb-label">Facebook</label>
										</a>
									</div>
									<div class="col-4 col-md-4">
										<a href="javascript:void(0)">
											<i class="fab fa-telegram-plane s-btn"></i>
											<label>Telegram</label>
										</a>
									</div>
									<div class="col-4 col-md-4">
										<a href="javascript:void(0)">
											<i class="fa fa-envelope s-btn"></i>
											<label>Email</label>
										</a>
									</div>
									<div class="col-4 col-md-4">
										<a href="javascript:void(0)">
											<i class="fa fa-copy s-btn"></i>
											<label class="c-link">Copy link</label>
										</a>
									</div>

								</div>
							</div>

						</div>
					</div>
				</div>
				<div class="priceTag">
					<label class="showCurrency">{{ collectible.price }}</label>
					<!-- <label class="showCurrencyPlain">$135.20</label> -->
				</div>
				<h5 class="itemType">
					<div class="legend">
						<div :class="collectible.legend">
							<i :class="collectible.icon"></i> {{ capitalizeFirstLetter(collectible.legend) }}
						</div>
					</div>
				</h5>
				<p class="itemDesc">{{ collectible.description }}</p>

				<collectible-details-component
					:creator="creator"
					:current_owner="current_owner"
					:owners="owners"
					:transactions="transactions"
					:user_profile="user_profile"
					:asset_url="asset_url"
					:collection="collectible.collection"
					:collection_image="collectible.collection_image"
				></collectible-details-component>


				<div class="row m-20 text-center end-content">
					<div class="col-4 col-md-4">
						<label class="position">Available</label>
						<label class="positionHolder">{{ collectible.available }}</label>
					</div>
					<div v-if="current_user != current_owner.user_id" class="col-4 col-md-4">
						<label class="position">Quantity</label>
						<span class="quantity-btn positionHolder">1 <i class="fa fa-angle-down"></i></span>

						<div class="quantity-drop d-none">
							<div v-for="index in collectible.quantity" :key="index" class="drop-group">
								<a href="javascript:void(0)" :id="index" class="quantity-item">{{ index }}</a>
							</div>
						</div>
					</div>
					<div v-if="current_user != current_owner.user_id" class="col-4 col-md-4">
						<label class="position">Pay with</label>
						<span class="checkout-currency positionHolder">BHC <i class="fa fa-angle-down"></i></span>

						<div class="checkout-drop d-none">
							<div class="drop-group">
								<a href="javascript:void(0)" id="BHC" class="side-drop currency-item">BHC</a>
								<i class="fa fa-check currency-check"></i>
							</div>
							<div class="drop-group">
								<a href="javascript:void(0)" id="BNB" class="side-drop currency-item">BNB</a>
								<i class="fa fa-check currency-check opacity-0"></i>
							</div>
						</div>
					</div>
				</div>

				<div v-if="collectible.isp == 1 && current_user != current_owner.user_id" class="buy-container">
					<button id="buyBtn" class="buyBtn d-none d-md-block" @click="fetchSingleNft('checkout')" >Buy 1 for {{ collectible.price }}</button>

					<p class="text-gray text-center d-none d-md-block">Service fee 1.5% {{ collectible.price }} = <span class="text-dark-gray">$137.228</span></p>

					<div class="show-footer-btn d-block d-md-none">
						<button class="buyBtn">Buy 1 for 110.15 BHC</button>

						<p class="text-gray text-center">Service fee 1.5% 110.15 BHC = <span class="text-dark-gray">$137.228</span></p>
					</div>
				</div>

				<div v-if="collectible.isp == 0 && current_user != current_owner.user_id" class="bid-container">
					<button id="bidBtn" class="buyBtn d-none d-md-block">Place a bid</button>
				</div>


			</div>

			<div class="col-sm-12 col-md-8 col-lg-8 col-xl-8 custom-lg-8 showContent my-auto first-mobile">
				<div class="inner-img">

					<div class="mobile-imgHead">
						<a  class="mobile-show-link" href="javascript:void(0)">
							<i class="fa fa-heart"></i>
						</a>

						<a class="mobile-show-link" href="javascript:void(0)">
							<i class="fa fa-arrows-alt"></i>
						</a>
					</div>
					<div class="collectible-container">

						<img v-if="collectible.type == 'image'" class="showImg" :src="asset_url+'storage/collectibles/'+collectible.nft">
						<video v-if="collectible.type == 'video'" class="showImg" autoplay loop controls muted>
						      <source :src="asset_url+'storage/collectibles/'+collectible.nft" type="video/mp4">
						</video>

						<div class="show-nft-option imgHead d-none d-md-block">
							<a v-if="auth_check && current_user != current_owner.user_id" id="like-btn" :data-nft-slug="collectible.nft_slug" :class="onWishList==true ? 'nft-liked' : ''" class="imgHead-link" href="javascript:void(0)">
								<i class="fa fa-heart nft-option"></i>
							</a>

							<a id="nft-expand" class="imgHead-link" href="javascript:void(0)">
								<i class="fa fa-arrows-alt expand nft-option"></i>
							</a>
						</div>
					</div>
				</div>
				
				<img class="showImgBg d-none d-md-block" :src="asset_url+'images/right.png'">
			</div>


		</div>

		<checkout-modal-component :singleNft="singleNft" :page="'showcollectible'"></checkout-modal-component>
		<bid-modal-component :singleNft="singleNft" :page="'showcollectible'"></bid-modal-component>

	</div>
</template>

<script>

import CollectibleDetails from './show_collectible/CollectibleDetailsComponent.vue';
import BidModal from './modals/BidModalComponent.vue';
import CheckoutModal from './modals/CheckoutModalComponent.vue';

export default {
	components: {
		CollectibleDetails,
		BidModal,
		CheckoutModal,
	},
	props: ['collectible','transactions','onWishList','asset_url','auth_check','user_profile', 'current_user','base_url'],
	data () {
		return {
			creator: [],
			current_owner: [],
			owners: [],
			singleNft: {},
		}
	},
	watch: {
		singleNft: function() {
			this.price = +parseFloat(this.singleNft.total).toFixed(2)
			this.nft_id = this.singleNft.id
			this.record_id = this.singleNft.record_id
		}
	},
	methods: {
	    capitalizeFirstLetter(string){
	    	return string.charAt(0).toUpperCase() + string.slice(1);
	    },
	    fetchSingleNft(clicked){
	    	if (this.current_user == 0) {
	    		window.location.href = this.base_url+'/connect';
	    	}else{
	    		modalOpen($('#'+clicked+'Modal'), $('.'+clicked+'-content'));
	    	}
	    	this.singleNft = this.collectible
	    	this.singleNft.total = this.fetchTotal(this.collectible.price)
	    	this.singleNft.currency = this.fetchCurrency(this.collectible.price)
	    	this.singleNft.max = this.fetchTotalCopies(this.collectible.available)
	    },
	    fetchTotal(price){
	    	return parseFloat(price.split(" ")[0]).toFixed(2)
	    },
	    fetchCurrency(price){
	    	return price.split(" ")[1]
	    },
	    fetchTotalCopies(copies){
	    	return copies.split(" ")[0]
	    },
	    updateData(){
	    	axios.get('/update/show-page/'+this.collectible.link_user_slug+'/'+this.collectible.link_nft_slug).then((res) =>{
	    		this.collectible = res.data.collectible
				this.owners = res.data.collectible.owners
				this.transactions = res.data.transactions
            }).catch((error) =>{
                console.log(error)
            })
	    }
	},
	mounted(){
		this.creator = this.collectible.creator
		this.current_owner = this.collectible.current_owner
		this.owners = this.collectible.owners
	}
}

</script>