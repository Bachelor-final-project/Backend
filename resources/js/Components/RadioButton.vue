<template>
  <div :class="{ 'flex justify-start gap-6 flex-wrap': row }">
    <!-- '' -->
    <h1 :class="row ? label_classes : label_classes + ' mb-3'">
      {{ $t(label) }}
      <span v-if="required" class="text-red-700">*</span>
    </h1>
    <div class="flex gap-6">
      <div :key="i" v-for="(radio, i) in radios" class="flex items-center">
        <input
          :required="required"
          @input="$emit('update:modelValue', $event.target.value)"
          :checked="modelValue == radio.value"
          :id="`${radio.value}-${radio.label}-default-radio-1`"
          type="radio"
          :value="radio.value"
          :name="label"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
        />
        <label
          :for="`${radio.value}-${radio.label}-default-radio-1`"
          class="capitalize ms-2 text-sm text-gray-900 dark:text-gray-300"
          >{{ $t(radio.label) }}</label
        >
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  label: { type: String, default: "" },
  radios: { type: Array, default: [] },
  modelValue: { type: String, default: "" },
  row: { type: Boolean, default: false },
  no_dots: { type: Boolean, default: false },
  required: { type: Boolean, default: false },
  label_classes: { type: String, default: "" },
});
defineEmits(["update:modelValue"]);
</script>

<style>
</style>