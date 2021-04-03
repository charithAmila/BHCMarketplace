<template>
  <div>
    <div id="update-cover" class="custom-modal">
      <div class="modal-content cover-content">
        <div class="modal-head">
          <h3>Update cover</h3>
          <span class="close-cover-modal">&times;</span>
        </div>
        <div class="modal-body">
          <form id="upload-cover">
            <input type="hidden" name="_token" :value="csrf_token" />
            <label class="cover-text"
              >Upload new cover for your profile page. We recommend to upload
              images in 1440x260 resolution</label
            >
            <input
              type="file"
              id="user-cover"
              name="cover_photo"
              accept="image/x-png,image/gif,image/jpeg"
              @change="updateData($event)"
            />
            <button
              v-if="!signed"
              id="sign-data"
              class="user-photo-btn choose-image"
              @click.prevent="sign"
            >
              Sign to Change Cover
            </button>
            <input
              v-else
              class="user-photo-btn choose-image"
              type="button"
              v-model="process"
              onclick="document.getElementById('user-cover').click();"
            />
            <span
              id="update-cover-error"
              class="custom-error text-danger"
            ></span>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: [
    "asset_url",
    "csrf_token",
    "user_data",
    "userPhoto",
    "page",
    "user_address",
  ],
  data() {
    return {
      j: "",
      s: "",
      uploadedImage: "",
      sd: "",
      signed: false,
      processing: false,
      process: "Choose Image",
    };
  },
  methods: {
    uploadImage: async function () {},
    updateData: async function (evt) {
      const FormData = require("form-data");
      let data = new FormData();
      const _this = this;
      var details = {};
      var url;

      _this.processing = true;
      _this.process = "Loading...";
      _this.uploadedImage = _this.user_data.cover_photo;

      await _this.aqquireKeys();

      //we gather a local file for this example, but any valid readStream source will work here.
      if (evt.target.files.length > 0) {
        var url = `https://api.pinata.cloud/pinning/pinFileToIPFS`;
        data.append("file", evt.target.files[0]);

        //You'll need to make sure that the metadata is in the form of a JSON object that's been convered to a string
        //metadata is optional
        const metadata = JSON.stringify({
          name: _this.user_data.name,
          keyvalues: {
            proPic: _this.name,
          },
        });
        data.append("pinataMetadata", metadata);

        //pinataOptions are optional
        const pinataOptions = JSON.stringify({
          cidVersion: 0,
          customPinPolicy: {
            regions: [
              {
                id: "FRA1",
                desiredReplicationCount: 1,
              },
              {
                id: "NYC1",
                desiredReplicationCount: 2,
              },
            ],
          },
        });
        data.append("pinataOptions", pinataOptions);

        _this.process = "Uploading Image...";

        await axios
          .post(url, data, {
            maxContentLength: "Infinity", //this is needed to prevent axios from erroring out with large files
            headers: {
              "Content-Type": `multipart/form-data; boundary=${data._boundary}`,
              Authorization: `Bearer ${_this.j}`,
            },
          })
          .then(async function (response) {
            //_this.userPhoto = 'https://ipfs.io/ipfs/'+response.data.IpfsHash;
            _this.uploadedImage =
              "https://ipfs.io/ipfs/" + response.data.IpfsHash;
            details.name = _this.user_data.name;
            details.description = _this.user_data.bio;
            details.short_url = _this.user_data.short_url;
            details.dp = _this.user_data.display_photo;
            details.cover = _this.uploadedImage;
            url = `https://api.pinata.cloud/pinning/pinJSONToIPFS`;
            _this.process = "Updating data...";
            await axios
              .post(url, details, {
                headers: {
                  Authorization: `Bearer ${_this.j}`,
                },
              })
              .then(function (response) {
                var ad = window.ethereum.selectedAddress;
                var proData = {
                  address: ad,
                  ipfs_hash: response.data.IpfsHash,
                  sign: _this.s,
                  short_url:_this.user_data.short_url
                };
                console.log(response);
                axios
                  .patch(`/api/profile/${ad}`, proData)
                  .then(function (response) {
                    $(".cover-content")
                      .removeClass("fade-in-bottom")
                      .addClass("fade-out-bottom");
                    $("#update-cover").removeClass("d-block");

                    $(".toast-message").text("Updated");
                    //$('#update-preferences').trigger("reset");

                    launch_toast();

                    _this.$parent.getUserData();
                    if (_this.page == "profile") {
                      _this.$parent.customFunc();
                    }
                  })
                  .catch(function (error) {
                    console.log(error);
                  });
              })
              .catch(function (error) {
                console.log(error);
              });
          })
          .catch(function (error) {
            //handle error here
          });
      }
      _this.process = "Choose Image";
      _this.processing = false;
      _this.s = "";
      _this.signed = false;
    },
    aqquireKeys: async function () {
      const _this = this;
      await axios.get("/api/keygen").then((res) => {
        _this.j = res.data.JWT;
      });
    },
    sign: function () {
      const _this = this;
      const provider = new ethers.providers.Web3Provider(window.ethereum);
      const signer = provider.getSigner();
      signer.signMessage(`I agree to update my profile.`).then((data) => {
        console.log(data);
        _this.s = data;
        _this.signed = true;
      });
    },
  },
};
</script>