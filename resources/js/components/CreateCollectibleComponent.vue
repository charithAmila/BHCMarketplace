<template>
  <div>
    <div class="back-btn">
      <a class="btn" :href="create_route" id="box"
        >Go back <i class="fas fa-angle-double-left"></i
      ></a>
    </div>
    <div class="solo-create">
      <div class="row">
        <div class="col-12 col-md-8">
          <h4 class="content-title text-head">
            Create {{ capitalizeFirstLetter(type) }} Collectible
          </h4>
          <form
            id="create-collectible"
            :data-collectible-type="type"
            method="POST"
            :action="store_route"
          >
            <div class="form-group">
              <h6 class="content-title text-upload">{{ process }}</h6>
              <div class="fileArea">
                <div class="file-input">
                  <p id="sizeInvalid" class="text-danger d-none">
                    File too Big, please select a file less than 50mb
                  </p>
                  <p v-if="isError.invalid_file" class="text-danger">
                    Invalid file format
                  </p>
                  <p id="fileLabel">
                    GIF, PNG, JPEG, WEBP, MP4, or MP3. Max size 50mb
                  </p>
                  <input
                    type="file"
                    id="selectedFile"
                    name="nft"
                    class="to-check"
                    @change="addFile($event)"
                  />
                  <input
                    class="fileBtn"
                    type="button"
                    v-model="process"
                    onclick="document.getElementById('selectedFile').click();"
                  />
                </div>
                <progress
                  v-if="imgselectok"
                  max="100"
                  :value.prop="uploadPercentageimg"
                ></progress>
                <p v-if="isError.imgselectok" class="this-error text-danger">
                  {{ error_message }}
                </p>
                <span
                  id="nft-validation"
                  class="custom-error text-danger"
                ></span>
                <div class="file-output d-none">
                  <img src="" class="category-img-tag" alt="" />
                  <span class="close-btn"><i class="fa fa-times"></i></span>
                </div>
                <div class="file-output-video d-none">
                  <video id="video_here" controls autoplay loop muted>
                    <source src="mov_bbb.mp4" class="category-video-tag" />
                    Your browser does not support HTML5 video.
                  </video>

                  <span class="close-btn-video"
                    ><i class="fa fa-times"></i
                  ></span>
                </div>
              </div>
            </div>

            <h6 class="content-title text-content">Access once purchased</h6>
            <div class="row">
              <div class="col-9 col-md-10">
                <label class="smallDesc"
                  >Content below and media file will be accessible after
                  successfull transaction</label
                >
              </div>
              <div class="col-3 col-md-2">
                <label class="switch">
                  <input id="accessToggle" name="aop" type="checkbox" />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>

            <div class="form-group digital-link d-none">
              <div>
                <input
                  id="d-link"
                  class="inp"
                  type="text"
                  name="aop_link"
                  placeholder="Digital key, code to redeem or link to a file"
                />
              </div>
              <span
                id="aop_link-validation"
                class="custom-error text-danger"
              ></span>
              <small class="faded-text"
                >Tip: Markdown syntax is supported</small
              >
            </div>

            <h6 class="content-title text-content">Select Collection</h6>
            <p style="color: #f88130" v-if="loading_collections">
              Loading your collections please wait...
              <span><img src="/images/loading.gif" alt="" width="3%" /></span>
            </p>
            <div id="collection-group">
              <a href="javascript:void(0)" class="generateBtn generateBtn">
                <div class="outside">
                  <div class="inside">
                    <div class="inner-outside">
                      <div class="inner generate_collection">
                        <i class="fa fa-plus"></i>
                        <h6>Generate collection</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </a>

              <a
                v-for="(collection, index) in setCollections"
                :key="index"
                :id="collection.id"
                href="javascript:void(0)"
                class="g_select"
                :class="index == clicked_index ? 'active-btn' : 'inactive-btn'"
                @click="onClickCollection(collection.address, index)"
              >
                <div class="outside">
                  <div class="inside">
                    <div class="inner-outside">
                      <div class="inner">
                        <img
                          v-if="collection.default == 1"
                          class="collection-logo"
                          :src="collection.icon"
                          alt=""
                        />
                        <img
                          v-else
                          class="collection-logo"
                          :src="collection.icon"
                          alt=""
                        />
                        <h6>{{ collection.name }} {{ collection.symbol }}</h6>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <p v-if="isError.collection" class="this-error text-danger">
              Please select a collection
            </p>
            <!--
            <generate-collection-component
              :collections="setCollections"
              :asset_url="asset_url"
              @clicked="onClickCard"
            ></generate-collection-component>
