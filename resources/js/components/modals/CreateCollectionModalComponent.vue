<template>
    <div id="collectionModal" class="custom-modal">
        <div class="modal-content collection-content">
            <div class="modal-head">
                <h3>Collection</h3>
                <span class="close-custom-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="create-collection">
                    <div class="row">
                        <div class="collection-container col-5 col-md-5">
                            <img
                                id="collection-logo-form"
                                class="collection-logo"
                                :src="asset_url + 'images/avatar2.png'"
                                alt="collection-logo"
                                accept="image/x-png,image/gif,image/jpeg,image/png,image/jpg"
                            />
                        </div>
                        <div class="col-label col-7 col-md-7">
                            <label
                                >Allowed png, gif, jpg. 160x160px
                                Recommended</label
                            >
                            <input
                                type="file"
                                id="collection-file"
                                name="image"
                                class="collection-check"
                                accept="image/x-png,image/gif,image/jpeg"
                            />
                            <input
                                class="collection-btn"
                                type="button"
                                value="Set picture"
                                onclick="document.getElementById('collection-file').click();"
                            />
                            <p class="collection-error text-danger"></p>
                            <span
                                id="image-collection"
                                class="custom-error text-danger"
                            ></span>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-divide">
                            <label class="input-label"
                                >Display name <small>(required)</small></label
                            >
                            <input
                                class="modal-input collection-check collection-input"
                                type="text"
                                name="display_name"
                                placeholder="Enter token name"
                                v-model="name"
                            />
                            <p class="collection-error text-danger"></p>
                            <span
                                id="display_name-collection"
                                class="custom-error text-danger"
                            ></span>
                            <label class="desc-url"
                                >Token name cannot be changed in future</label
                            >
                        </div>

                        <div class="form-divide">
                            <label class="input-label"
                                >Symbol <small>(required)</small></label
                            >
                            <input
                                class="modal-input collection-check collection-input"
                                type="text"
                                name="symbol"
                                placeholder="Enter token symbol"
                                v-model="symbol"
                            />
                            <p class="collection-error text-danger"></p>
                            <span
                                id="symbol-collection"
                                class="custom-error text-danger"
                            ></span>
                        </div>

                        <div class="form-divide">
                            <label class="input-label">Description</label>
                            <input
                                class="modal-input collection-input"
                                type="text"
                                name="description"
                                placeholder="Spread some words about your token collection"
                                v-model="description"
                            />
                            <span
                                id="image-collection"
                                class="custom-error text-danger"
                            ></span>
                        </div>

                        <!--div class="form-divide">
                            <label class="input-label">Short url</label>
                            <span class="link-url">billion.com/</span>
                            <input
                                class="modal-input with-link collection-input"
                                type="text"
                                name="short_url"
                                placeholder="Enter short url"
                            />
                            <span
                                id="short_url-collection"
                                class="custom-error text-danger"
                            ></span>
                        </div-->
                        <div class="col-md-12 create-cmodel-elements">
                            <label>Pay With</label>
                            <select
                                class="form-controll"
                                v-model="payWith"
                                @change="setPaywith()"
                            >
                                <option value="bnb">BNB</option>
                                <option value="hps">HPS</option>
                            </select>
                        </div>
                        <button
                            v-if="pay_with_hps && !isApproved"
                            class="form-submit"
                            type="button"
                            @click="approve"
                        >
                            <span
                                v-html="
                                    isApproving
                                        ? text.approveText
                                        : 'Approve HPS'
                                "
                            ></span>
                        </button>

                        <button
                            id="collection-submit"
                            class="form-submit"
                            type="button"
                            @click="generateCollection"
                        >
                            <span
                                v-html="
                                    isGenerating
                                        ? text.generateText
                                        : 'Generate Collection'
                                "
                            ></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import {
    createCollection,
    waitForTransaction,
    approveTokens,
    getFees
} from "./../../etherFunc.js";
import { hpsAddress } from "./../../addresses/constants";

