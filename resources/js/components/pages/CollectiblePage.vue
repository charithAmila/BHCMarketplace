<template>
    <div>
        
        <div v-if="loaded && !notFound">
            <show-collectible-component
                :collectible="collectible"
                :onWishList="onWishList"
                :transactions="transactions"
                :is_liked="is_liked"
                :asset_url="asset_url"
                :auth_check="is_auth"
                :user_profile="user_profile"
                :base_url="base_url"
            ></show-collectible-component>

            <show-fullscreen-component
                :collectible="collectible"
                :asset_url="asset_url"
            ></show-fullscreen-component>
        </div>

        

        <div v-else>
            <div v-if="!notFound">
                <div class="loader-container">
                    <div class="actual-loader">
                        <img :src="asset_url + 'images/logo.png'" alt="">
                        <div class="loader2"></div>
                        <div class="loader"></div>
                    </div>
                </div>

            </div>
            <div v-else>
                <h2 class="text-center">NFT is not found under this owner!</h2>
                <h3 class="text-center">
                    May be bought by someone else or burned!
                </h3>
            </div>
        </div>
    </div>
</template>
<script>
import { getTokenData } from "./../../data";
import { checkConnection, getOwnersOf } from "./../../etherFunc";
export default {
    props: ["asset_url", "user_profile", "base_url", "contract", "owner", "id"],
    data() {
        return {
            is_liked: false,
            is_auth: false,
            onWishList: false,
            collectible: { creator: "dddd" },
            transactions: [],
            loaded: false,
            fullyLoaded: false,
            current_user: "",
            owners: [],
            notFound: false
        };
    },
    methods: {
        getCollectible: async function() {
            const _this = this;
            var data = await getTokenData(
                _this.contract,
                _this.owner,
                Number(_this.id)
            );

            return data;
        },

        authCheck: async function() {
            const _this = this;
            _this.current_user = await checkConnection();

            _this.current_user == _this.owner ? (_this.is_auth = true) : null;
        }
    },
    async mounted() {
        try {
            this.collectible = await this.getCollectible();
        } catch (e) {
            this.notFound = true;
        }
        this.loaded = true;

        await this.authCheck();
        this.fullyLoaded = true;
    }
};
</script>
