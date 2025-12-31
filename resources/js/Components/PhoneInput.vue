<template>
  <div class="flex rtl:flex-row-reverse" >
    <select 
      v-model="countryCode" 
      class="w-24 px-5 py-2 border border-r-0 rounded-l-lg  focus:ring focus:ring-indigo-300 bg-gray-50 "
      @change="updateValue"
    >
      <option class="text-right" dir="ltr" v-for="country in countries" :key="country.id" :value="country.calling_code" :selected="country.calling_code === countryCode">
        {{ "+" + country.calling_code }}
      </option>
    </select>
    <input
      v-model="phoneNumber"
      type="tel"
      :placeholder="placeholder"
      class="rounded-r-lg flex-1 px-4 py-2 border focus:ring focus:ring-indigo-300"
      @input="validateAndUpdate"
      minlength="7"
      maxlength="15"
      dir="ltr"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: String,
  countries: Array,
  defaultCountryCode: String,
  placeholder: {
    type: String,
    default: 'Enter phone number'
  },
  inputClass: String
})

const isRTL = document.dir === 'rtl' || document.documentElement.dir === 'rtl'

const emit = defineEmits(['update:modelValue'])

const countryCode = ref(props.defaultCountryCode || props.countries?.[0]?.calling_code || '+1')
const phoneNumber = ref('')

// Parse initial value
if (props.modelValue) {
  const match = props.modelValue.match(/^(\+\d{1,4})(.*)$/)
  if (match) {
    countryCode.value = match[1]
    phoneNumber.value = match[2]
  }
}

const validateAndUpdate = () => {
  phoneNumber.value =phoneNumber.value.replace(/[^\d]/g, '')
  updateValue()
}

const updateValue = () => {
  const fullNumber =  "00" + countryCode.value + phoneNumber.value
  emit('update:modelValue', fullNumber)
}

watch(() => props.modelValue, (newValue) => {
  if (newValue && newValue !== countryCode.value + phoneNumber.value) {
    const match = newValue.match(/^(\+\d{1,4})(.*)$/)
    if (match) {
      countryCode.value = match[1]
      phoneNumber.value = match[2]
    }
  }
})
</script>