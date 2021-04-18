<template>
    <div>
        <img
            id="current-cover-photo"
            :src="base_url + '/' + user.cover_path + user.cover_photo"
        />
        <a
            v-if="auth_id == user_id"
            href="javascript:void(0)"
            class="edit-cover d-none d-md-block"
            >Edit Cover</a
        >
        <a
            v-if="auth_id == user_id"
            href="javascript:void(0)"
            class="edit-cover-mobile d-xs-block d-md-none"
        >
            <i class="fa fa-cog"></i>
        </a>
    </div>
</template>

<script>
import { ethers } from "ethers";
import { toAddress, checkConnection } from "./../etherFunc";
export default {
    props: [
        "user_id",
        //'auth_id',
        "base_url"
    ],
    data() {
        return {
            user: {
                id: ""
            },
            auth_id: ""
        };
    },
    methods: {
        checkConnection: async function() {
            const _this = this;
            var interval = setInterval(async function() {
                var acc = await checkConnection();
                _this.auth_id = acc;
                if (acc) {
                    clearInterval(interval);
                }
            }, 300);
        }
    },
    async mounted() {
        await this.checkConnection();
    }
};
</script>
