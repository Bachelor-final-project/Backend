<script setup>
import { onMounted, ref, computed } from 'vue'
import { twMerge } from 'tailwind-merge'
import { ModelSelect } from 'vue-search-select'
import { useI18n } from 'vue-i18n'
import 'vue-search-select/dist/VueSearchSelect.css'

const { t, locale } = useI18n()

const props = defineProps({
  modelValue: [String, Number],
  options: {
    type: Array,
    default: () => []
  },
  classes: String,
  item_name: String,
  underline: Boolean,
  hide_choose: Boolean,
  searchable: Boolean,
  placeholder: {
    type: String,
    default: 'Choose an option...'
  }
})

const emit = defineEmits(['update:modelValue'])

const selectedValue = ref(props.modelValue)
const input = ref(null)

const formattedOptions = computed(() =>
  props.options.map((option) => ({
    value: option.id,
    text: getOptionText(option)
  }))
)

const getOptionText = (option) => {
  return (
    option.name ||
    option[props.item_name] ||
    option[`name_${locale.value}`] ||
    option[`type_${locale.value}`] ||
    'Unknown'
  )
}

const baseClasses = computed(() => {
  const common = 'transition-all duration-200 ease-in-out font-medium'
  
  if (props.underline) {
    return `${common} block w-full px-0 py-3 text-sm text-gray-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:ring-0 focus:border-blue-500 dark:focus:border-blue-400`
  }
  
  return `${common} w-full px-4 py-3 text-sm border border-gray-300 rounded-lg bg-white text-gray-700 shadow-sm hover:border-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:border-gray-500 dark:focus:border-blue-400 dark:focus:ring-blue-800`
})

const handleUpdate = (value) => {
  selectedValue.value = value
  emit('update:modelValue', value)
}

onMounted(() => {
  if (input.value?.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ 
  focus: () => input.value?.focus() 
})
</script>

<template>
  <div class="relative">
    <!-- Searchable Select -->
    <ModelSelect
      v-if="searchable"
      v-model="selectedValue"
      :options="formattedOptions"
      :class="twMerge(baseClasses, classes)"
      :placeholder="t(placeholder)"
      @update:modelValue="handleUpdate"
      class="select-input-searchable"
      style="z-index: 9999;"
    />
    
    <!-- Regular Select -->
    <select
      v-else
      ref="input"
      :value="modelValue"
      :class="twMerge(baseClasses, classes)"
      @input="handleUpdate($event.target.value)"
      class="select-input-regular"
    >
      <option 
        v-if="!hide_choose" 
        value="" 
        disabled 
        class="text-gray-400 bg-gray-50 dark:bg-gray-700 font-medium italic"
      >
        {{ t(placeholder) }}
      </option>
      
      <option 
        v-for="option in options" 
        :key="option.id" 
        :value="option.id"
        class="py-3 px-4 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-150 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
      >
        {{ getOptionText(option) }}
      </option>
    </select>
    
    <!-- Custom dropdown icon for regular select -->
    <div 
      v-if="!searchable" 
      class="absolute inset-y-0 ltr:right-0 rtl:left-0 flex items-center ltr:pr-3 rtl:pl-3 pointer-events-none"
    >
      <svg 
        class="w-5 h-5 text-gray-400" 
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M19 9l-7 7-7-7"
        />
      </svg>
    </div>
  </div>
</template>

<style scoped>
.select-input-regular {
  appearance: none;
  background-image: none;
}

.select-input-regular:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.select-input-searchable {
  min-height: 48px;
  position: relative;
  z-index: 1000;
}

:global(.vue-search-select .dropdown) {
  z-index: 99999 !important;
  position: fixed !important;
}

:global(.vue-search-select .dropdown .menu) {
  z-index: 99999 !important;
  position: fixed !important;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

:global(.vue-search-select .dropdown-menu) {
  z-index: 99999 !important;
  position: fixed !important;
}

/* Enhanced options styling */
.select-input-regular option {
  padding: 12px 16px;
  font-weight: 500;
  line-height: 1.5;
}

.select-input-regular option:hover {
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.select-input-regular option:checked {
  background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
  /* color: white; */
  font-weight: 600;
}

/* Custom dropdown styling */
.select-input-regular {
  background-position: right 12px center;
  background-size: 16px;
}

/* LTR layout */
.select-input-regular:not([dir="rtl"]) {
  padding-right: 40px;
}

/* RTL layout for Arabic */
.select-input-regular[dir="rtl"],
[dir="rtl"] .select-input-regular {
  background-position: left 12px center;
  padding-left: 40px;
  padding-right: 16px;
}

/* Improved focus ring */
.select-input-regular:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Hover effects */
.select-input-regular:hover,
.select-input-searchable:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Focus state enhancement */
.select-input-regular:focus,
.select-input-searchable:focus {
  transform: translateY(-1px);
}

/* Dark mode enhancements */
@media (prefers-color-scheme: dark) {
  .select-input-regular:hover,
  .select-input-searchable:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }
  
  .select-input-regular option:hover {
    background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
  }
  
  .select-input-regular option:checked {
    background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
  }
}
</style>