-->
            <div class="form-group mt-8">
              <div>
                <h6 class="content-title text-content mb-0">Name</h6>
                <input
                  class="inp to-check"
                  type="text"
                  name="collectible_name"
                  placeholder="E.g 'Happiness Qualitees'"
                  v-model="name"
                />
              </div>
              <p v-if="isError.name" class="this-error text-danger">
                {{ error_message }}
              </p>
              <span
                id="name-validation"
                class="custom-error text-danger"
              ></span>
            </div>

            <div class="form-group">
              <h6 class="content-title text-content text-content mb-0">
                Description <span class="optional">(Optional)</span>
              </h6>
              <input
                class="inp"
                type="text"
                name="description"
                placeholder="E.g 'Every successfull transaction youll be able to claim this unique tees'"
                v-model="description"
              />
              <small class="faded-text">With preserve line-breaks</small>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="legend col-6 col-md-6 col-lg-6 col-xl-6">
                  <h6 class="content-title text-content text-content mb-0">
                    Category
                  </h6>
                  <div>
                    <select
                      class="form-control to-check"
                      v-model="category"
                      id="category-drop"
                      name="category_id"
                    >
                      <option value="">-- Please select category --</option>
                      <option
                        v-for="category in categories"
                        :key="category.id"
                        class="special"
                        :value="category.category"
                      >
                        {{ category.category }}
                      </option>
                    </select>
                  </div>
                  <p v-if="isError.category" class="this-error text-danger">
                    {{ error_message }}
                  </p>
                  <span
                    id="legend-validation"
                    class="custom-error text-danger"
                  ></span>
                </div>
                <div class="category col-6 col-md-6 col-lg-6 col-xl-6">
                  <h6 class="content-title text-content text-content mb-0">
                    Legend
                  </h6>
                  <div>
                    <select
                      class="form-control to-check"
                      v-model="legend"
                      id="legend-drop"
                      name="legend_id"
                    >
                      <option value="">-- Please select legend --</option>
                      <option
                        v-for="legend in legends"
                        :key="legend.id"
                        class="special"
                        :value="legend"
                      >
                        {{ legend.legend }}
                      </option>
                    </select>
                  </div>
                  <p v-if="isError.legend" class="this-error text-danger">
                    {{ error_message }}
                  </p>
                  <span
                    id="category-validation"
                    class="custom-error text-danger"
                  ></span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div
                  class="col-12"
                  :class="type == 'multiple' ? 'col-md-6' : 'col-md-12'"
                >
                  <h6 class="content-title text-content text-content mb-0">
                    Royalties
                  </h6>
                  <div>
                    <input
                      class="inp to-check"
                      type="number"
                      name="royalties"
                      placeholder="E.g '20%'"
                      v-model="royalties"
                    />
                  </div>
                  <p class="this-error text-danger"></p>
                  <span
                    id="royalties-validation"
                    class="custom-error text-danger"
                  ></span>
                  <small class="faded-text"
                    >Recommended 5%, 10%, 15%, 20%, 30%, 35%, 40%, 50%</small
                  >
                </div>
                <div v-if="type == 'multiple'" class="col-12 col-md-6">
                  <h6 class="content-title text-content text-content mb-0">
                    Number of copies
                  </h6>
                  <div>
                    <input
                      class="inp to-check"
                      type="number"
                      name="copies"
                      placeholder="E.g '10'"
                      v-model="copies"
                    />
                  </div>
                  <p v-if="isError.copies" class="this-error text-danger">
                    {{ error_message }}
                  </p>
                  <span
                    id="copies-validation"
                    class="custom-error text-danger"
                  ></span>
                  <small class="faded-text">Amount of tokens</small>
                </div>
              </div>
            </div>

            <div class="form-group">
              <h6 class="content-title text-content text-content mb-0">
                Properties <span class="optional">(Optional)</span>
              </h6>

              <div id="properties-field">
                <div class="row">
                  <div class="col-6 col-md-6">
                    <input
                      class="inp bold-placeholder provided-key input-head"
                      type="text"
                      name="key[]"
                      placeholder="Provided Key"
                      v-model="properties['key']"
                    />
                  </div>
                  <div class="col-6 col-md-6">
                    <input
                      class="inp bold-placeholder provided-value"
                      type="text"
                      name="value[]"
                      placeholder="Provided Value"
                      v-model="properties['value']"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-9 col-md-10">
                <h6 class="content-title text-content mb-0">Put on sale</h6>
              </div>
              <div class="col-3 col-md-2 pt-14">
                <label class="switch">
                  <input
                    id="putSale"
                    name="pos"
                    type="checkbox"
                    v-model="putOnSale"
                  />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>

            <div v-if="putOnSale" class="form-group row">
              <div class="col-9 col-md-10">
                <h6 class="content-title text-content mb-0">
                  Instant sale price
                </h6>
                <small
                  >Enter the price for which the item will be instantly
                  sold</small
                >
              </div>
              <div v-if="putOnSale" class="col-3 col-md-2 pt-14">
                <label class="switch">
                  <input
                    id="instantSale"
                    name="isp"
                    type="checkbox"
                    v-model="instantSale"
                  />
                  <span class="slider round"></span>
                </label>
              </div>
            </div>

            <div v-if="putOnSale" class="form-group row price-tag d-block">
              <div class="col-12 col-md-12">
                <input
                  class="inp sale-price"
                  type="number"
                  name="price"
                  placeholder="Enter price for 1 piece"
                  :disabled="!instantSale"
                  v-model="price"
                />
                <span id="BHC" class="link-url-end sale-price-btn"
                  >BNB <i class="fa fa-angle-down"></i
                ></span>
                <div class="sale-price-drop d-none">
                  <div class="drop-group">
                    <a
                      href="javascript:void(0)"
                      id="BNB"
                      @click="setSaleCurrency('BNB')"
                      class="currency-item"
                      >BNB</a
                    >
                    <i class="fa fa-check currency-check"></i>
                  </div>
                  <div class="drop-group">
                    <a
                      href="javascript:void(0)"
                      id="HPS"
                      @click="setSaleCurrency('HPS')"
                      class="currency-item"
                      >HPS</a
                    >
                    <i class="fa fa-check currency-check opacity-0"></i>
                  </div>
                </div>
              </div>
              <p v-if="isError.sale_price" class="this-error text-danger">
                {{ error_message }}
              </p>
              <span
                id="price-validation"
                class="custom-error text-danger pl-15"
              ></span>
            </div>

            <div class="form-group row">
              <div class="error-msg col-12 col-md-12" v-if="errors_have">
                <p class="text-danger sum-error">
                  Something wrong. Please fix the errors in fields above and try
                  again.
                </p>
              </div>
              <div class="col-6 col-md-6">
                <input
                  id="createCollectiblea"
                  class="submitBtn"
                  type="button"
                  name=""
                  value="Generate Item"
                  @click="generateModelPopup()"
                />
              </div>

              <div class="col-2 col-md-2"></div>
              <div class="col-4 col-md-4 p-0">
                <label class="boldFade">disregard update</label>
              </div>
            </div>
          </form>

          <div class="sampleLoad"></div>
        </div>

        <div class="col-md-4 d-none d-md-block">
          <div class="preview-pane">
            <h6 class="content-title">Preview</h6>
            <div class="preview">
              <div class="outside">
                <div class="inside">
                  <div class="inner-outside">
                    <div class="inner">
                      <div
                        v-if="uploadedImage == ''"
                        class="preview-desc d-block"
                      >
                        <small class="prevLabel"
                          >Preview of your new artworks or collectibles</small
                        >
                      </div>
                      <div
                        v-if="fileType == 'image' && uploadedImage != ''"
                        class="image-container d-block"
                      >
                        <img
                          v-bind:src="uploadedImage"
                          class="category-img-tag"
                          alt=""
                        />
                      </div>
                      <div
                        v-if="fileType == 'video' && uploadedImage != ''"
                        class="video-container d-block"
                      >
                        <video controls autoplay loop muted>
                          <source
                            v-bind:src="uploadedImage"
                            class="category-video-prev"
                          />
                          Your browser does not support HTML5 video.
                        </video>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <create-collection-modal-component
      :store_route="store_route"
      :asset_url="asset_url"
      :csrf_token="csrf_token"
      :type="colType"
    ></create-collection-modal-component>
    <div id="create-collectible-modal" class="custom-modal">
      <div class="modal-content follower-content">
        <div class="modal-head">
          <h4>Create Collectible</h4>
          <span class="close-collectible-modal">&times;</span>
        </div>
        <div id="followingBody" class="modal-body">
          <div class="row">
            <div class="col-md-12 create-cmodel-elements">
              <label>Pay With</label>
              <select
                class="form-controll"
                v-model="pay_with"
                @change="setPaywith()"
              >
                <option value="bnb">BNB</option>
                <option value="hps">HPS</option>
              </select>
            </div>

            <div class="col-md-12 create-cmodel-elements" v-if="pay_with_hps">
              <button type="button" class="submitBtn">Approve HPS</button>
            </div>
            <div class="col-md-12 create-cmodel-elements" v-if="pay_with_hps">
              <button type="button" class="submitBtn">
                Approving HPS...
                <span><img src="/images/loading.gif" alt="" width="7%" /></span>
              </button>
            </div>
            <div class="col-md-12 create-cmodel-elements">
              <button type="button" class="submitBtn">Mint Token</button>
            </div>
            <div class="col-md-12 create-cmodel-elements">
              <button type="button" class="submitBtn">
                Minting token...
                <span><img src="/images/loading.gif" alt="" width="7%" /></span>
              </button>
            </div>
            <div class="col-md-12 create-cmodel-elements" v-if="putOnSale">
              <button type="button" class="submitBtn">Sign</button>
            </div>
            <div class="col-md-12 create-cmodel-elements" v-if="putOnSale">
              <button type="button" class="submitBtn">
                Signing...
                <span><img src="/images/loading.gif" alt="" width="7%" /></span>
              </button>
            </div>
            <div class="col-md-12 create-cmodel-elements" v-if="putOnSale">
              <button type="button" class="submitBtn">Approve NFT</button>
            </div>
            <div class="col-md-12 create-cmodel-elements" v-if="putOnSale">
              <button type="button" class="submitBtn">
                Approving NFT...
                <span><img src="/images/loading.gif" alt="" width="7%" /></span>
              </button>
            </div>
            <div class="col-md-12 create-cmodel-elements" v-if="putOnSale">
              <button type="button" class="submitBtn">Puton Sale</button>
            </div>
            <div class="col-md-12 create-cmodel-elements" v-if="putOnSale">
              <button type="button" class="submitBtn">
                Publishing Sale...
                <span><img src="/images/loading.gif" alt="" width="7%" /></span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { tempCollectionData, getCollections, getTokensData } from "../data.js";

