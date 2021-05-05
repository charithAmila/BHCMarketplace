<template>
    <div id="loginModal" class="custom-modal d-none">
        <div class="modal-content login-content">
            <div class="modal-head">
                <h3>Login</h3>
                <div>
                    <span class="close-login-modal">&times;</span>
                </div>
            </div>
            <div class="modal-body">
                <div>
                    <div
                        class="login-links"
                        v-for="link in links"
                        :key="link.id"
                    >
                        <a :href="link.customLink">
                            <div class="row">
                                <div class="col-3 col-sm-3 col-md-3 col-lg-3">
                                    <img
                                        class="login_img"
                                        :src="laravel_asset + link.asset_url"
                                    />
                                </div>
                                <div class="col-9 col-sm-9 col-md-9 col-lg-9">
                                    <h6>{{ link.name }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="login-links create-user">
                        <a :href="create_user">
                            <i class="fa fa-plus"></i> Create User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["laravel_asset", "create_user"],
    data() {
        return {
            links: []
        };
    },
    methods: {
        getUser() {
            axios
                .get("/wallet/fetch")
                .then(res => {
                    this.links = res.data;
                })
                .catch(error => {
                    //removed//console.log(error)
                });
        }
    },
    mounted() {
        this.getUser();
    }
};
</script>
