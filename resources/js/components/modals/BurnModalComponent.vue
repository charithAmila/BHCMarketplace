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
                                    type="number"
                                    name="quantity"
                                    placeholder="Enter Quantity"
                                    v-model="quantity"
                                    @click.prevent="burn_token()"
                                />
                            </div>
                            <button class="form-submit" @click.prevent="burn_token()">
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
import { checkConnection,burn} from ".././../etherFunc";
export default {
    props: ["singleNft","current_user","page"],
    data() {
        return {
                 address:"",
            quantity:1
        };
    },
    methods: {
               async burn_token(){
                   const _this = this;
           console.log("Burn token");
            let res = await burn(_this.singleNft.contract, _this.singleNft.owner_id,_this.singleNft.type, _this.singleNft.id, _this.quantity)
            if(res){
                 Toast.fire({
                        icon: "success",
                        title: "Successfully burned token..!"
                    });
            }  
            else{ 
                 Toast.fire({
                        icon: "error",
                        title: "User rejected transaction!"
                    });
            }     
       }
        
    }
};
</script>
