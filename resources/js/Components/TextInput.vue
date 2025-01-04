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
  }
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
        :maxlength="maxLength" 
    />
</template>
