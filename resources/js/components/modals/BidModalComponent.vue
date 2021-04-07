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
              <div class="purchase-info" v-if="this.selected_token == 1">
                <label class="text-details">Your WBNB balance</label>
                <label class="text-value"
                  >{{ this.WBNB_Balance }} <span>WBNB</span></label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">Your balance</label>
                <label class="text-value"
                  >{{ this.selectedBalance }}
                  <span class="changeDD">BHC</span></label
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
            <!--button
              class="form-submit"
              type="button"
              @click="placeBid()"
            >
			  <span>Place a bid</span>
            </button-->

             <button
             v-if="notStarted && enoughWBNB && !isBHC && !approved"
              class="form-submit"
              type="button"
              @click="!approved && !approving?approveWBNBFunc():''"

            >
              <span v-html="approveWBNBText"></span>
            </button>

            <button
              v-if="notStarted && !enoughWBNB && !isBHC"
              class="form-submit"
              type="button"

              @click="!approved && !converting?convertBNB():''"

            >
              <span v-html="convertBNBText"></span>
            </button>


  <button
             v-if="notStarted &&  !approved && isBHC" 
              class="form-submit"
              type="button"
              @click="!approved && !approving?approveBHCFunc():''"

            >
              <span v-html="approveBHCText"></span>
            </button>



             <button
             v-if="approved && !signed"

              class="form-submit"
              type="button"
              @click="!signed ? signBidFunc() : ''"
            >
              <span v-html="signBidText"></span>
            </button>

            <span style="color: red">{{ error }}</span>
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
  approveBHC,
  signBid,
  approveWBNB,
  convertBNBtoWBNB,
} from ".././../bidFunc";

