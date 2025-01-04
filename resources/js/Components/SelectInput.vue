<script setup>
import { onMounted, ref } from "vue";
import { twMerge } from "tailwind-merge";
const props = defineProps([
  "modelValue",
  "options",
  "classes",
  "item_name",
  "underline",
  "hide_choose",
]);

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute("autofocus")) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });

const main_class = props.underline
  ? "p-8 block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer"
  : "w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm";
</script>

<template>
  <select
    :class="twMerge(main_class, classes)"
    class="font-semibold"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
  >
    <!-- <option  class="text-center" disabled selected v-if="!hide_choose">{{ $t("Choose one") }}</option> -->
    <option :key="i" v-for="(option, i) in options || []" :value="option.id">
      {{ option.name || option[`name_${i18n_locale}`] }}
    </option>
  </select>
</template>
