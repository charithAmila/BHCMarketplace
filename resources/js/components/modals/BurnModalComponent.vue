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
                                />
                            </div>
                            <button class="form-submit" @click.prevent="burn_token()">
                               <span v-html="burn_text"></span>
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
            quantity:1,burn_text:"Burn Token"
        };
    },
    methods: {
               async burn_token(){
                   try{
           const _this = this;
           this.burn_text =  "Burning Token...  <img src='/images/loading.gif' alt='' width='7%' />";
            let res = await burn(_this.singleNft.contract,_this.singleNft.copies==1?721: 1155, _this.singleNft.id, _this.quantity)
           
           if(res){
               this.burn_text = "Burn Token"
                 Toast.fire({
                        icon: "success",
                        title: "Successfully burnt token..!"
                    });
                    modalClose($('#burnModal'), $(".burn-content"));

            }  
            else{ 
                 this.burn_text = "Burn Token"
                 Toast.fire({
                        icon: "error",
                        title: "User rejected transaction!"
                    });
                     modalClose($('#burnModal'), $(".burn-content"));
            } 
                   }
                   catch (error) {
                        this.burn_text = "Burn Token"
                        Toast.fire({
                        icon: "error",
                        title: "User rejected transaction!"
                    });
                     modalClose($('#burnModal'), $(".burn-content"));
                   }
            
       }
        
    }
};
</script>
