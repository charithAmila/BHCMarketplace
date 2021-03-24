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
        <div id="wallet_item" @click="connectMetamsk()">
          <img :src="asset_url + 'images/metamask.png'" />
          <h6 class="content-title">Metamask</h6>
        </div>
      </div>
      <div class="col-sm-12 col-md-6 wallet-link">
        <div id="wallet_item">
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
  mounted() {
    //this.checkConnection();
  },
  methods: {
    connectMetamsk: async function () {
      const _this = this;
      window.ethereum.send("eth_requestAccounts").then((data) => {
        _this.selectedAccount = data.result[0];
        window.location.href = _this.url_previous;
      });
    },
  },
};
</script>