<template>
    <div>
        <div id="changePriceModal" class="custom-modal d-none">
            <div class="modal-content changePrice-content">
                <div class="modal-head">
                    <h3>Change Price</h3>
                    <div>
                        <span class="close-changePrice-modal">&times;</span>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- <label class="report-description">You can transfer tokens from your address to another</label> -->

                    <div class="form-section" @submit.prevent>
                        <form autocomplete="off" id="transferForm">
                            <div class="form-divide">
                                <label class="input-label">Current Price</label>
                                <label class="input-label"
                                    >{{ singleNft.price }}
                                    {{ singleNft.currencyName }}</label
                                >
                            </div>
                            <div class="form-divide">
                                <label class="input-label">New Price</label>
                                <input
                                    class="modal-input"
                                    type="text"
                                    name="new_price"
                                    placeholder="New Price"
                                    v-model="price"
                                />
                            </div>
                            <button
                                class="form-submit"
                                v-if="!signed"
                                @click="sign"
                            >
                                {{ signText }}
                            </button>
                            <button
                                class="form-submit"
                                v-if="signed"
                                @click="update"
                            >
                                Change Price
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import {
    checkConnection,
    generateOrderIdMessage,
    signMessage
} from ".././../etherFunc";
import { updateSale } from "../../data";
export default {
    props: ["singleNft", "current_user", "page"],
    data() {
        return {
            signText: "Sign",
            signed: false,
            price: 0,
            sale: "",
            s: "",
            orderId: ""
        };
    },
    methods: {
        async sign() {
            try {
                this.signText = "Signing";
                const _this = this;
                _this.salt = Math.random()
                    .toString(36)
                    .substring(7);

                var orderId = await generateOrderIdMessage(
                    _this.singleNft.contract,
                    _this.singleNft.id,
                    _this.singleNft.on_sale,
                    _this.singleNft.currency,
                    _this.price,
                    _this.salt
                );

                var sig = await signMessage(orderId);

                _this.s = sig;
                _this.signed = true;
                this.signText = "Sign";
                _this.orderId = orderId;
            } catch (error) {
                //removed//console.log(error);
                //if (error.code == 4001) {
                alert("User rejected minting token");
                //this.signing = false;
                //}
                this.signed = false;
                this.signText = "Sign";
            }
        },
        async update() {
            const _this = this;
            var data = {
                signed_to: _this.singleNft.on_sale,
                price: Number(_this.price),
                signature: _this.s,
                order_id: _this.orderId,
                salt: _this.salt
            };
            await updateSale(_this.singleNft.db_id, data);
            $(".changePrice-content")
                .removeClass("fade-in-bottom")
                .addClass("fade-out-bottom");
            setTimeout(function() {
                $("#changePriceModal").removeClass("d-block");
            }, 400);
            $(".toast-message").text("Price Changed!");
            $("#transferForm").trigger("reset");

            setTimeout(function() {
                launch_toast();
            }, 500);
            window.location.reload();
        }
    }
};
</script>
