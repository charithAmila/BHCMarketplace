<template>
    <div>
        <div
            id="burnModal"
            class="custom-modal d-none"
        >
            <div class="modal-content burn-content">
                <div class="modal-head">
                    <h3>Burn</h3>
                    <div>
                        <span class="close-burn-modal">&times;</span>
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
                                <label class="input-label">Quantity to burn</label>
                                <input
                                    class="modal-input"
                                    type="text"
                                    name="quantity"
                                    placeholder="New Price"
                                />
                            </div>
                            <button class="form-submit" type="submit">
                                Burn
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
