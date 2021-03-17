<template>
	<div>
		<a class="btn" :href="previous_url" id="box">Go back <i class="fas fa-angle-double-left"></i></a>
	</div>
</template>

<script>
	
export default{
	props: [
		'previous_url'
	]
}
</script>