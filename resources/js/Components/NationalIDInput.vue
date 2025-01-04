<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import { isValidPalestinianID } from '../utils/validators';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

const NIdRegex = /^[0-9]{0,9}$/;

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

// function isValidPalestinianID(id) {
//     // console.log("Validation Ran");
//   return /\d{9}/.test(id) && Array.from(id, Number).reduce((counter, digit, i) => {
//         const step = digit * ((i % 2) + 1);
//         return counter + (step > 9 ? step - 9 : step);
//     }) % 10 === 0;
// }

let isValid = true;

watch(model, (newValue, oldValue) => {
    isValid = isValidPalestinianID(newValue + "");
    if (!NIdRegex.test(newValue)) {
        // Revert to the previous value if input is invalid
        model.value = oldValue;
        return;
    }
    
});

</script>
<template>
    <input
        type="tel"
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
        :class="{'border-red-500': !isValid, 'focus:border-red-500': !isValid, 'border-2' : !isValid}"
    />
</template>
