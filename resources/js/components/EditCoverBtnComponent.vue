<template>
	<div>
		<img id="current-cover-photo" :src="base_url+'/'+user.cover_path+user.cover_photo">
		<a v-if="auth_id == user_id" href="javascript:void(0)" class="edit-cover d-none d-md-block">Edit Cover</a>
		<a v-if="auth_id == user_id" href="javascript:void(0)" class="edit-cover-mobile d-xs-block d-md-none">
			<i class="fa fa-cog"></i>
		</a>
	</div>
</template>

<script>
import {ethers} from 'ethers';
export default{
	props: [
		'user_id',
		//'auth_id',
		'base_url',
	],
	data(){
		return{
			user:{
				id:''
			},
			auth_id:'',
		}
	},
	methods:{
		checkConnection:function() {
			const _this =this;
			var connectionInterval = setInterval(function(){
				var acc = window.ethereum.selectedAddress;
				if(acc){
					//console.log(acc)
					_this.auth_id=ethers.utils.getAddress(acc);
					clearInterval(connectionInterval);
				}
			},300)
		}
	}, 
	mounted(){
		this.checkConnection();
	}
}
</script>