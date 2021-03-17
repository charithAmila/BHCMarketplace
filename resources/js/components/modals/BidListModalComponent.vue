 <template>
 	<div id="bidListModal" class="custom-modal d-none">
	  <div class="modal-content bidList-content">
	    <div class="modal-head">
	      <h3>Bids for </h3>
	      <span class="close-bid-list-modal">&times;</span>
	    </div>
	    <div class="modal-body">
	    	<label class="item-description"><span class="item-name">{{ collectible.name }}</span></label>

	    	<div class="bid-list-container">
		    	<div v-for="(item, index) in bidList" :key="index" class="row follower-list">
					<div class="col-3 col-sm-3 col-md-3 col-lg-3">
						<a :href="item.user.asset_url+'profile/'+item.user.link_profile">
							<img :src="item.user.asset_url+item.user.display_photo">
						</a>
						
					</div>
					<div class="col-6 col-sm-6 col-md-6 col-lg-6">
						<label>
							<a class="user_name" :href="item.user.asset_url+'profile/'+item.user.link_profile">
								{{ item.user.name }}
							</a> offered <span class="bid-offer">{{ item.bid.price }} {{ item.bid.currency }}</span>
						</label>
					</div>
					<div class="col-3 col-sm-3 col-md-3 col-lg-3">
						<a class="accept-bid-nft" v-if="item.bid.sold == 0" href="javascript:void(0)" @click="acceptBid(item.bid)">Accept</a>
						<a class="sold-nft" v-if="item.bid.sold == 1">Sold</a>
					</div>
				</div>
			</div>

		</div>
	    
	  </div>

	</div>
 </template>

 <script>
 	
 export default{
 	props: ['collectible','bidList'],
 	data() {
 		return{

 		}
 	},
 	methods: {
 		acceptBid(bid){
 			axios.post('/accept/bid', {
			    transaction_id: bid.id,
			}).then((res) => {
				this.$parent.updateBidList(this.collectible)
				this.$parent.$parent.getCollectible()
			})
			.catch((error) => {
                console.log(error)
            })
 			console.log(bid.id)
 		}
 	}
 }	
 </script>