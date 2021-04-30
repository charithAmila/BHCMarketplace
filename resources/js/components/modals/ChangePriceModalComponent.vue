<template>
    <div>
        <div
            id="changePriceModal"
            class="custom-modal d-none"
        >
            <div class="modal-content changePrice-content">
                <div class="modal-head">
                    <h3>Change Price</h3>
                    <div>
                        <span class="close-changePrice-modal">&times;</span>
                    </div>
                </div>
                <div class="modal-body">
                    <!-- <label class="report-description">You can transfer tokens from your address to another</label> -->

                    <div class="form-section">
                        <form
                            autocomplete="off"
                            id="transferForm"
                        >
                            <div class="form-divide">
                                <label class="input-label">Current Price</label>
                                <label class="input-label">10000 BHC</label>
                            </div>
                            <div class="form-divide">
                                <label class="input-label">New Price</label>
                                <input
                                    class="modal-input"
                                    type="text"
                                    name="new_price"
                                    placeholder="New Price"
                                />
                            </div>
                            <button class="form-submit" type="submit">
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
