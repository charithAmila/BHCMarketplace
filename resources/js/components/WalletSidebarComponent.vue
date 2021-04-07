<template>
  <div>
    <div>
      <a class="btn content-title" :href="url_previous" id="box"
        >Go back <i class="fas fa-angle-double-left"></i
      ></a>
    </div>
    <h1 class="content-title mb-0 pr-20 mobile-padding-top">
      Connect your wallet
    </h1>
    <p class="text-bold pr-30">
      Sign up with one of available wallet providers
    </p>

    <div class="row walletconnect">
      <div class="col-sm-12 col-md-6 wallet-link">
        <div id="wallet_item_connect" @click="connectMetamsk()">
          <img :src="asset_url + 'images/metamask.png'" />
          <h6 class="content-title">Metamask</h6>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 wallet-link">
        <div id="wallet_item_connect" @click="walletConnect()">
          <img :src="asset_url + 'images/walletconnect.png'" />
          <h6 class="content-title">WalletConnect</h6>
        </div>
      </div>
    </div>

    <h4 class="content-title mb-0 mtext-title">Non-custodial & Secure</h4>
    <p class="text-bold pl-40">
      We do not own your private keys and cannot access your funds without your
      confirmation.
    </p>
  </div>
</template>


<script>
export default {
  data() {
    return {
      selectedAccount: "",
    };
  },
  props: ["url_previous", "asset_url"],
  mounted() {},
  methods: {
    connectMetamsk: async function () {
      const _this = this;
      try {
        window.ethereum.send("eth_requestAccounts").then((data) => {
          _this.selectedAccount = data.result[0];
          window.location.href = _this.url_previous;
        });
      } catch (e) {
        alert("No Metamask Provider Found!");
      }
    },
    walletConnect() {},
  },
};
</script>
<style>
.walletconnect #wallet_item_connect {
  color: black !important;
  text-align: center;
  padding: 10px 0;
  border-radius: 10px;
  width: 100%;
  height: 100px;
  background: white;
  color: white;
  border: 1px solid white;
  box-shadow: 0 0 0 1px #ffc170, 0 0 0 6px white, 0 0 0 9px #ffc170;
}

.walletconnect #wallet_item_connect img {
  height: 50px;
  margin: auto;
  display: block;
}

.walletconnect #wallet_item_connect:hover {
  cursor: pointer;
}

.walletconnect #wallet_item_connect {
  color: black !important;
  text-align: center;
  padding: 25px 0;
  border-radius: 10px;
  width: 100%;
  height: 150px;
  background: white;
  border: 1px solid white;
  box-shadow: 0 0 0 1px #ffc170, 0 0 0 6px white, 0 0 0 9px #ffc170;
}

.walletconnect #wallet_item_connect img {
  height: 50px;
  margin: auto;
  display: block;
}
</style>