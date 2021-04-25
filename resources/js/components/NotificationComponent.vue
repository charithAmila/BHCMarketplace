<template>
	<div class="notification d-none">
		<div class="row">
<label class="notif-title">Notifications</label>

<!--button type="button" class="btn btn-primary btn-sm">Small button</button-->
<button type="button" class="btn btn-secondary btn-sm" style="margin-left:20px" @click ="clearNotif()">Clear All</button>
		</div>

		<div class="notif" :class="notifications.length == 0 ? '':'d-none'">
			<img :src="notifications.length == 0 ? asset_url+'images/logo.png':asset_url+'images/logo2.png'">

			<label class="notif-content" >No new notifications</label>
		</div>
		<div class="notif-list" :class="notifications.length > 0 ? '':'d-none'">
			<div v-for="notif in notifications" :key="notif.id" class="notif-item">
				<div>

				 <a v-if="notif.type =='sell'|| notif.type =='bid'" :href="'/nft/'+ notif.contract+':'+notif.token_id+':'+notif.owner"><p>
					{{notif.message}}</p></a> </div>
				<!--a :href="asset_url+'profile/'+notif.profile_link"><img class="notif-img" :src="notif.display_photo"></a-->
				<!--label><a class="user-profile" :href="asset_url+'profile/'">{{ notif.messaage }}</a--><!--span class="notif-item">{{ notif.nft }}</span--><!--small> {{ notif.transaction_time }} ago</small--></label>
			</div>
		</div>
	</div>
</template>

<script>
import { toAddress, checkConnection } from "../etherFunc.js";
export default {
  props: ["asset_url"],
  data() {
    return {
      notifications: [],
      address: "",
    };
  },
  methods: {
    getNotifications() {
      let data = {};
      data.user_id = this.address;
      axios
        .post("/notifications", data)
        .then((res) => {
          this.notifications = res.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    clearNotif() {
      let data = {};
      data.user_id = this.address;
      axios.post("/deleteNotification", data, {}).then((res) => {
        console.log(res.data);
      });
      this.notifications = [];
    },
  },
  async mounted() {
    this.address = await checkConnection();
    this.getNotifications();
  },
};
</script>
