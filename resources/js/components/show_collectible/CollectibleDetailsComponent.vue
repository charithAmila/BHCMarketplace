<template>
	<div>
		<div class="details-tab">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#home" class="tabLink">Details</a></li>
				<li><a href="#holder" class="tabLink">Holder</a></li>
				<li><a href="#past_transactions" class="tabLink">Past Transactions</a></li>
			</ul>

			<div class="tabGroup">
				<div id="home" class="tab-pane tab-active">

					<div class="row dtab">
						<div class="col-3 col-md-2">
							<div class="inlineDiv">
								<a :href="user_profile+'/'+creator.user_profile">
									<img class="br-50" :src="asset_url+creator.asset_url" width="50">
								</a>
								<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-9 col-md-10">
							<label class="position">Designer</label>
							<label class="positionHolder"><a :href="user_profile+'/'+creator.user_profile" >{{ creator.user_name }}</a></label>
						</div>
					</div>


					<div class="row dtab">
						<div class="col-3 col-md-2">
							<div class="inlineDiv">
								<a :href="user_profile+'/'+current_owner.user_profile">
									<img class="br-50" :src="asset_url+current_owner.asset_url" width="50">
								</a>
								<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-9 col-md-10">
							<label class="position">Holder</label>
							<label class="positionHolder"><a :href="user_profile+'/'+current_owner.user_profile">{{ current_owner.user_name }}</a></label>
						</div>
					</div>

					

					<div class="row dtab">
						<div class="col-3 col-md-2">
							<div class="inlineDiv">
								<img class="br-50" :src="asset_url+collection_image" width="50">
								<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-9 col-md-10">
							<label class="position">Collection</label>
							<label class="positionHolder">{{ collection }}</label>
						</div>
					</div>


				</div>


				<div id="holder" class="tab-pane">
					<div v-for="(owner,index) in owners" :key="index" class="row dtab">
						<div class="col-3 col-md-2">
							<div class="inlineDiv">
								<a :href="user_profile+'/'+owner.user_profile">
									<img class="br-50" :src="asset_url+owner.asset_url" width="50">
								</a>
								<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-9 col-md-10">
							<label class="position">Holder</label>
							<label class="positionHolder"><a :href="user_profile+'/'+owner.user_profile" >{{ owner.user_name }}</a></label>
						</div>
					</div>
				</div>


				<div id="past_transactions" class="tab-pane">
					<div v-for="(transac,index) in transactions" :key="index" class="row dtab">
						<div class="col-3 col-md-2">
							<div class="inlineDiv">
								<a :href="user_profile+'/'+transac.user_profile">
									<img class="br-50" :src="asset_url+transac.user_image" width="50">
								</a>
								<i class="fa fa-check-circle imgCheck" aria-hidden="true"></i>
							</div>
						</div>
						<div class="col-9 col-md-10">
							<label class="position">{{ transac.action }} <span class="positionHolder">{{ transac.price }}</span> {{ transac.sub_context }}{{ transac.time_ago}} by <a :href="user_profile+'/'+transac.user_profile"><span class="positionHolder">{{ transac.user_name }}</span></a></label>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</template>

<script>
	
export default {
	props: ['creator','current_owner','owners','transactions','user_profile','asset_url','collection', 'collection_image'],
	data () {
		return {

		}
	}
}

</script>