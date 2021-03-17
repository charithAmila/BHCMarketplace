<template>
	<div>
		<select class="form-control to-check" id="legend-drop" name="legend_id">
	    	<option value=""> -- Please select legend --</option>
	    	<option v-for="legend in legends" :key="legend.id" class="special" :value="legend.id">{{ capitalizeFirstLetter(legend.legend) }}</option>
	    </select>
	</div>
</template>


<script>

export default{
	props: ['legends'],
	methods: {
		capitalizeFirstLetter(string){
	    	return string.charAt(0).toUpperCase() + string.slice(1);
	    },
	}
}
</script>