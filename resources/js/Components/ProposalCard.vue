<template>
    <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
      <img
        :src="imageSrc"
        :alt="proposal.title"
        class="w-full h-48 object-cover"
      />
      <div class="p-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ proposal.title }}</h3>
        <p class="font-medium text-gray-900 dark:text-gray-100">{{$t('cost')}}: {{ proposal.cost }} {{ proposal['currency_name'] }}</p>
        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ proposal.body }}</p>
        <div>
          <InputLabel
            for="donationAmount"
            class="block text-sm font-medium text-gray-700 mb-1"
          >
            {{$t("Enter Donation Amount")}}
          </InputLabel>
          <TextInput
            id="donationAmount"
            type="number"
            v-model="localDonationAmount"
            placeholder="Enter amount"
            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300"
            @input="emitDonation"
          />
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';


const props = defineProps({
    proposal: {
        type: Object,
        required: true
    }
});
const imageSrc = ref(props.proposal.image || new URL(`@/assets/images/hero.jpg`, import.meta.url).href);
  
  const emit = defineEmits(['donate']);
  
  const localDonationAmount = ref('');
  
  const emitDonation = () => {
    
    if (localDonationAmount.value) {
      emit('donate', props.proposal.id, localDonationAmount.value, props.proposal.currency_id);
    } else {
      alert('Please enter a donation amount.');
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
  