<template>
    <div>
        <div>
            <profile-info-component
                :user_id="user_id"
                :asset_url="asset_url"
                :csrf_token="csrf_token"
                :user="user"
                :following="following"
            ></profile-info-component>
        </div>

        <profile-component
            :collectible_asset="collectible_asset"
            :show_collectible="show_collectible"
            :base_url="base_url"
            :asset_url="asset_url"
            :marketplace_url="marketplace_url"
            :user_id="user_id"
            :current_user="auth_id"
        ></profile-component>

        <update-cover-modal-component
            :csrf_token="csrf_token"
        ></update-cover-modal-component>

        <footer-component :base_url="base_url"></footer-component>
    </div>
</template>

<script>
import { getUserDetails, checkFollowing, tempUserData } from "./../../data";
import { toAddress, checkConnection } from "./../../etherFunc";
export default {
    props: [
        "asset_url",
        "base_url",
        "csrf_token",
        "collectible_asset",
        "show_collectible",
        "base_url",
        "asset_url",
        "marketplace_url",
        "user_id"
    ],
    data() {
        return {
            user: {
                name: "",
                bio: "",
                display_photo:
                    "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.pinterest.com%2Fshiling7821%2Fanimation-loading%2F&psig=AOvVaw3UWpxv3TcXs34HgfQcltDF&ust=1620537767952000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCPjRj-KrufACFQAAAAAdAAAAABAD",
                cover_photo:
                    "https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.pinterest.com%2Fshiling7821%2Fanimation-loading%2F&psig=AOvVaw3UWpxv3TcXs34HgfQcltDF&ust=1620537767952000&source=images&cd=vfe&ved=0CAIQjRxqFwoTCPjRj-KrufACFQAAAAAdAAAAABAD"
            },
            auth_id: "",
            following: true
        };
    },
    methods: {
        checkConnection: async function() {
            const _this = this;
            //var connectionInterval = setInterval(async function() {
            var acc = await checkConnection();
            if (acc) {
                _this.auth_id = toAddress(acc);
                //clearInterval(connectionInterval);
            } else {
                _this.auth_id = toAddress("");
            }
            //}, 300);
        }
    },
    async mounted() {
        await this.checkConnection();
        this.user = await getUserDetails(this.user_id);

        console.log(this.user);
        this.following = await checkFollowing(this.auth_id, this.user_id);
    }
};
</script>
