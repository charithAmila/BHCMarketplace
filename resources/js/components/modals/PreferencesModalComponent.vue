<template>
    <div>
        <div id="preferencesModal" class="custom-modal">
            <div class="modal-content preferences-content">
                <div class="modal-head">
                    <h3>Preferences</h3>
                    <span class="close-custom-modal">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-img col-5 col-md-5">
                            <img
                                class="user-logo"
                                :src="userPhoto"
                                alt="profile"
                            />
                        </div>
                        <div class="col-label col-7 col-md-7">
                            <label
                                >Allowed png, gif, jpg. 400x400
                                Recommended</label
                            >
                            <input
                                type="file"
                                id="user-photo"
                                name="display_photo"
                                class="user-check"
                                accept="image/x-png,image/gif,image/jpeg"
                            />
                            <input
                                class="user-photo-btn btn-setPicture"
                                type="button"
                                value="Set picture"
                                onclick="document.getElementById('user-photo').click();"
                            />
                            <p class="preferences-error text-danger"></p>
                            <span
                                id="display_photo-preferences"
                                class="custom-error text-danger"
                            ></span>
                        </div>
                    </div>

                    <div class="form-section">
                        <form
                            id="update-preferences"
                            method="POST"
                            @submit.prevent="updateData"
                        >
                            <input
                                type="hidden"
                                name="_token"
                                :value="csrf_token"
                            />
                            <div class="form-divide">
                                <label class="input-label">Display name</label>
                                <input
                                    id="name-profile"
                                    class="modal-input preferences-check"
                                    type="text"
                                    name="name"
                                    placeholder="E.g. John Doe"
                                    v-model="user_data.name"
                                />
                                <p class="preferences-error text-danger"></p>
                                <span
                                    id="name-preferences"
                                    class="custom-error text-danger"
                                ></span>
                            </div>
                            <div class="form-divide">
                                <label class="input-label">Description</label>
                                <input
                                    id="description-profile"
                                    class="modal-input"
                                    type="text"
                                    name="description"
                                    placeholder="Spread some words about yourself"
                                    v-model="user_data.bio"
                                />
                                <p class="preferences-error text-danger"></p>
                                <span
                                    id="description-preferences"
                                    class="custom-error text-danger"
                                ></span>
                                <label class="desc-url">URLs are allowed</label>
                            </div>
                            <div class="form-divide">
                                <label class="input-label">Short url</label>
                                <span class="link-url">billionhappiness.finance/</span>
                                <input
                                    id="short_url-profile"
                                    class="modal-input preferences-check with-link"
                                    type="text"
                                    name="short_url"
                                    placeholder="Enter short url"
                                    v-model="user_data.short_url"
                                    @input="nameCheck"
                                />
                                <pre>{{ url_previous }}</pre>
                                <p class="preferences-error text-danger"></p>
                                <div
                                    class="alert alert-danger"
                                    v-if="
                                        nameerror &&
                                            nameerror != null &&
                                            fields.short_url != user_data.short_url
                                    "
                                >
                                    Already exists
                                </div>
                                <div
                                    class="alert alert-success"
                                    v-if="!nameerror && nameerror != null"
                                >
                                    Available
                                </div>
                                <span
                                    id="short_url-preferences"
                                    class="custom-error text-danger"
                                ></span>
                            </div>
                            <div v-if="this.nameerror == false || this.nameerror==null">

                            <button
                                v-if="!signed"
                                id="sign-data"
                                class="form-submit"
                                @click.prevent="sign"
                            >
                                Sign Changes
                            </button>
                            <button
                                v-else
                                id="preferences-submit"
                                class="form-submit"
                                type="submit"
                                :disabled="processing"
                            >
                                {{ process }}
                            </button>

                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import { signMessage, checkConnection } from "./../../etherFunc";

