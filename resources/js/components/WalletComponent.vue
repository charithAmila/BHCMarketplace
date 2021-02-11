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
                    <div class="login-links">
                        <a href="">
                            <div class="row">
                                <div class="col-3 col-sm-3 col-md-3 col-lg-3">
                                    <img class="login_img" src="" />
                                </div>
                                <div class="col-9 col-sm-9 col-md-9 col-lg-9">
                                    <h6></h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="login-links create-user">
                        <a href=""> <i class="fa fa-plus"></i> Create User </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "Wallet",
    created() {
        console.log("test");
    }
};
</script>
