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
                    <span class="item-name">{{ singleNft.name }}</span> from 
                    {{this.creator}}. Check information then proceed to payment</label
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
                                    <i
                                        class="fa fa-check currency-check opacity-0"
                                    ></i>
                                </div>
                            </div>
                        </div>
                        <div class="purchase">
                            <div
                                class="purchase-info"
                                v-if="this.selected_token == 1"
                            >
                                <label class="text-details"
                                    >Your WBNB balance</label
                                >
                                <label class="text-value"
                                    >{{ this.WBNB_Balance }}
                                    <span>WBNB</span></label
                                >
                            </div>
                            <div class="purchase-info">
                                <label class="text-details">Your balance</label>
                                <label class="text-value"
                                    >{{ this.selectedBalance }}
                                    <span class="changeDD">BHC</span></label
                                >
                            </div>
                            <!--div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  >{{ service_fee }} <span class="changeDD">BHC</span></label
                >
              </div-->
                            <div class="purchase-info">
                                <label class="text-details">You will pay</label>
                                <label class="text-value"
                                    >{{ total_payment }}
                                    <span class="changeDD">BHC</span></label
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
                            v-if="
                                notStarted && enoughWBNB && !isBHC && !approved
                            "
                            class="form-submit"
                            type="button"
                            @click="
                                !approved && !approving ? approveWBNBFunc() : ''
                            "
                        >
                            <span v-html="approveWBNBText"></span>
                        </button>

                        <button
                            v-if="notStarted && !enoughWBNB && !isBHC"
                            class="form-submit"
                            type="button"
                            @click="
                                !approved && !converting ? convertBNB() : ''
                            "
                        >
                            <span v-html="convertBNBText"></span>
                        </button>

                        <button
                            v-if="notStarted && !approved && isBHC"
                            class="form-submit"
                            type="button"
                            @click="
                                !approved && !approving ? approveBHCFunc() : ''
                            "
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
    convertBNBtoWBNB
} from ".././../bidFunc";
import { bhcAddress, WBNB_tokenAddress } from ".././../addresses/constants";
import { collectionURI, checkConnection } from ".././../etherFunc";
import { getUserDetails } from ".././../data";