export default {
    props: ["store_route", "asset_url", "csrf_token", "type"],
    data() {
        return {
            name: "",
            symbol: "",
            description: "",
            icon: "",
            j: "",
            s: "",
            process: "Create Collection",
            processing: false,
            collectionGenerated: false,
            payWith: "bnb",
            pay_with_hps: false,
            isApproving: false,
            isApproved: false,
            isGenerating: false,
            text: {
                approveText:
                    "Appproving HPS... <img src='/images/loading.gif' alt='' width='7%' />",
                generateText:
                    "Generating collection... <img src='/images/loading.gif' alt='' width='7%' />"
            }
        };
    },
    methods: {
        closeModel() {
            setTimeout(function() {
                $("#collectionModal").removeClass("d-block");
            }, 3000);
        },
        setPaywith() {
            if (this.payWith == "hps") {
                this.pay_with_hps = true;
            } else {
                this.pay_with_hps = false;
            }
        },
        async aqquireKeys() {
            const _this = this;
            //await axios.get("/api/keygen").then((res) => {
            //_this.j = res.data.JWT;
            //});
            _this.j =
                "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySW5mb3JtYXRpb24iOnsiaWQiOiJkM2VjNWQzNy01MmQ0LTRlYjMtYmEyNC1kNmRjYmY4YTY1NDMiLCJlbWFpbCI6ImJpbGxpb25oYXBwaW5lc3NAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsInBpbl9wb2xpY3kiOnsicmVnaW9ucyI6W3siaWQiOiJOWUMxIiwiZGVzaXJlZFJlcGxpY2F0aW9uQ291bnQiOjF9XSwidmVyc2lvbiI6MX0sIm1mYV9lbmFibGVkIjpmYWxzZX0sImF1dGhlbnRpY2F0aW9uVHlwZSI6InNjb3BlZEtleSIsInNjb3BlZEtleUtleSI6IjQyZmNmMDViN2ZlZGFmNjBiMzNlIiwic2NvcGVkS2V5U2VjcmV0IjoiMGJlZTgwY2Q2OWY3MWVkMWZjZjViNTcxZjcyMjU3M2QwYTEzMzlhZjg4OWYzYjUyYTYwY2RlOGUxOTI1OTRmNSIsImlhdCI6MTYxODY2MjEyN30.8FlNm4h2vEW7TrTO5_xQf_GXQ2SkgXGJ1cepl4kkBrE";
        },
        async approve() {
            try {
                this.isApproving = true;
                var fee = await getFees();
                var hash = await approveTokens(hpsAddress, `${fee}`);
                waitForTransaction(hash).then((data, error) => {
                    if (error) {
                        error.code == 4001
                            ? alert("Rejected approving HPS")
                            : null;
                    } else if (data.status) {
                        this.isApproved = true;
                    } else {
                        alert("Try again!");
                    }
                    this.isApproving = false;
                });
            } catch (error) {
                this.isApproving = false;
                if (error.code == 4001) {
                    alert("user rejected approving");
                }
                this.isApproving = false;
            }
        },
        async generateCollection() {
            const FormData = require("form-data");
            let data = new FormData();
            const _this = this;
            var details = {};
            var url;
            _this.isGenerating = true;
            await _this.aqquireKeys();

            //we gather a local file for this example, but any valid readStream source will work here.
            if ($("#collection-file").get(0).files.length > 0) {
                var url = `https://api.pinata.cloud/pinning/pinFileToIPFS`;
                data.append("file", $("#collection-file").prop("files")[0]);

                //You'll need to make sure that the metadata is in the form of a JSON object that's been convered to a string
                //metadata is optional
                const metadata = JSON.stringify({
                    name: _this.name,
                    keyvalues: {
                        proPic: _this.name
                    }
                });
                data.append("pinataMetadata", metadata);

                //pinataOptions are optional
                const pinataOptions = JSON.stringify({
                    cidVersion: 0,
                    customPinPolicy: {
                        regions: [
                            {
                                id: "FRA1",
                                desiredReplicationCount: 1
                            },
                            {
                                id: "NYC1",
                                desiredReplicationCount: 2
                            }
                        ]
                    }
                });
                data.append("pinataOptions", pinataOptions);
                _this.process = "Uploading Image...";
                _this.processing = true;
                _this.isGenerating = true;
                await axios
                    .post(url, data, {
                        maxContentLength: "Infinity", //this is needed to prevent axios from erroring out with large files
                        headers: {
                            "Content-Type": `multipart/form-data; boundary=${data._boundary}`,
                            Authorization: `Bearer ${_this.j}`
                        }
                    })
                    .then(function(response) {
                        //_this.userPhoto = 'https://ipfs.io/ipfs/'+response.data.IpfsHash;
                        _this.icon =
                            "https://ipfs.io/ipfs/" + response.data.IpfsHash;
                    })
                    .catch(function(error) {
                        //handle error here
                    });
            }

            details.name = _this.name;
            details.symbol = _this.symbol;
            details.description = _this.description;
            details.icon = _this.icon;
            url = `https://api.pinata.cloud/pinning/pinJSONToIPFS`;
            _this.process = "Updating data...";
            _this.processing = true;
            await axios
                .post(url, details, {
                    headers: {
                        Authorization: `Bearer ${_this.j}`
                    }
                })
                .then(async function(response) {
                    var tx = await createCollection(
                        _this.type,
                        "https://ipfs.io/ipfs/" + response.data.IpfsHash,
                        !_this.pay_with_hps
                    );
                    _this.processing = true;
                    waitForTransaction(tx).then(data => {
                        if (data.status) {
                            _this.$parent.checkConnection();
                            $("#collectionModal").removeClass("d-block");
                            _this.processing = true;
                            _this.process = "Generate Collection";
                        } else {
                            alert("Try again");
                        }
                        _this.isGenerating = false;
                    });
                })
                .catch(function(error) {
                    if (error.code == 4001) {
                        alert("User rejected!");
                    }
                    _this.isGenerating = false;
                });
        },
        generateCollectionOld() {
            var formdata = new FormData();

            formdata.append("image", $("#collection-file").prop("files")[0]);
            formdata.append(
                "display_name",
                $(".collection-input[name='display_name']").val()
            );
            formdata.append(
                "symbol",
                $(".collection-input[name='symbol']").val()
            );
            formdata.append(
                "description",
                $(".collection-input[name='description']").val()
            );
            /*formdata.append(
                "short_url",
                $(".collection-input[name='short_url']").val()
            );
*/
            axios
                .post("/collection", formdata)
                .then(res => {
                    $(".collection-content")
                        .removeClass("fade-in-bottom")
                        .addClass("fade-out-bottom");
                    setTimeout(function() {
                        $("#collectionModal").removeClass("d-block");
                    }, 400);

                    $(".toast-message").text(res.data.message);
                    $("#create-collection").trigger("reset");
                    $("#collection-logo-form").attr(
                        "src",
                        this.asset_url + "images/avatar2.png"
                    );
                    setTimeout(function() {
                        launch_toast();
                    }, 500);
                    this.$parent.updateCollection(res.data.collections);
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>
