 <template>
 	<div id="bidModal" class="custom-modal d-none">
	  <div class="modal-content bid-content">
	    <div class="modal-head">
	      <h3>Place a bid</h3>
	      <span class="close-bid-modal">&times;</span>
	    </div>
	    <div class="modal-body">
	    	<label class="item-description">You are about to purchase <span class="item-name">{{ singleNft.name }}</span> from billion. Check information then proceed to payment</label>

			<div class="form-section">
				<form id="bidForm" autocomplete="off" @submit.prevent="placeBid()">
					<div class="form-divide">
						<label class="input-label">Your bid</label>
						<input v-model.number="bid_input" class="modal-input dropdown-bid" type="number" id="bid-input" name="bid" placeholder="Enter bid" step=".01">
						<span class="link-url-end currency-btn"><span id="selectedCurrency">HPS</span> <i class="fa fa-angle-down"></i></span>

						<div class="currency-drop d-none">
							<div class="drop-group">
								<a href="javascript:void(0)" id="HPS" class="currency-item" @click ="setHPS()">HPS</a>
								<i class="fa fa-check currency-check"></i>
							</div>
							<div class="drop-group">
								<a href="javascript:void(0)" id="BNB" class="currency-item" @click="setBNB()">BNB</a>
								<i class="fa fa-check currency-check opacity-0"></i>
							</div>
						</div>
						
						
					</div>
					<div class="purchase">
						<div class="purchase-info">
							<label class="text-details">Your balance</label>
							<label class="text-value">{{ balance }} <span class="changeDD">HPS</span></label>
						</div>
						<div class="purchase-info">
							<label class="text-details">Service fee</label>
							<label class="text-value">{{ service_fee }} <span class="changeDD">HPS</span></label>
						</div>
						<div class="purchase-info">
							<label class="text-details">You will pay</label>
							<label class="text-value">{{ total_payment }} <span class="changeDD">HPS</span></label>
						</div>
					</div>
					<button class="form-submit" type="submit">Place a bid</button>
					<button class="cancel-btn" type="button">Cancel</button>
				</form>
			</div>
		</div>
	    
	  </div>

	</div>
 </template>


<script>


import { bid , startBidding, getHighestBid, getBiddingStatus,getAllBids,endBidding, getHpsBalance,getBNBBalance } from ".././../bidFunc";

import $ from 'jquery'

export default {

	props: [ 'singleNft', 'page'],
	data () {
		return{
			bid_input: 0,
			HPS_balance: 0,
			BNB_Balance:0,
			service_fee: 0,
			total_payment: 0,
			payment: 0,
			currency: '',
			nft_id: 0,
			record_id: 0,
			selected_token:0
			
		}
	},
	async mounted() {
		 this.HPS_Balance = await getHpsBalance()
		this.BNB_Balance = await getBNBBalance()
	 
	
	},

	computed:{
			balance(){
		
			if(this.selected_token ==1){
				return this.BNB_Balance;
			}
			else{ 
				return this.HPS_Balance;
			}
		},

	},

	watch: {
		

		singleNft: function() {
			this.nft_id = this.singleNft.id
			this.record_id = this.singleNft.record_id
		},
		bid_input: function() {
			this.payment = this.bid_input
			this.service_fee = +(this.payment * 0.025)
		    this.total_payment = +(this.payment + +this.service_fee)
		}
	},
	methods: {
		setBNB(){
			this.selected_token = 1;
		},
		setHPS(){
			this.selected_token = 0;
		},
		async placeBid() {
			this.currency = $('#selectedCurrency').text()

			let res = await bid(this.singleNft.owner_id,this.singleNft.contract,this.singleNft.id,this.currency,this.payment);
			console.log(res);

			/*axios.post('/create/transaction', {
			    type: 'bid',
			    nft_id: this.nft_id,
			    price: this.payment,
			    currency: this.currency,
			    record_id: this.record_id,
			}).then((res) => {
				$('.toast-message').text(res.data.message);
				$('#bidForm').trigger("reset");
	            setTimeout(function() {
                    launch_toast();
                }, 500);
				modalClose($('#bidModal'), $(".bid-content"));
				this.service_fee = 0
				this.total_payment = 0
				this.payment = 0
				this.bid_input = 0

				if (this.page == 'showcollectible') {
					this.$parent.updateData()
				}
			})
			.catch((error) => {
                alert("error")
            })*/

		}
	}
}
</script>