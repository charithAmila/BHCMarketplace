<template>
	<div class="notification d-none">
		<label class="notif-title">Notifications</label>
		<div class="notif" :class="notifications.length == 0 ? '':'d-none'">
			<img :src="asset_url+'images/logo2.png'">
			<label class="notif-content">No new notifications</label>
		</div>
		<div class="notif-list" :class="notifications.length > 0 ? '':'d-none'">
			<div v-for="notif in notifications" class="notif-item">
				<a :href="asset_url+'profile/'+notif.profile_link"><img class="notif-img" :src="notif.display_photo"></a>
				<label><a class="user-profile" :href="asset_url+'profile/'+notif.profile_link">{{ notif.profile_name }}</a> {{ notif.action }} <span class="notif-item">{{ notif.nft }}</span><small> {{ notif.transaction_time }} ago</small></label>
			</div>
		</div>
	</div>
</template>

<script>

export default{
	props: ['asset_url'],
	data() {
		return{
			notifications: [],
		}
	},
	methods: {
		getNotifications(){
			axios.get('/notifications').then((res) => {
				this.notifications = res.data.notifications
			}).catch((error) =>{
                console.log(error)
            })
		},
	},
	mounted(){
		this.getNotifications()
	}
}
</script>