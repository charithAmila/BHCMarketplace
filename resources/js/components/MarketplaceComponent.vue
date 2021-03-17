<template>
	<div>
		
		<div class="item-filter">
			<div>
				<button data-filter-id="all" class="filter-btn active" @click="filterNft('all')">All</button>
				<button data-filter-id="1" class="filter-btn" @click="filterNft(1)">Arts</button>
				<button data-filter-id="2"class="filter-btn" @click="filterNft(2)">Memes</button>
				
			</div>
			<div class="sortDiv">
				<a class="sorter sortDesk" href="javascript:void(0)">
					<img class="imgFilter" :src="asset_url+'/images/filter.png'" width="25"> Sort & Filter
				</a>

				<a class="sorter d-sm-block d-md-none" href="javascript:void(0)">
					<img class="imgFilter" :src="asset_url+'/images/filter.png'" width="25">
				</a>

				<div class="sortfilter d-none">
					<label class="sortLabel">Sort by</label>
					<ul>
						<li>
							<a href="javascript:void(0)" class="sortItem" data-sort-by="updated_at" data-order="desc" @click="sortNft('updated_at', 'desc')">
								<div class="row">
									<div class="col-md-9">Recently added</div>
									<div class="col-md-3 checkLabel">
										<i class="fa fa-check"></i>
									</div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="sortItem" data-sort-by="price" data-order="asc" @click="sortNft('price', 'asc')">
								<div class="row">
									<div class="col-md-9">Cheapest</div>
									<div class="col-md-3 checkLabel">
										<i class="fa fa-check"></i>
									</div>
								</div>
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" class="sortItem" data-sort-by="price" data-order="desc" @click="sortNft('price', 'desc')">
								<div class="row">
									<div class="col-md-9">Highest price</div>
									<div class="col-md-3 checkLabel">
										<i class="fa fa-check"></i>
									</div>
								</div>
							</a>
						</li>
					</ul>
					<label class="sortLabel">Options</label>
					<ul>
						<li>
							<a href="javascript:void(0)">
								<div class="row">
									<div class="col-md-9">
										Verified only
									</div>
									<div class="col-md-3">
										<div class="custom-control custom-switch">
										  <input type="checkbox" class="custom-control-input" id="customSwitch1" checked>
										  <label class="custom-control-label" for="customSwitch1"></label>
										</div>
									</div>
								</div>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="items">
			<div id="preloader" class="row d-none">
				<div v-for="index in 24" :key="index" class="col-md-3 col-lg-3 custom-column-xl main-dashboard">
					<div class="outside-nft border-on-profile">
					  <div class="inside-nft">
					  	<div class="inner-outside-nft">
					  		<div class="inner-nft">
					  			<div class="item-main">

									<div class="item-head">
										<div class="preloader-img"></div>
	        						</div>
								</div>

								<div class="item-img">
								</div>

								<div class="display-flex -mt-15">
									<div class="preloader-content"></div>
								</div>
								<div class="text-center currency-label"><div class="preloader-content"></div></div>
								<div class="preloader-half"><div class="preloader-content"></div></div>
					  		</div>
					  	</div>
					  </div>
					</div>
				</div>
			</div>

			
			<collectible-component 
				:div_id="div_id"
				:collectible_asset="collectible_asset"
				:show_collectible="show_collectible"
				:current_user="current_user"
				:base_url="base_url"
				:collectibles="collectibles"
				:page="'marketplace'"
			></collectible-component>
				
		</div>


	</div>
</template>

<script>

import Collectible from './CollectibleComponent.vue';

export default{
	components: {
		Collectible,
	},
	props: [ 
		'collectible_asset',
		'show_collectible',
		'current_user',
		'base_url',
		'asset_url'
	],
	data () {
		return {
			collectibles: [],
			category: 'all',
			sortBy: 'updated_at',
			order: 'desc',
			div_id: 'nft-container',
			notifications: [],
		}
	},
	methods: {
		getCollectible(){
            axios.get('/nft/fetchCollectibles').then((res) =>{
                this.collectibles = res.data
            }).catch((error) =>{
                console.log(error)
            })
	    },
	    filterNft(clickedCategory){
	    	this.category = clickedCategory
	    	this.fetchFilterNft()
	    },
	    sortNft(column, columnOrder){
	    	this.sortBy = column
	    	this.order = columnOrder
	    	this.fetchFilterNft()
	    },
	    fetchFilterNft(){
	    	axios.get("/nft/filter/"+this.category+'/'+this.sortBy+'/'+this.order)
	    	.then((res) => {
				this.collectibles = res.data.collectibles
			})
			.catch((error) => {
                console.log(error)
            })
	    },
	},
	mounted() {
		this.getCollectible()
    }
}

</script>
