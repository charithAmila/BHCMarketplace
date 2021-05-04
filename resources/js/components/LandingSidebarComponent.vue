<template>
    <div>
        <WrongNetworkModalComponent
            v-if="wrongNet"
        ></WrongNetworkModalComponent>
        <div class="posBtn z-2">
            <a
                class="btn content-title"
                :href="!connected ? wallet_route : profile_route"
                id="connectWallet"
                ><i class="fas fa-angle-double-right"></i> {{ account }}</a
            >
        </div>
        <a :href="marketplace_route">
            <img class="sidebarLogo z-2" :src="asset_url + 'images/logo.png'" />
        </a>
        <div class="my-auto">
            <div class="imgSmall imgPos1">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/2_icon1.png'"
                />
            </div>
            <div class="imgLarge imgPos2">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/2_icon2.png'"
                />
            </div>
            <div class="imgNormal imgPos3">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/2_icon3.png'"
                />
            </div>
            <div class="imgNormal imgPos4">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/2_icon4.png'"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { toAddress, checkConnection } from "./../etherFunc";
import WrongNetworkModalComponent from "./../components/modals/WrongNetworkModalComponent";
export default {
    components: { WrongNetworkModalComponent },
    data() {
        return {
            selectedAddress: "",
            account: "Connect Wallet",
            connected: false,
            profile_route: "/profile/",
            account_check: null,
            wrongNet: false
        };
    },
    props: ["wallet_route", "marketplace_route", "asset_url"],
    async mounted() {
        await this.checkConnection();
        provider.getNetwork().then(data => {
            this.account_check = data.chainId;
        });
    },
    methods: {
        checkConnection: async function() {
            const _this = this;
            var acc = await checkConnection();
            if (acc && acc != toAddress("")) {
                _this.selectedAddress = acc;
                // _this.account = acc.substring(0, 10) + "...";
                var strshortfront = acc.slice(0, 6);
                var strshortend = acc.slice(-4);
                _this.account = strshortfront + "..." + strshortend;
                _this.connected = true;
                _this.profile_route = _this.profile_route + acc;
            }
            _this.wrongNet = window.wrongNetwork;
        }
    }
};
</script>
