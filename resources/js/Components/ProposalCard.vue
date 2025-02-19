<template>
  <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col justify-between">
    <div class="flex flex-col justify-end ">
      <img
        :src="imageSrc"
        :alt="proposal.title"
        class="w-full h-48 object-cover"
      />
      <div class="p-4">
        <h3 class="text-lg text-center font-bold text-gray-900 dark:text-gray-100">{{ proposal.title }}</h3>
        <p class="font-medium text-gray-900 dark:text-gray-100 py-2"><span class="font-bold">{{$t('cost')}}: </span>{{ proposal.cost }} {{ proposal['currency_name'] }}</p>
        <p class="font-medium text-gray-900 dark:text-gray-100 py-2"><span class="font-bold">{{$t('min_documenting_amount')}}: </span>{{ proposal.min_documenting_amount }} {{ proposal['currency_name'] }}</p>
        <p class="text-sm font-medium text-gray-900 dark:text-gray-100"><span class="font-bold">{{$t('proposal details')}}: <br></span>{{ proposal.body }}</p>
        <div class="mt-10">
          <InputLabel
            for="donationAmount"
            class="block text-sm font-medium text-gray-700 mb-1"
          >
            {{$t("Enter Donation Amount")}} ({{ proposal.currency_name }})
          </InputLabel>
          <TextInput
            id="donationAmount"
            type="number"
            v-model="localDonationAmount"
            :placeholder="$t('Enter amount')"
            class="w-full mt-2 px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300"
            @input="emitDonation"
          />
        </div>
      </div>
    </div>
    <div v-if="proposal.isPayableOnline" class="flex flex-row justify-between">
      <div class="text-gray-500 text-xs">{{ $t("pay online") }}</div>
      <Checkbox :value="false" v-model="payonlineChecked" @update:checked="emitDonation"></Checkbox>
    </div>

  </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Checkbox from './Checkbox.vue';


const props = defineProps({
    proposal: {
        type: Object,
        required: true
    }
});
const imageSrc = ref(props.proposal.cover_image || new URL(`@/assets/images/hero.jpg`, import.meta.url).href);
  
  const emit = defineEmits(['donate']);
  
  const localDonationAmount = ref('');
  const payonlineChecked = ref(false);
  
  const emitDonation = () => {
    if (localDonationAmount.value) {
      emit('donate', props.proposal.id, localDonationAmount.value, props.proposal.currency_id, payonlineChecked.value, props.proposal.min_documenting_amount);
    } else {
      
    }
  };
  </script>
  
  <style scoped>
  img {
    transition: transform 0.3s ease;
  }
  
  img:hover {
    transform: scale(1.05);
  }
  </style>
  