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
                                    v-model="quantity"
                                />
                            </div>
                            <div class="form-divide">
                                <label class="input-label">Receiver address</label>
                                <input
                                    class="modal-input"
                                    type="text"
                                    name="receiver_address"
                                    placeholder="Paste address"
                                    v-model="receiver_address"
                                />
                            </div>
                            <button @click="transfer_token()" class="form-submit">
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
import { checkConnection,transfer } from ".././../etherFunc";
export default {
    props: ["singleNft","current_user","page"],
    data() {
        return {
            address:""
        };
    },
    methods: {
       async transfer_token(){
            let res = await transfer(this.singleNft.contract, this.singleNft.owner_id, receiver_address, this.singleNft.type, this.singleNft.id, quantity)
            if(res){
                 Toast.fire({
                        icon: "success",
                        title: "Successfully transfered token..!"
                    });
            }  
            else{ 
                 Toast.fire({
                        icon: "error",
                        title: "User rejected transaction!"
                    });
            }     
       }
        
    },
   async mounted() {
       this.quantity =1;
       
    }
};
</script>
