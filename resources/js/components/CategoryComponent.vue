<template>
  <div>
    <select class="form-control to-check" id="category-drop" name="category_id">
      <option value="">-- Please select category --</option>
      <option
        v-for="category in categories"
        :key="category.id"
        class="special"
        :value="category.id"
        @click="onClick(category.category)"
      >
        {{ capitalizeFirstLetter(category.category) }}
      </option>
    </select>
  </div>
</template>


<script>
export default {
  props: ["categories"],
  methods: {
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    onClick(cat) {
      this.$emit("clicked", cat);
    },
  },
};
</script>