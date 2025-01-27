<script setup>
import { computed, ref } from 'vue';

const checkboxRef = ref();
const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        // emit('update:checked', val);
    },
});

function handleCheckedChages() {
    emit('update:checked', checkboxRef.value);
}
</script>

<template>
    <input
        ref="checkboxRef"
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
        @change="handleCheckedChages"
    />
</template>
