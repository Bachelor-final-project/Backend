<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t, locale } = useI18n()

const props = defineProps({
  modelValue: [String, Number],
  options: {
    type: Array,
    default: () => []
  },
  item_name: String,
  classes: String
})

const emit = defineEmits(['update:modelValue'])

const getOptionText = (option) => {
  return (
    option.name ||
    option[props.item_name] ||
    option[`name_${locale.value}`] ||
    option[`type_${locale.value}`] ||
    'Unknown'
  )
}

const handleSelect = (optionId) => {
  emit('update:modelValue', optionId)
}
</script>

<template>
  <div class="flex gap-2 sm:gap-3 w-full">
    <button
      v-for="option in options"
      :key="option.id"
      type="button"
      @click="handleSelect(option.id)"
      :class="[
        'w-full px-3 py-2 sm:px-4 sm:py-2 text-sm font-medium rounded-lg border transition-all duration-200',
        'focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1',
        'hover:scale-105 active:scale-95',
        modelValue == option.id
          ? 'bg-blue-600 text-white border-blue-600 shadow-md'
          : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 hover:border-gray-400 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700'
      ]"
    >
      {{ getOptionText(option) }}
    </button>
  </div>
</template>