export default {
    props: [
        "asset_url",
        "csrf_token",
        "user_data",
        "userPhoto",
        "page",
        "user_address"
    ],
    mounted() {
        axios.get("/api/shorturls").then(response => {
            this.shorturls = response.data;
           // this.url_previous = this.user_data.short_url;
            console.log(this.shorturls);
        });
    },

    data() {
        return {
            fields: {},
            shorturls: null,
            nameerror: null,
            userData: {},
            url_previous: "",
            j: "",
            s: "",
            pro_pic: "",
            name: "",
            description: "",
            short_url: "",
            uploadedImage: "",
            sd: "",
            signed: false,
            processing: false,
            process: "Update Preferences"
        };
    },
    methods: {
        updateData: async function() {
            const FormData = require("form-data");
            let data = new FormData();
            const _this = this;
            var details = {};
            var url;

            _this.processing = true;
            _this.process = "Loading...";
            _this.uploadedImage = _this.user_data.display_photo;

            await _this.aqquireKeys();

            //we gather a local file for this example, but any valid readStream source will work here.
            if ($("#user-photo").get(0).files.length > 0) {
                var url = `https://api.pinata.cloud/pinning/pinFileToIPFS`;
                data.append("file", $("#user-photo").prop("files")[0]);

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
                        _this.uploadedImage =
                            "https://ipfs.io/ipfs/" + response.data.IpfsHash;
                    })
                    .catch(function(error) {
                        //handle error here
                    });
            }

            details.name = $("#name-profile").val();
            details.description = $("#description-profile").val();
            details.short_url = $("#short_url-profile").val();
            details.dp = _this.uploadedImage;
            details.cover = _this.user_data.cover_photo;
            url = `https://api.pinata.cloud/pinning/pinJSONToIPFS`;
            _this.process = "Updating data...";
            await axios
                .post(url, details, {
                    headers: {
                        Authorization: `Bearer ${_this.j}`
                    }
                })
                .then(async function(response) {
                    var ad = await checkConnection();
                    var proData = {
                        address: ad,
                        ipfs_hash: response.data.IpfsHash,
                        sign: _this.s,
                        short_url: details.short_url
                    };
                    console.log(response);
                    axios
                        .patch(`/api/profile/${ad}`, proData)
                        .then(function(response) {
                            $(".preferences-content")
                                .removeClass("fade-in-bottom")
                                .addClass("fade-out-bottom");
                            setTimeout(function() {
                                $("#preferencesModal").removeClass("d-block");
                            }, 400);

                            $(".toast-message").text("Updated");
                            //$('#update-preferences').trigger("reset");
                            setTimeout(function() {
                                launch_toast();
                            }, 500);
                            window.location.reload();
                            _this.$parent.getUserData();
                            if (_this.page == "profile") {
                                _this.$parent.customFunc();
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                })
                .catch(function(error) {
                    console.log(error);
                });
            _this.process = "Update Preferences";
            _this.processing = false;
            _this.s = "";
            _this.signed = false;
        },
        async aqquireKeys() {
            const _this = this;
            //await axios.get("/api/keygen").then((res) => {
            //_this.j = res.data.JWT;
            //});
            _this.j =
                "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySW5mb3JtYXRpb24iOnsiaWQiOiJkM2VjNWQzNy01MmQ0LTRlYjMtYmEyNC1kNmRjYmY4YTY1NDMiLCJlbWFpbCI6ImJpbGxpb25oYXBwaW5lc3NAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsInBpbl9wb2xpY3kiOnsicmVnaW9ucyI6W3siaWQiOiJOWUMxIiwiZGVzaXJlZFJlcGxpY2F0aW9uQ291bnQiOjF9XSwidmVyc2lvbiI6MX0sIm1mYV9lbmFibGVkIjpmYWxzZX0sImF1dGhlbnRpY2F0aW9uVHlwZSI6InNjb3BlZEtleSIsInNjb3BlZEtleUtleSI6IjQyZmNmMDViN2ZlZGFmNjBiMzNlIiwic2NvcGVkS2V5U2VjcmV0IjoiMGJlZTgwY2Q2OWY3MWVkMWZjZjViNTcxZjcyMjU3M2QwYTEzMzlhZjg4OWYzYjUyYTYwY2RlOGUxOTI1OTRmNSIsImlhdCI6MTYxODY2MjEyN30.8FlNm4h2vEW7TrTO5_xQf_GXQ2SkgXGJ1cepl4kkBrE";
        },
        async sign() {
            const _this = this;
            var sig = await signMessage(`I agree to update my profile.`);
            if (sig) {
                _this.s = sig;
                _this.signed = true;
            }
        },

        nameCheck() {
            let shorturl = $("#short_url-profile").val();
            if (shorturl.length == 0) {
                this.nameerror = null;
            }

            this.nameerror = false;
            this.shorturls.forEach(i => {
                if (i == shorturl && shorturl != this.url_previous) {
                    this.nameerror = true;
                }
            });
        }
    }
};
</script>
