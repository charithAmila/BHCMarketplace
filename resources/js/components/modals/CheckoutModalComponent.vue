<template>
    <div id="checkoutModal" class="custom-modal d-none">
        <div class="modal-content checkout-content">
            <div class="modal-head">
                <h3>Checkout</h3>
                <span class="close-checkout-modal">&times;</span>
            </div>
            <div class="modal-body">
                <label class="item-description"
                    >You are about to purchase
                    <span class="item-name">{{ singleNft.name }}</span> from
                    <span class="item-name">{{ creator }}</span
                    >. Check information then proceed to payment</label
                >

                <div class="form-section">
                    <form
                        autocomplete="off"
                        id="purchaseForm"
                        @submit.prevent="
                            enoughBalance && !purchasing ? purchase() : ''
                        "
                    >
                        <div class="form-divide">
                            <input
                                v-model.number="quantity"
                                class="modal-input"
                                type="number"
                                id="checkout-quantity"
                                name="quantity"
                                placeholder="Enter quantity"
                                min="1"
                                :max="singleNft.on_sale"
                            />
                            <label class="desc-url">Enter quantity.</label>
                        </div>
                        <div class="form-divide">
                            <input
                                class="modal-input"
                                type="text"
                                id="checkout-price"
                                name="price"
                                readonly
                                :value="singleNft.price * quantity"
                            />
                            <span class="link-url-end"
                                ><span id="checkout-currency">{{
                                    singleNft.currencyName
                                }}</span>
                                <i class="fa fa-angle-down"></i>
                                <i class="fa fa-lock" aria-hidden="true"></i
                            ></span>
                        </div>
                        <div class="purchase">
                            <div class="purchase-info">
                                <label class="text-details">Your balance</label>
                                <label class="text-value"
                                    >{{ Number(balance).toFixed(3) }}
                                    {{ singleNft.currencyName }}</label
                                >
                            </div>
                            <div class="purchase-info">
                                <label class="text-details">Service fee</label>
                                <label class="text-value"
                                    >{{ Number(service_fee).toFixed(3) }}
                                    {{ singleNft.currencyName }}</label
                                >
                            </div>
                            <div class="purchase-info">
                                <label class="text-details">You will pay</label>
                                <label class="text-value"
                                    >{{ Number(total_payment).toFixed(3) }}
                                    {{ singleNft.currencyName }}</label
                                >
                            </div>
                        </div>
                        <button
                            class="form-submit"
                            @click.prevent="approve"
                            v-if="!approved && enoughBalance"
                        >
                            <span v-html="approveText"></span>
                        </button>
                        <button
                            class="form-submit"
                            type="submit"
                            v-if="approved"
                            :disabled="!enoughBalance"
                        >
                            <span v-html="proceedToPaymentText"></span>
                        </button>

                        <label class="text-details" v-if="!enoughBalance"
                            >Low Balance</label
                        >
                        <label
                            class="text-details"
                            v-if="allowance < total_payment"
                            >Low Allowance</label
                        >
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
    checkTokensApproved,
    approveTokens,
    checkTokensBalance,
    buy,
    waitForTransaction,
    toAddress,
    getBNBBalance,
    serviceFee
} from "./../../etherFunc";
import { removeSale, bought, getUserDetails } from "../../data";
import { bhcAddress } from "./../../addresses/constants";
export default {
    props: ["singleNft", "page", "current_user"],
    data() {
        return {
            purchasing: false,
            quantity: 1,
            balance: 0,
            allowance: 0,
            enoughBalance: false,
            service_fee: 0,
            royalty_fee: 0,
            total_payment: 0,
            payment: 0,
            price: 0,
            currency: "",
            approveText: "Approve",
            proceedToPaymentText: "Proceed to payment",
            approvingText:
                "Approving...<img src='/images/loading.gif' alt='' width='7%' />",
            processPaymentText:
                "Processing payment...<img src='/images/loading.gif' alt='' width='7%' />",
            purchasing: false,
            nft_id: 0,
            record_id: 0,
            approved: false,
            creator: "John Doe"
        };
    },
    async mounted() {
        this.checkEligibility();
    },
    watch: {
        singleNft: async function() {
            this.checkEligibility();
            var res = await getUserDetails(this.singleNft.creator);
            this.creator = res.name;
        },
        quantity: async function() {
            this.checkEligibility();
        }
    },
    methods: {
        async checkEligibility() {
            this.singleNft.currencyName == "BHC"
                ? (this.currency = bhcAddress)
                : (this.currency = toAddress(""));
            this.price = this.singleNft.price;
            this.nft_id = this.singleNft.id;
            this.record_id = this.singleNft.record_id;
            await this.updateValues();
            if (this.currency != toAddress("")) {
                var allowance = await checkTokensApproved(
                    this.currency,
                    this.current_user
                );
                this.balance = await checkTokensBalance(
                    this.currency,
                    this.current_user
                );
                this.allowance = allowance;
            } else {
                this.balance = await getBNBBalance(this.current_user);
                var allowance = this.balance;
                this.allowance = allowance;
            }
            this.allowance = allowance;
            //removed//console.log(allowance);
            if (this.total_payment * 1.03 <= allowance) {
                this.approved = true;
            } else {
                this.approved = false;
            }
            if (this.balance * 1.03 < this.total_payment) {
                this.enoughBalance = false;
            } else {
                this.enoughBalance = true;
            }
        },
        async updateValues() {
            this.payment = +(this.price * this.quantity);
            this.service_fee = await serviceFee(this.singleNft.currencyName);
            this.royalty_fee = (this.payment * this.singleNft.royalties) / 100;
            this.total_payment = +(this.payment + this.service_fee);
        },
        async approve() {
            try {
                const _this = this;
                this.approveText = this.approvingText;
                var hash = await approveTokens(
                    this.currency,
                    `${Number(this.total_payment)}`
                );
                waitForTransaction(hash).then(data => {
                    if (data.status) {
                        _this.approved = true;
                        _this.allowance = _this.total_payment;
                    }
                });
            } catch (error) {
                this.approveText = "Approve";
                Toast.fire({
                    icon: "error",
                    title: "User rejected transaction!"
                });
            }
        },
        purchase() {
            const _this = this;
            this.proceedToPaymentText = this.processPaymentText;
            this.purchasing = true;
            var collectible = this.singleNft;
            let currency =
                this.currency == "0x6fd7c98458a943f469E1Cf4eA85B173f5Cd342F4"
                    ? "BHC"
                    : "BNB";
            let message_buyer =
                "You have successfully purchased " +
                collectible.name +
                " for " +
                `${this.price} ` +
                currency;

            let message_seller =
                "The " +
                collectible.name +
                " has been bought for " +
                `${this.price} ` +
                currency;

            buy(
                collectible.contract,
                collectible.type == 721 ? true : false,
                collectible.id,
                collectible.signed_to,
                _this.quantity,
                _this.currency,
                `${_this.price}`,
                collectible.salt,
                collectible.owner_id,
                collectible.signature,
                _this.total_payment
            )
                .then(async function(hash) {
                    var data = await waitForTransaction(hash);
                    if (data.status) {
                        var req = {};
                        req.message_seller = message_seller;
                        //removed//console.log(req);
                        req.message_buyer = message_buyer;
                        req.buyer_id = toAddress(_this.current_user);
                        req.amount = _this.total_payment;
                        req.seller_id = collectible.owner_id;
                        req.token_id = collectible.id;
                        req.owner = collectible.owner_id;
                        req.contract = collectible.contract;
                        req.currency = collectible.currencyName;
                        req.type = "sell";
                        axios.post("/addNotification", req).then(res => {
                            //removed//console.log(res.data);
                        });

                        await removeSale(
                            collectible.contract,
                            collectible.id,
                            collectible.signed_to,
                            _this.currency,
                            `${_this.price}`,
                            collectible.salt,
                            collectible.db_id
                        );
                        await bought(collectible.db_id, _this.quantity);

                        $(".toast-message").text("Purchased");
                        $("#purchaseForm").trigger("reset");
                        setTimeout(function() {
                            launch_toast();
                        }, 500);

                        modalClose($("#checkoutModal"), $(".checkout-content"));
                        _this.service_fee = 0;
                        _this.total_payment = 0;
                        _this.payment = 0;
                        _this.bid_input = "";
                        _this.quantity = 1;
                        _this.purchasing = false;
                        _this.proceedToPaymentText = "Proceed to payment";
                        if (
                            _this.page == "marketplace" ||
                            _this.page == "profile"
                        ) {
                            try {
                                _this.$parent.$parent.getCollectible();
                            } catch (e) {}
                           let req = {};
                            req.message_seller = message_seller;
                            req.message_buyer = message_buyer;
                            req.buyer_id = toAddress(_this.current_user);
                            req.buy_amount = _this.price;
                            req.seller_id = collectible.owner_id;
                            req.type = "sell";
                            await axios
                                .post("/addNotification", req, {})
                                .then(res => {
                                    //removed//console.log(res.data);
                                });
                        }
                        if (_this.page == "marketplace") {
                            try {
                                _this.$parent.$parent.$parent.updateTopUser();
                            } catch (e) {}
                           let req = {};
                            req.message_seller = message_seller;
                            req.message_buyer = message_buyer;
                            req.buyer_id = toAddress(_this.current_user);
                            req.buy_amount = _this.total_payment;
                            req.seller_id = collectible.owner_id;
                            req.type = "sell";
                            await axios
                                .post("/addNotification", req, {})
                                .then(res => {
                                    //removed//console.log(res.data);
                                });
                            window.location.reload();
                        }
                        if (_this.page == "showcollectible") {
                            window.location.href = `/nft/${_this.singleNft.contract}:${_this.singleNft.id}:${_this.current_user}`;
                            //_this.$parent.updateData();
                        }
                    }
                })
                .catch(error => {
                    console.log(error);
                    _this.proceedToPaymentText = "Proceed to payment";
                    _this.purchasing = false;
                    Toast.fire({
                        icon: "error",
                        title: "Something went wrong!"
                    });
                });
        },
        truncate(string) {
            if (string.length <= 18) {
                return string;
            }
            return string.slice(0, 18) + "...";
        }
    }
};
</script>
