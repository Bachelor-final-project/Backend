<script setup>
import { onMounted, ref, defineProps } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
  maxLength: {
    type: Number,
    default: 255,  // default maxLength if not provided
  },
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
// const clearInput = () => {
//     console.log(model.value);
// }
</script>

<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700"
        v-model="model"
        ref="input"
        :maxlength="maxLength" 
    />
</template>
