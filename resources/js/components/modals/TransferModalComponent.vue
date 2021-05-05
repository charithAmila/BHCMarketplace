<template>
    <div>
        <div id="transferModal" class="custom-modal d-none">
            <div class="modal-content transfer-content">
                <div class="modal-head">
                    <h3>Transfer Token</h3>
                    <div>
                        <span class="close-transfer-modal">&times;</span>
                    </div>
                </div>
                <div class="modal-body">
                    <label class="report-description"
                        >You can transfer tokens from your address to
                        another</label
                    >

                    <div class="form-section">
                        <form autocomplete="off" id="transferForm">
                            <div class="form-divide">
                                <label class="input-label">Quantity</label>
                                <input
                                    class="modal-input"
                                    type="number"
                                    name="quantity"
                                    placeholder="quantity"
                                    v-model="quantity"
                                />
                            </div>
                            <div class="form-divide">
                                <label class="input-label"
                                    >Receiver address</label
                                >
                                <input
                                    class="modal-input"
                                    type="text"
                                    name="receiver_address"
                                    placeholder="Paste address"
                                    v-model="receiver_address"
                                />
                            </div>
                            <button
                                @click.prevent="transfer_token()"
                                class="form-submit"
                            >
                                <span v-html="transfer_text"></span>
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
import { checkConnection, transfer } from ".././../etherFunc";
export default {
    props: ["singleNft", "current_user", "page"],
    data() {
        return {
            address: "",
            receiver_address: "",
            quantity: 1,
            transfer_text: "Transfer"
        };
    },
    methods: {
        async transfer_token() {
            try {
                this.transfer_text =
                    "Transferring Token...  <img src='/images/loading.gif' alt='' width='7%' />";
                const _this = this;
                let res = await transfer(
                    _this.singleNft.contract,
                    _this.singleNft.owner_id,
                    _this.receiver_address,
                    _this.singleNft.copies == 1 ? 721 : 1155,
                    _this.singleNft.id,
                    _this.quantity
                );
                if (res) {
                    this.transfer_text = "Transfer";
                    Toast.fire({
                        icon: "success",
                        title: "Successfully transfered token..!"
                    });
                    modalClose($("#transferModal"), $(".transfer-content"));
                    modalClose($("#transferModal"), $(".transfer-content"));
                } else {
                    this.transfer_text = "Transfer";
                    Toast.fire({
                        icon: "error",
                        title: "Transaction failed!"
                    });
                }
            } catch (error) {
                this.transfer_text = "Transfer";
                //removed//console.log(error);
                Toast.fire({
                    icon: "error",
                    title: "User rejected transaction!"
                });
                modalClose($("#transferModal"), $(".transfer-content"));
            }
        }
    },
    async mounted() {}
};
</script>
