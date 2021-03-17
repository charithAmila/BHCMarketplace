<template>
	<div>
		<div id="reportModal" class="custom-modal d-none" data-report-type="nft" data-report-slug="">

		  <div class="modal-content report-content">
		    <div class="modal-head">
		      <h3>Why are you reporting</h3>
		      <div>
		      	<span class="close-report-modal">&times;</span>
		      </div>
		    </div>
		    <div class="modal-body">
		    	<label class="report-description">{{ context }}</label>

				<div class="form-section">
					<form autocomplete="off" id="reportForm" @submit.prevent="report">
						<div class="form-divide">
							<label class="input-label">Message</label>
							<input class="modal-input" type="text" name="report_description" placeholder="Tell us some details">
							<label v-if="errorMsg!=null" class="text-danger">{{ errorMsg }}</label>
						</div>
						<button class="form-submit" type="submit">Report</button>
						<button class="cancel-btn" type="button">Cancel</button>
					</form>
				</div>
			</div>
		  </div>
		  
		</div>
	</div>
</template>

<script>

import $ from 'jquery'

export default{
	props: [
		'singleNft',
	],
	data(){
		return{
			errorMsg: null,
		}
	},
	computed:{
		context: function(){
			if (this.type == 'nft') {
				return 'Describe why you think this item should be removed from marketplace'
			}
			return 'Describe why you think this user violates the rules of the site'
		},
	},
	methods: {
		report(){
			this.errorMsg = null
			$("[name='report_description']").removeClass('emptyVal')
			var formdata = new FormData()
	        formdata.append('type', $('#reportModal').attr('data-report-type'))
	        if ($('#reportModal').attr('data-report-type') == 'nft') {
	        	formdata.append('report_slug', this.singleNft.slug)
	        }
	        else {
	        	formdata.append('report_slug', $('#reportModal').attr('data-report-slug'))
	        }
	        formdata.append('description', $("[name='report_description']").val())


			axios.post('/report', formdata).then((res) => {
				$('.toast-message').text(res.data.message);
				$('#reportForm').trigger("reset");
	            setTimeout(function() {
                    launch_toast();
                }, 500);
				modalClose($('#reportModal'), $(".report-content"));
			})
			.catch((error) => {
				$("[name='report_description']").addClass('emptyVal')
				this.errorMsg = error.response.data.errors.description[0]
            })
		}
	}
}
</script>