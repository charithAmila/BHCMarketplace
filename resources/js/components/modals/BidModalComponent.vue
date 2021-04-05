<template>
  <div id="bidModal" class="custom-modal d-none">
    <div class="modal-content bid-content">
      <div class="modal-head">
        <h3>Place a bid</h3>
        <span class="close-bid-modal">&times;</span>
      </div>
      <div class="modal-body">
        <label class="item-description"
          >You are about to purchase
          <span class="item-name">{{ singleNft.name }}</span> from billion.
          Check information then proceed to payment</label
        >

        <div class="form-section">
          <form id="bidForm" autocomplete="off">
            <div class="form-divide">
              <label class="input-label">Your bid</label>
              <input
                v-model.number="bid_input"
                class="modal-input dropdown-bid"
                type="number"
                id="bid-input"
                name="bid"
                placeholder="Enter bid"
                step=".01"
              />
              <span class="link-url-end currency-btn"
                ><span id="selectedCurrency">BHC</span>
                <i class="fa fa-angle-down"></i
              ></span>

              <div class="currency-drop d-none">
                <div class="drop-group">
                  <a
                    href="javascript:void(0)"
                    id="BHC"
                    class="currency-item"
                    @click="setBHC()"
                    >BHC</a
                  >
                  <i class="fa fa-check currency-check"></i>
                </div>
                <div class="drop-group">
                  <a
                    href="javascript:void(0)"
                    id="BNB"
                    class="currency-item"
                    @click="setBNB()"
                    >BNB</a
                  >
                  <i class="fa fa-check currency-check opacity-0"></i>
                </div>
              </div>
            </div>
            <div class="purchase">
              <div class="purchase-info">
                <label class="text-details">Your balance</label>
                <label class="text-value"
                  >{{ this.selectedBalance }} <span class="changeDD">BHC</span></label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  >{{ service_fee }} <span class="changeDD">BHC</span></label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">You will pay</label>
                <label class="text-value"
                  >{{ total_payment }} <span class="changeDD">BHC</span></label
                >
              </div>
            </div>
            <button
              class="form-submit"
              type="button"
              @click="placeBid()"
            >
			  <span>Place a bid</span>
            </button>
			<span style="color:red">{{error}}</span>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import {
  bid,
  startBidding,
  getHighestBid,
  getBiddingStatus,
  getAllBids,
  endBidding,
  getBHCBalance,
  getBNBBalance,
  getWBNBBalance,
  approveBHCFunc,signBidFunc
} from ".././../bidFunc";

export default {
  props: ["singleNft", "page"],
  data() {
    return {
      bid_input: 0,
      selectedBalance:0,
      BHC_balance: 0,
      BNB_Balance: 0,
	  WBNB_Balance:0,
      service_fee: 0,
      total_payment: 0,
      payment: 0,
      currency: "",
	  error:"",
      nft_id: 0,
      record_id: 0,
      selected_token: 0,
      isApproving: false,
	  isSigning: false,
	  approvingText: "Approving BHC...  <img src='/images/loading.gif' alt='' width='7%' />",
	  signText: "Signing...  <img src='/images/loading.gif' alt='' width='7%' />",
		isApprovingWbnb: false,
		ifWbnb: false,
		approvingWbnbText: "Approving WBNB...  <img src='/images/loading.gif' alt='' width='7%' />",
	isConverting: false,
	convertText: "Converting BNB...  <img src='/images/loading.gif' alt='' width='7%' />",
	
	};
  },
  async mounted() {
    this.BHC_Balance = await getBHCBalance();
    this.BNB_Balance = await getBNBBalance();
	this.WBNB_Balance = await getWBNBBalance();
	this.selected_token = 0;
	this.selectedBalance = this.BHC_Balance;
  },

  computed: {
    balance() {
      if (this.selected_token == 1) {
        return this.BNB_Balance;
      } else {
        return this.BHC_Balance;
      }
    },
	enoughWBNB(){
		if(this.bid_input>this.WBNB_Balance){
				return false;
		}
		else{
			return true;
		}
	},
	enoughBNB()
	{
	if(this.bid_input>this.BNB_Balance){
				return false;
		}
		else{
			return true;
		}
	},
	enoughBHC(){
		if(this.bid_input>this.BHC_Balance){
				return false;
		}
		else{
			return true;
		}
	}

  },

  watch: {
    singleNft: function () {
      this.nft_id = this.singleNft.id;
      this.record_id = this.singleNft.record_id;
    },
    bid_input: function () {
      this.payment = this.bid_input;
      this.service_fee = +(this.payment * 0.025);
      this.total_payment = +(this.payment + +this.service_fee);
    },
  },
  methods: {
   async approveBHC() {

if(this.enoughBHC){
this.error = "";
this.isApproving = true;
var res = await approveBHCFunc(this.payment)
if(res==1){
  this.isApproving = false;
}
}else{
	this.error  = "Not enough Balance"
}

    },
    
async signBid(){
  this.isSigning = true;
   this.currency = $("#selectedCurrency").text();
  let res = await signBidFunc(
        this.singleNft.owner_id,
        this.singleNft.contract,
        this.singleNft.id,
        this.currency,
        this.payment
      );
      if(res==1){
        this.signing = false;

      }
},

    setBNB() {
      this.selected_token = 1;
      this.selectedBalance = this.BNB_Balance;
    },
    setBHC() {
      this.selected_token = 0;
      this.selectedBalance = this.BHC_balance;
    },
    async placeBid() {
      this.currency = $("#selectedCurrency").text();
      let res = await bid(
        this.singleNft.owner_id,
        this.singleNft.contract,
        this.singleNft.id,
        this.currency,
        this.payment
      );
     //window.location.reload();
    },
  },
};
</script>