export default {
    props: ["singleNft", "page"],
    data() {
        return {
            showApproveBHC: true,
            rate: 2.5,
            collection_data: {},
            bid_input: 0,
            selectedBalance: 0,
            BHC_Balance: 0,
            BNB_Balance: 0,
            WBNB_Balance: 0,
            service_fee: 0,
            total_payment: 0,
            payment: 0,
            currency: "",
            message_bidder: "",
            message_owner: "",
            error: "",
            approved: false,
            signed: false,
            converting: false,
            approving: false,
            signing: false,
            creator:'User',
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
                "Converting BNB...  <img src='/images/loading.gif' alt='' width='7%' />"
        };
    },
    async mounted() {
        this.BHC_Balance = await getBHCBalance();
        this.BNB_Balance = await getBNBBalance();
        this.WBNB_Balance = await getWBNBBalance();
        this.selected_token = 0;
        this.selectedBalance = this.BHC_Balance;
         var res = await getUserDetails(this.singleNft.creator);
         console.log("creator");
         console.log(this.singleNft.creator);
         console.log(res);
            this.creator = res.name;
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
        }
    },

    watch: {
        singleNft: function() {
            this.nft_id = this.singleNft.id;
            this.record_id = this.singleNft.record_id;
        },
        bid_input: function() {
            this.payment = this.bid_input;
            this.service_fee = +(this.payment * 0);
            this.total_payment = +(this.payment + +this.service_fee);
        }
    },
    methods: {
        //////////////////!Approve WBNB////////////////////
        async approveWBNBFunc() {
            try {
                this.approving = true;
                this.approveWBNBText = this.approvingWbnbText;
                let res = await approveWBNB(this.total_payment);
                if (res) {
                    this.approveWBNBText = "Approved WBNB";
                    this.approved = true;
                    this.approving = false;
                }
            } catch (error) {
                this.approving = false;
                this.approveWBNBText = "Approve WBNB";
                Toast.fire({
                    icon: "error",
                    title: "User rejected transaction!"
                });
            }
        },
        //////////////////!Convert BNB////////////////////
        async convertBNB() {
            if (this.enoughBNB) {
                try {
                    this.converting = true;
                    this.convertBNBText = this.convertText;
                    let res = await convertBNBtoWBNB(this.total_payment);
                    if (res) {
                        this.converting = false;
                        this.WBNB_Balance += this.payment;
                        //this.convertBNBText = "Converted BNB to WBNB";
                    }
                } catch (error) {
                    this.converting = false;
                    this.convertBNBText = "Convert BNB to WBNB";
                    Toast.fire({
                        icon: "error",
                        title: "User rejected transaction!"
                    });
                }
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Not enough balance!"
                });
                this.error = "Not enough Balance";
            }
        },
        //////////////////!Approve BHC////////////////////
        async approveBHCFunc() {
            ////////////////////////////////////////////////////////////////
            ////removed//console.log("Contract");
            //  //removed//console.log(singleNft.contract);
            //  this.collection_data = await collectionURI(singleNft.contract);
            ////////////////////////////////////////////////////////////////
            if (this.enoughBHC) {
                this.approving = true;
                this.approveBHCText = this.approvingText;
                try {
                    var res = await approveBHC(this.total_payment);
                    if (res == 1) {
                        this.approving = false;
                        //this.approveBHCText = "Approved BHC";
                        this.approved = true;
                    }
                } catch (error) {
                    this.approving = false;
                    this.approveBHCText = "Approve BHC";
                    Toast.fire({
                        icon: "error",
                        title: "User rejected transaction!"
                    });
                }
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Not enough balance!"
                });
                this.error = "Not enough Balance";
            }
        },
        //////////////////!Sign Bid////////////////////

        async signBidFunc() {
            //  //removed//console.log( singleNft);
            // this.collection_data= await collectionURI(this.singleNft.contract);
            //  //removed//console.log("DATA");
            // //removed//console.log(this.collection_data);

            let pay_token;
            this.signBidText = this.signText;
            this.currency = $("#selectedCurrency").text();
            //removed//console.log(this.selected_token == 0);
            if (this.selected_token == 0) {
                pay_token = bhcAddress;
            } else {
                pay_token = WBNB_tokenAddress;
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
                    let address = await checkConnection();
                    let details = await getUserDetails(address);

                    let message_bidder =
                        "You have place a bid of " +
                        this.payment +
                        " " +
                        this.currency +
                        " to token " +
                        this.singleNft.name;

                    let message_owner =
                        details.name +
                        " placed a bid of " +
                        this.payment +
                        " " +
                        this.currency +
                        " to token " +
                        this.singleNft.name;
                    // +" in the collection " +
                    // this.singleNft.collection.name;
                    let data = {};
                    data.message_owner = message_owner;
                    data.message_bidder = message_bidder;
                    data.user_id = address;
                    data.owner = this.singleNft.owner_id;
                    data.token_id = this.singleNft.id;
                    data.contract = this.singleNft.contract;
                    data.amount = this.payment;
                    data.type = "bid";
                    data.currency = this.currency;
                    await axios.post("/addNotification", data).then(res => {
                        //removed//console.log(res.data);
                    });

                    this.signed = true;
                    this.signBidText = "Signed and Placed Order";
                    Toast.fire({
                        icon: "success",
                        title: "Successfully placed a bid!"
                    });
                    $("#bidModal").removeClass("d-block");
                }
            } catch (error) {
                Toast.fire({
                    icon: "error",
                    title: "User rejected transaction!"
                });
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
            this.selectedBalance = this.BHC_Balance;
        },
        //////////////////!Place Bid////////////////////
        async placeBid() {
            this.currency = $("#selectedCurrency").text();

            try {
                let res = await bid(
                    this.singleNft.owner_id,
                    this.singleNft.contract,
                    this.singleNft.id,
                    this.currency,
                    this.payment
                );

                /* if(this.page=="showCollectible"){
        this.message_bidder =
          "You have place a bid of " +
          this.payment +
          " " +
          this.currency +
          " to token " +
          this.singleNft.name +
          " in the collection ";
        +this.singleNft.collection.name;

            this.message_owner =
          "Your nft "+this.singleNft.name +
          " in the collection ";
        +this.singleNft.collection.name;
          " have received a bid of" +
          this.payment +
          " " +
          this.currency;
      }
      else{
                this.message_bidder =
          "You have place a bid of " +
          this.payment +
          " " +
          this.currency +
          " to token " +
          this.singleNft.name +
          " in the collection ";
        +this.singleNft.collection.name;

            this.message_owner =
          "Your nft "+this.singleNft.name +
          " in the collection ";
        +this.singleNft.collection.name;
          " have received a bid of" +
          this.payment +
          " " +
          this.currency;
      }*/

                let success = true;

                if (success) {
                    /* let data = {};
          data.currency = this.currency;
          data.message = message;
          data.user_id = await checkConnection();
         await axios.post("/addNotification", data, {}).then((res) => {
            //removed//console.log(res.data);
          });*/

                    this.approved = false;
                    this.signed = false;
                    //window.location.reload();
                }
            } catch (error) {
                Toast.fire({
                    icon: "error",
                    title: "User rejected transaction!"
                });
            }
        },
        catch(error) {
            Toast.fire({
                icon: "error",
                title: "User rejected transaction!"
            });
        }
    }
};
</script>