import {
  createASingle,
  selectedAddress,
  getSingles,
  createABatch,
  waitForTransaction,
  toAddress,
  checkConnection,
  approveNFT,
  approveTokens,
} from "./../etherFunc.js";
export default {
  props: [
    "collections",
    "categories",
    "legends",
    "type",
    "asset_url",
    "create_route",
    "store_route",
    "csrf_token",
  ],
  data() {
    return {
      isError: {
        name: false,
        file: false,
        description: false,
        category: false,
        legend: false,
        collection: false,
        sale_price: false,
        invalid_file: false,
        imgselectok: false,
        copies: false,
      },
      name: "",
      description: "",
      category: "",
      legend: "",
      royalties: 0,
      properties: {},
      putOnSale: true,
      instantSale: false,
      price: 0,
      myCollection: [],
      setCollections: [],
      signed: false,
      s: "",
      j: "",
      processing: false,
      process: "Upload File",
      fProcessing: false,
      fProcess: "Generate Item",
      uploadedImage: "",
      fileType: "image",
      selectedContract: "",
      copies: 0,
      imgselectok: false,
      uploadPercentageimg: 0,
      colType: this.type == "solo" ? 721 : 1155,
      error_message: "This field is required",
      sale_currency: "BNB",
      clicked_index: null,
      errors_have: false,
      loading_collections: false,
      pay_with: "bnb",
      pay_with_hps: false,
      isProcessing: true,
    };
  },
  methods: {
    setPaywith() {
      if (this.pay_with == "hps") {
        this.pay_with_hps = true;
      } else {
        this.pay_with_hps = false;
      }
    },
    generateModelPopup() {
      if (this.type == "solo") {
        if (
          !this.name ||
          !this.imgselectok ||
          !this.legend ||
          !this.category ||
          this.selectedContract == ""
        ) {
          if (!this.name) {
            this.isError.name = true;
          } else {
            this.isError.name = false;
          }
          if (!this.imgselectok) {
            this.isError.imgselectok = true;
          } else {
            this.isError.imgselectok = false;
          }
          if (!this.category) {
            this.isError.category = true;
          } else {
            this.isError.category = false;
          }
          if (!this.legend) {
            this.isError.legend = true;
          } else {
            this.isError.legend = false;
          }
          if (this.selectedContract == "") {
            this.isError.collection = true;
          } else {
            this.isError.collection = false;
          }
          this.errors_have = true;
        } else {
          this.errors_have = false;
          if (this.putOnSale) {
            if (this.instantSale && Number(this.price) == 0) {
              this.isError.sale_price = true;
              this.errors_have = true;
            } else {
              this.isError.imgselectok = false;
              this.isError.category = false;
              this.isError.legend = false;
              this.isError.collection = false;
              this.isError.sale_price = false;
              this.isError.name = false;
              this.errors_have = false;
              $("#create-collectible-modal").addClass("d-block");
            }
          } else {
            this.isError.imgselectok = false;
            this.isError.category = false;
            this.isError.legend = false;
            this.isError.collection = false;
            this.isError.sale_price = false;
            this.isError.name = false;
            this.errors_have = false;
            $("#create-collectible-modal").addClass("d-block");
          }
        }
      } else {
        if (
          !this.name ||
          !this.imgselectok ||
          !this.legend ||
          !this.category ||
          this.selectedContract == "" ||
          this.copies == 0
        ) {
          if (!this.name) {
            this.isError.name = true;
          } else {
            this.isError.name = false;
          }
          if (!this.imgselectok) {
            this.isError.imgselectok = true;
          } else {
            this.isError.imgselectok = false;
          }
          if (!this.category) {
            this.isError.category = true;
          } else {
            this.isError.category = false;
          }
          if (!this.legend) {
            this.isError.legend = true;
          } else {
            this.isError.legend = false;
          }
          if (this.selectedContract == "") {
            this.isError.collection = true;
          } else {
            this.isError.collection = false;
          }
          if (this.copies == 0) {
            this.isError.copies = true;
          } else {
            this.isError.copies = false;
          }
          this.errors_have = true;
        } else {
          this.errors_have = false;
          if (this.putOnSale) {
            if (this.instantSale && Number(this.price) == 0) {
              this.isError.sale_price = true;
              this.errors_have = true;
            } else {
              this.isError.imgselectok = false;
              this.isError.category = false;
              this.isError.legend = false;
              this.isError.collection = false;
              this.isError.sale_price = false;
              this.isError.name = false;
              this.isError.copies = false;
              this.errors_have = false;
              $("#create-collectible-modal").addClass("d-block");
            }
          } else {
            this.isError.imgselectok = false;
            this.isError.category = false;
            this.isError.legend = false;
            this.isError.collection = false;
            this.isError.sale_price = false;
            this.isError.name = false;
            this.isError.copies = false;
            this.errors_have = false;
            $("#create-collectible-modal").addClass("d-block");
          }
        }
      }
    },
    setSaleCurrency(currency) {
      this.sale_currency = currency;
    },
    onClickCollection(address, index) {
      this.selectedContract = address;
      this.clicked_index = index;
    },
    checkConnection: function () {
      const _this = this;
      var connectionInterval = setInterval(async function () {
        var acc = checkConnection();
        if (acc) {
          _this.current_user = toAddress(acc);
          clearInterval(connectionInterval);
          if (_this.type == "solo") {
            _this.loading_collections = true;
            _this.setCollections = await getCollections(721, acc, false);
            _this.loading_collections = false;
          } else if (_this.type == "multiple") {
            _this.loading_collections = true;
            _this.setCollections = await getCollections(1155, acc, false);
            _this.loading_collections = false;
          }
        }
      }, 300);
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    updateCollection(passedCollection) {
      this.setCollections = passedCollection;
    },
    async aqquireKeys() {
      const _this = this;
      await axios.get("/api/keygen").then((res) => {
        _this.j = res.data.JWT;
      });
    },
    async sign() {
      const _this = this;
      var sig = await signMessage(`I agree to update my profile.`);
      if (sig) {
        _this.s = sig;
        _this.signed = true;
      }
    },
    addFile: async function (evt) {
      const FormData = require("form-data");
      let data = new FormData();
      const _this = this;
      var url;

      _this.processing = true;
      _this.process = "Loading...";

      await _this.aqquireKeys();

      //we gather a local file for this example, but any valid readStream source will work here.
      if (evt.target.files.length > 0) {
        if (
          evt.target.files[0]["type"] === "image/jpeg" ||
          evt.target.files[0]["type"] === "image/jpg" ||
          evt.target.files[0]["type"] === "image/png" ||
          evt.target.files[0]["type"] === "image/gif" ||
          evt.target.files[0]["type"] === "image/webp" ||
          evt.target.files[0]["type"] === "video/mp4" ||
          evt.target.files[0]["type"] === "audio/mpeg"
        ) {
          this.isError.invalid_file = false;
          _this.fileType = evt.target.files[0].type.split("/")[0];
          var url = `https://api.pinata.cloud/pinning/pinFileToIPFS`;
          data.append("file", evt.target.files[0]);

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

          _this.process = "Uploading File...";

          await axios
            .post(url, data, {
              onUploadProgress: function (uploadEvent) {
                _this.imgselectok = true;
                _this.uploadPercentageimg = Math.round(
                  (uploadEvent.loaded / uploadEvent.total) * 100
                );
              },
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
            })
            .catch(function (error) {
              //handle error here
            });
          _this.process = "Uploaded";
          _this.processing = false;
        } else {
          this.isError.invalid_file = true;
          console.log(evt.target.files[0]["type"]);
          _this.process = "Upload";
        }
      } else {
        console.log(evt.target.files[0]["type"]);
      }
    },
    async createCollectible() {
      const _this = this;
      console.log(this.selectedContract);
      _this.fProcessing = true;
      _this.fProcess = "Generating Hash";
      var data = {
        creator: toAddress(window.ethereum.selectedAddress),
        name: _this.name,
        file: _this.uploadedImage,
        fileType: _this.fileType,
        description: _this.description,
        category: _this.category,
        legend: _this.legend.legend,
        royalties: _this.royalties,
        properties: _this.properties,
        instant_sale_price: _this.price,
        instant_sale_token: _this.token,
        count: 1,
        sale_currency: _this.sale_currency,
        icon: _this.legend.icon,
      };
      _this.type == "multiple" ? (data.count = _this.copies) : null;

      var url = `https://api.pinata.cloud/pinning/pinJSONToIPFS`;
      await axios
        .post(url, data, {
          headers: {
            Authorization: `Bearer ${_this.j}`,
          },
        })
        .then(function (response) {
          _this.fProcess = "Generating Token";
          _this.type == "solo"
            ? createASingle(
                "https://ipfs.io/ipfs/" + response.data.IpfsHash,
                _this.royalties,
                _this.selectedContract,
                !_this.pay_with_hps
              ).then((res) => {
                console.log(res);
                waitForTransaction(res.hash).then((data) => {
                  if (data) {
                    _this.fProcess = "Token minted";
                    _this.fProcessing = false;
                  }
                  window.location.href = `/profile/${toAddress(
                    window.ethereum.selectedAddress
                  )}`;
                });
              })
            : createABatch(
                "https://ipfs.io/ipfs/" + response.data.IpfsHash,
                _this.copies,
                _this.royalties,
                _this.selectedContract,
                !_this.pay_with_hps
              ).then((res) => {
                console.log(res);
                waitForTransaction(res.hash).then((data) => {
                  if (data) {
                    _this.fProcess = "Token minted";
                    _this.fProcessing = false;
                  }
                  window.location.href = `/profile/${toAddress(
                    window.ethereum.selectedAddress
                  )}`;
                });
              });
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    onClickCard(_selectedContract) {
      const _this = this;
      _this.selectedContract = _selectedContract;
    },
  },

  async mounted() {
    this.checkConnection();
  },
};
</script>
<style>
.create-cmodel-elements {
  margin-bottom: 5px;
}
.form-controll {
  display: block;
  width: 100%;
  border: 1px solid #f88130;
  border-radius: 3px;
}
</style>