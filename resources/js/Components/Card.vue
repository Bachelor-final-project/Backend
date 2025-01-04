<template>
  <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <header v-if="title || sub_title">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ title }}
      </h2>

      <p class="text-sm text-gray-400 dark:text-gray-400">
        {{ sub_title }}
      </p>
    </header>

    <slot />
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  sub_title: {
    type: String,
    default: "",
  },
});
</script>

<style></style>