export default {
  props: ["singleNft", "page"],
  data() {
    return {

      showApproveBHC:true,
      rate:2.5,

      bid_input: 0,
      selectedBalance: 0,
      BHC_balance: 0,
      BNB_Balance: 0,
      WBNB_Balance: 0,
      service_fee: 0,
      total_payment: 0,
      payment: 0,
      currency: "",

	  error:"",
    approved:false,
    signed:false,
    converting:false,
    approving: false,signing: false,

      nft_id: 0,
      record_id: 0,
      selected_token: 0,
      notStarted: true,
      isApproving: false,
      isSigning: false,
      approveWBNBText: "Approve WBNB",
      approveBHCText: "Approve BHC",
      signBidText: "Sign Order",
      convertBNBText: "Convert BNB to WBNB",
      approvingText:
        "Approving BHC...  <img src='/images/loading.gif' alt='' width='7%' />",
      signText:
        "Signing...  <img src='/images/loading.gif' alt='' width='7%' />",
      isApprovingWbnb: false,
      ifWbnb: false,
      approvingWbnbText:
        "Approving WBNB...  <img src='/images/loading.gif' alt='' width='7%' />",
      isConverting: false,
      convertText:
        "Converting BNB...  <img src='/images/loading.gif' alt='' width='7%' />",
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
    isBHC() {
      if (this.selected_token == 0) {
        return true;
      } else {
        return false;
      }
    },
    balance() {
      if (this.selected_token == 1) {
        return this.BNB_Balance;
      } else {
        return this.BHC_Balance;
      }
    },
    enoughWBNB() {
      if (this.total_payment > this.WBNB_Balance) {
        return false;
      } else {
        return true;
      }
    },
    enoughBNB() {
      if (this.total_payment > this.BNB_Balance) {
        return false;
      } else {
        return true;
      }
    },
    enoughBHC() {
      if (this.total_payment > this.BHC_Balance) {
        return false;
      } else {
        return true;
      }
    },
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


//////////////////!Approve WBNB////////////////////
async approveWBNBFunc(){
try{
  this.approving = true;
this.approveWBNBText = this.approvingWbnbText;
let res = await approveWBNB(this.total_payment);
if(res){
this.approveWBNBText ="Approved WBNB";
this.approved = true;
this.approving = false;
}}catch(error){
         if (error.code == 4001) {
            Toast.fire({
              icon: "error",
              title: "User rejected transaction!",
            });
          }
}
},
//////////////////!Convert BNB////////////////////
async convertBNB(){
  if(this.enoughBNB){
    try{
  this.converting = true;
  this.convertBNBText = this.convertText;
let res = await convertBNBtoWBNB(this.total_payment);
if(res){
  this.converting = false;
  this.WBNB_Balance += this.payment;
  this.convertBNBText = "Converted BNB to WBNB"
}}catch(error){
         if (error.code == 4001) {

            Toast.fire({
              icon: "error",
              title: "User rejected transaction!",
            });
          }

}}
else{
  	this.error  = "Not enough Balance"
}
},
//////////////////!Approve BHC////////////////////
 async approveBHCFunc() {
   this.approving = true;
this.approveBHCText = this.approvingText;
if(this.enoughBHC){
try{
var res = await approveBHC(this.total_payment)
if(res==1){
  this.approving = false
  this.approveBHCText = "Approved BHC"
  this.approved = true;
}}catch(error){
         if (error.code == 4001) {

            Toast.fire({
              icon: "error",
              title: "User rejected transaction!",
            });
          }
        }
      } else {
        this.error = "Not enough Balance";
      }
    },
    //////////////////!Sign Bid////////////////////

    async signBidFunc() {
      let pay_token;
      this.signBidText = this.signText;
      this.currency = $("#selectedCurrency").text();
      console.log(this.selected_token == 0);
      if (this.selected_token == 0) {
        pay_token = "0xE19DD2fa7d332E593aaf2BBe4386844469e51937";
      } else {
        pay_token = "0xae13d989dac2f0debff460ac112a837c89baa7cd";
      }
      try {
        let res = await signBid(
          this.singleNft.owner_id,
          this.singleNft.contract,
          this.singleNft.id,
          pay_token,
          this.payment
        );
        if (res) {
          let message =
            "You have place a bid of " +
            this.payment +
            " " +
            this.currency +
            " to token " +
            this.singleNft.name +
            " in the collection " +
            this.singleNft.collection.name;
          let data = {};
          data.message = message;
          data.user_id = window.ethereum.selectedAddress;
          data.amount = this.payment;
          data.noBuy = true;
          await axios.post("/addNotification", data, {}).then((res) => {
            console.log(res.data);
          });

          this.signed = true;
          this.signBidText = "Signed and Placed Order";
          $("#bidModal").removeClass("d-block");
        }
      } catch (error) {
        if (error.code == 4001) {
          Toast.fire({
            icon: "error",
            title: "User rejected transaction!",
          });
        }
      }
    },

    //////////////////!Set BNB////////////////////
    setBNB() {
      this.selected_token = 1;
      this.selectedBalance = this.BNB_Balance;
    },
    //////////////////!Set BHC////////////////////
    setBHC() {
      this.selected_token = 0;
      this.selectedBalance = this.BHC_balance;
    },
    //////////////////!Place Bid////////////////////
    async placeBid() {
      this.currency = $("#selectedCurrency").text();

      try{
      let res = await bid(
        this.singleNft.owner_id,
        this.singleNft.contract,
        this.singleNft.id,
        this.currency,
        this.payment
      );
      console.log(res);
      let message ="You have place a bid of "+this.payment +" "+this.currency+" to token "+this.singleNft.name+ " in the collection "+this.singleNft.collection.name;
      let success = true;
      
       if(success){

          let data={};

          data.message = message;
          data.user_id = window.ethereum.selectedAddress;
          await axios.post("/addNotification", data, {}).then((res) => {
            console.log(res.data);
          });

          
          this.approved = false;
         this.signed = false;
         //window.location.reload();
        }}catch(error){
                 if (error.code == 4001) {
            Toast.fire({
              icon: "error",
              title: "User rejected transaction!",
            });
          }
        
        }
      },catch (error) {
        if (error.code == 4001) {
          Toast.fire({
            icon: "error",
            title: "User rejected transaction!",
          });
        }
      }
  },
};
</script>