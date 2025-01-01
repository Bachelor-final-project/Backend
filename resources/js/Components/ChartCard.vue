<template>
  <div class=" my-5 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
    <section >
      <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ title }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ sub_title }}
        </p>
      </header>
      <slot />
    </section>
  </div>
</template>

<script setup>
defineProps({
  title: { type: String, default: "" },
  sub_title: { type: String, default: "" },
});
</script>

<style>
</style>