<template>
    <div>
        <div
            id="transferModal"
            class="custom-modal d-none"
        >
            <div class="modal-content transfer-content">
                <div class="modal-head">
                    <h3>Transfer Token</h3>
                    <div>
                        <span class="close-transfer-modal">&times;</span>
                    </div>
                </div>
                <div class="modal-body">
                    <label class="report-description">You can transfer tokens from your address to another</label>

                    <div class="form-section">
                        <form
                            autocomplete="off"
                            id="transferForm"
                        >
                            <div class="form-divide">
                                <label class="input-label">Quantity</label>
                                <input
                                    class="modal-input"
                                    type="number"
                                    name="quantity"
                                    placeholder="quantity"
                                    value="1"
                                />
                            </div>
                            <div class="form-divide">
                                <label class="input-label">Receiver address</label>
                                <input
                                    class="modal-input"
                                    type="text"
                                    name="receiver_address"
                                    placeholder="Paste address"
                                />
                            </div>
                            <button class="form-submit" type="submit">
                                Transfer
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
import { checkConnection } from ".././../etherFunc";
export default {
    props: ["singleNft","current_user","page"],
    data() {
        return {
        };
    },
    methods: {
        
    }
};
</script>
