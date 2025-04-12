<template>
  <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg flex flex-col justify-between">
    <div class="flex flex-col justify-between w-full">
      <!-- <div class="my-3 space-y-2">
        <span class="inline-block py-1 px-3 text-xs font-medium text-white bg-blue-600 rounded-full">
          {{ $t("unit price") }} : {{ proposal.one_unit_price }} {{proposal.currency_symbol}}
        </span>
        <span class="inline-block py-1 px-3 text-xs font-medium text-white bg-purple-500 rounded-full ">
          <f-icon icon="users" class="font-lg text-lg" />
          {{ $t("Beneficiaries") }} : {{ (proposal.expected_benificiaries_count) }}
        </span>
      </div> -->
      <img
        :src="imageSrc"
        :alt="proposal.title"
        class="w-full h-48 object-cover"
      />
      <div class="p-4">
        <h3 class="text-lg text-center font-bold text-gray-900 dark:text-gray-100">{{ proposal.title }}</h3>
        <!-- <p class="font-medium text-gray-900 dark:text-gray-100 py-2"><span class="font-bold ">{{$t('cost')}}: </span>{{ proposal.cost }} {{ proposal['currency_name'] }}</p> -->
        <p class="text-sm font-medium text-gray-900 dark:text-gray-100"><span class="font-bold ">{{$t('proposal details')}}: <br></span>{{ proposal.body }}</p>
        
        <div class="mt-5">
          <p class="font-medium text-gray-900 dark:text-gray-100 py-2"><span class="font-bold ">{{$t('min_documenting_amount')}}: </span>{{ proposal.min_documenting_amount }} {{ proposal['currency_name'] }}</p>
          <InputLabel
            :for="`donationAmount_${proposal.id}`"
            class="block text-sm font-medium text-gray-700 mb-1"
          >
            {{$t("Enter Donation Amount")}} ({{ proposal.currency_name }})
          </InputLabel>
          <TextInput
            :id="`donationAmount_${proposal.id}`"
            type="number"
            v-model="localDonationAmount"
            :placeholder="$t('Enter amount')"
            class="w-full mt-2 px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300"
            @input="emitDonation"
          />
        </div>
        <div class="p-4 mt-3 bg-white rounded-lg shadow-md dark:bg-gray-800">
  
      <!-- Header -->
      <div class="flex justify-between mb-2">
        <span class="text-blue-700 dark:text-white">{{ $t("Funding Progress") }}</span>
        <span class="text-sm font-medium text-blue-700 dark:text-white">
          {{ parseInt((proposal.paid_amount / proposal.cost) * 100) }}%
        </span>
      </div>

      <!-- Progress Bar -->
      <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
        <div 
          class="bg-blue-600 h-2.5 rounded-full transition-all" 
          :style="`width: ${(proposal.paid_amount / proposal.cost) * 100}%`">
        </div>
      </div>
<!-- Details with Badges -->
    <div class="mt-3 space-y-2">
        <span class="inline-block py-1 px-3 mx-1 text-xs font-medium text-white bg-blue-600 rounded-full">
          {{ $t("cost") }} : {{ proposal.cost }} {{proposal.currency_code}}
        </span>
        <span class="inline-block py-1 px-3 mx-1 text-xs font-medium text-white bg-green-400 rounded-full">
          {{ $t("paid_amount") }} : {{ proposal.paid_amount }} {{proposal.currency_code}}
        </span>
        <span class="inline-block py-1 px-3 mx-1 text-xs font-medium text-white bg-purple-500 rounded-full">
          {{ $t("remaining_amount") }} : {{ (proposal.remaining_amount) }} {{proposal.currency_code}}
        </span>
      </div>
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
    localDonationAmount.value = Math.round(localDonationAmount.value)
      emit('donate', props.proposal.id, localDonationAmount.value, props.proposal.currency_id, payonlineChecked.value, props.proposal.min_documenting_amount);
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
  