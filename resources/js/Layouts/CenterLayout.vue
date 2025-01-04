<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { twMerge } from "tailwind-merge";
import { Link } from "@inertiajs/vue3";
defineProps({
  classes: String,
});
</script>

<template>
  <div
    class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900"
  >
    <div
      :class="twMerge('w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg',classes)"
    >
      <slot />
    </div>
  </div>
</template>
