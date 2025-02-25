<script setup>
import { onMounted, ref, watch } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

// Define a regex for validating phone numbers
const phoneRegex = /^\+?[0-9]{0,15}$/;

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

// Watch the model and validate input
watch(model, (newValue, oldValue) => {
    model.value = newValue.replace(/(^\+)/g, "00");
    if (!phoneRegex.test(newValue)) {
        // Revert to the previous value if input is invalid
        model.value = oldValue;
    }
});
</script>

<template>
    <input
        type="tel"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
    />
</template>
