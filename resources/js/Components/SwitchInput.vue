<template>
  <label class="relative inline-flex items-center mb-4 cursor-pointer">
    <input
      name="swich"
      @input="$emit('update:modelValue', $event.target.checked)"
      type="checkbox"
      :checked="modelValue"
      :value="modelValue"
      class="sr-only peer"
    />
    <div
      :class="
        i18n_locale == 'ar'
          ? 'peer-checked:after:-translate-x-full'
          : 'peer-checked:after:translate-x-full'
      "
      class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"
    ></div>
    <span
      :class="
        twMerge(
          'capitalize ms-3 text-sm font-medium text-gray-900 dark:text-gray-300',
          classes
        )
      "
    >
      {{ $t(title) }}</span
    >
  </label>
</template>

<script setup>
import { twMerge } from "tailwind-merge";
defineProps({
  title: {
    type: String,
    default: "",
  },
  modelValue: Boolean,
  classes: String,
});
defineEmits(["update:modelValue"]);
</script>

<style></style>
