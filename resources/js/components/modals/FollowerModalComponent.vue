<template>
  <div>
    <div id="follower-modal" class="custom-modal">
      <div class="modal-content follower-content">
        <div class="modal-head">
          <h3>Followers</h3>
          <span class="close-follower-modal">&times;</span>
        </div>
        <div id="followingBody" class="modal-body">
          <div
            v-for="(follower, index) in followers"
            :key="index"
            class="row follower-list"
          >
            <div class="col-3 col-sm-3 col-md-3 col-lg-3">
              <a
                :href="asset_url + 'profile/' + follower.wallet"
                target="_blank"
              >
                <img :src="follower.cover_photo" alt="" />
              </a>
            </div>
            <div class="col-9 col-sm9 col-md-9 col-lg-9">
              <small> {{ follower.bio }}</small>
              <a
                target="_blank"
                class="user_name"
                :href="asset_url + 'profile/' + follower.wallet"
              >
                {{ follower.name }}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["followers", "asset_url"],
};
</script>