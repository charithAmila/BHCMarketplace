<template>
    <div id="putOnSaleModal" class="custom-modal d-none">
        <div class="modal-content putOnSale-content">
            <div class="modal-head">
                <h3>Put on Sale</h3>
                <span class="close-putOnSale-modal">&times;</span>
            </div>
            <div class="modal-body">
                <label class="item-description"
                    >You are about to put the
                    <span class="item-name">{{ singleNft.name }}</span> from
                    {{ singleNft.collection.name }} on sale. Check information
                    then submit</label
                >

                <div class="form-section" @submit.prevent="">
                    <form autocomplete="off" id="saleForm">
                        <div class="form-divide">
                            <input
                                v-model.number="quantity"
                                class="modal-input"
                                type="number"
                                id="checkout-quantity"
                                name="quantity"
                                placeholder="Enter quantity"
                                min="1"
                                :max="singleNft.ownedCopies"
                            />
                            <label class="desc-url">You Have.</label>
                            <label class="desc-url"
                                >{{ singleNft.ownedCopies }} out of
                                {{ singleNft.copies }}</label
                            >
                        </div>
                        <div class="form-divide">
                            <input
                                class="modal-input"
                                type="text"
                                id="checkout-price"
                                name="price"
                                v-model="price"
                            />
                            <span class="link-url-end"
                                ><!--span id="checkout-currency">{{ currency }}</span>
                <i class="fa fa-angle-down"></i>
                <i class="fa fa-lock" aria-hidden="true"></i
              -->
                                <span
                                    class="checkout-currency positionHolder"
                                    @click="toggleDropdown('.checkout-drop')"
                                    >{{ currency_label[currency] }}
                                    <i class="fa fa-angle-down"></i
                                ></span>

                                <div
                                    class="checkout-drop d-none"
                                    @click="toggleDropdown('.checkout-drop')"
                                >
                                    <div class="drop-group">
                                        <a
                                            href="javascript:void(0)"
                                            id="BHC"
                                            class="side-drop currency-item"
                                            @click="currency = 1"
                                            >BHC</a
                                        >
                                        <i
                                            class="fa fa-check currency-check"
                                        ></i>
                                    </div>
                                    <!--div class="drop-group">
                    <a
                      href="javascript:void(0)"
                      id="BNB"
                      class="side-drop currency-item"
                      @click="currency = 2"
                      >HPS</a
                    >
                    <i class="fa fa-check currency-check opacity-0"></i>
                  </div-->
                                    <div class="drop-group">
                                        <a
                                            href="javascript:void(0)"
                                            id="BNB"
                                            class="side-drop currency-item"
                                            @click="currency = 2"
                                            >BNB</a
                                        >
                                        <i
                                            class="fa fa-check currency-check opacity-0"
                                        ></i>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <!--div class="purchase">
              <div class="purchase-info">
                <label class="text-details">Your balance</label>
                <label class="text-value"
                  >{{ balance }} {{ singleNft.currency }}</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">Service fee</label>
                <label class="text-value"
                  >{{ service_fee }} {{ singleNft.currency }}</label
                >
              </div>
              <div class="purchase-info">
                <label class="text-details">You will pay</label>
                <label class="text-value"
                  >{{ total_payment }} {{ singleNft.currency }}</label
                >
              </div>
            </div-->
                        <button
                            v-if="!signed"
                            class="form-submit"
                            @click="
                                !signing && quantity <= singleNft.ownedCopies
                                    ? sign()
                                    : ''
                            "
                        >
                            <span v-html="signText"></span>
                        </button>

                        <button
                            v-if="!singleNft.approved && signed"
                            class="form-submit"
                            @click="!approving ? approveNFT() : ''"
                        >
                            <span v-html="approveText"></span>
                        </button>

                        <button
                            class="form-submit"
                            v-if="signed && singleNft.approved"
                            @click="placeOrder()"
                        >
                            <span v-html="saleText"></span>
                        </button>

                        <!--button class="cancel-btn" type="button">Cancel</button-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import {
    generateOrderIdMessage,
    signMessage,
    checkNFTApproved,
    approveNFT,
    waitForTransaction,
    toAddress
} from "./../../etherFunc";
import { hpsAddress, bhcAddress } from "./../../addresses/constants";
import { addSale } from "./../../data";
export default {
    props: ["singleNft", "page"],
    data() {
        return {
            signing: false,
            approving: false,
            signText: "Sign",
            approveText: "Approve",
            saleText: "Put on sale",
            quantity: 1,
            balance: 0,
            service_fee: 0,
            total_payment: 0,
            payment: 0,
            price: 0,
            currency: 1,
            nft_id: 0,
            record_id: 0,
            s: "",
            signed: false,
            salt: "",
            progress: "Sign Order",
            approvingText:
                "Approving...<img src='/images/loading.gif' alt='' width='7%' />",
            signingText:
                "Signing...  <img src='/images/loading.gif' alt='' width='7%' />",
            test: "",
            processing: false,
            orderId: "",
            approved: false,
            currency_label: {
                1: "BHC",
                2: "BNB"
            }
        };
    },
    watch: {
        singleNft: async function() {
            //this.price = +parseFloat(this.singleNft.total).toFixed(2);
            //this.nft_id = this.singleNft.id;
            //this.record_id = this.singleNft.record_id;
            //this.updateValues();
            this.signed = false;
        },
        quantity: function() {
            this.updateValues();
        }
    },
    methods: {
        toggleDropdown(ct) {
            var container;
            container = $(ct);
            if (!container.hasClass("fade-in-top")) {
                container.toggleClass("d-none");
                container.addClass("fade-in-top").removeClass("fade-out-top");
            } else {
                container.addClass("fade-out-top").removeClass("fade-in-top");
                setTimeout(function() {
                    container.toggleClass("d-none");
                }, 400);
            }
        },
        async sign() {
            try {
                this.signText = this.signingText;
                this.signing = true;
                const _this = this;
                _this.salt = Math.random()
                    .toString(36)
                    .substring(7);

                var orderId = await generateOrderIdMessage(
                    _this.singleNft.contract,
                    _this.singleNft.id,
                    _this.quantity,
                    _this.currency == 1 ? bhcAddress : toAddress(""),
                    _this.price,
                    _this.salt
                );

                var sig = await signMessage(orderId);

                _this.s = sig;
                _this.signed = true;
                _this.progress = "Put Order";
                _this.orderId = orderId;
            } catch (error) {
                if (error.code == 4001) {
                    alert("User rejected minting token");
                    this.signing = false;
                }
                this.signing = false;
                this.signText = "Sign";
            }
        },
        async approveNFT() {
            try {
                this.approveText = this.approvingText;
                this.approving = true;
                var tx = await approveNFT(this.singleNft.contract);
                waitForTransaction(tx.hash).then(data => {
                    if (data.status) {
                        this.approved = true;
                        this.singleNft.approved = true;
                    }
                });
            } catch (error) {
                if (error.code == 4001) {
                    alert("User rejected minting token");
                    this.approving = false;
                    this.approveText = "Approve";
                }
            }
        },

        async placeOrder() {
            const _this = this;
            var data = {
                collection: _this.singleNft.contract,
                current_owner: _this.singleNft.owner_id,
                token_id: _this.singleNft.id,
                signed_to: Number(_this.quantity),
                price: Number(_this.price),
                is_instant: false,
                currency: _this.currency == 1 ? bhcAddress : toAddress(""),
                signature: _this.s,
                order_id: _this.orderId,
                salt: _this.salt
            };
            await addSale(data);
            $(".putOnSale-content")
                .removeClass("fade-in-bottom")
                .addClass("fade-out-bottom");
            setTimeout(function() {
                $("#putOnSaleModal").removeClass("d-block");
            }, 400);
            $(".toast-message").text("Added to Marketplace");
            $("#saleForm").trigger("reset");
            setTimeout(function() {
                launch_toast();
            }, 500);
        },
        updateValues() {
            this.payment = +(this.price * this.quantity).toFixed(2);
            this.service_fee = +(this.payment * 0.025).toFixed(2);
            this.total_payment = +(this.payment + +this.service_fee).toFixed(2);
        },
        purchase() {
            this.currency = $("#checkout-currency").text();

            axios
                .post("/create/transaction", {
                    type: "buy",
                    nft_id: this.nft_id,
                    price: this.payment,
                    currency: this.currency,
                    record_id: this.record_id,
                    quantity_input: this.quantity
                })
                .then(res => {
                    $(".toast-message").text(res.data.message);
                    $("#purchaseForm").trigger("reset");
                    setTimeout(function() {
                        launch_toast();
                    }, 500);
                    modalClose($("#checkoutModal"), $(".checkout-content"));
                    this.service_fee = 0;
                    this.total_payment = 0;
                    this.payment = 0;
                    this.bid_input = "";
                    this.quantity = 1;

                    if (this.page == "marketplace" || this.page == "profile") {
                        this.$parent.$parent.getCollectible();
                    }
                    if (this.page == "marketplace") {
                        this.$parent.$parent.$parent.updateTopUser();
                    }
                    if (this.page == "showcollectible") {
                        this.$parent.updateData();
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>
