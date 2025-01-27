<template>
  <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
    <div class="container mx-auto px-4">
      <h1 class="text-4xl font-bold text-center text-gray-800 mb-10 text-gray-900 dark:text-gray-100">{{ $t(entity.name) }}</h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <ProposalCard
          v-for="proposal in proposals"
          :key="proposal.id"
          :proposal="proposal"
          @donate="handleDonation"
        />
      </div>

      <div class="mt-10">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $t('Your Information') }}</h2>
        <form @submit.prevent="submitDonations" class="space-y-4">
          <div>
            <InputLabel for="name" value="name" />
            <TextInput
              id="name"
              type="text"
              v-model="form.name"
              class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300"
              required
            />
            <InputError :message="form.errors.name" class="mt-2" />

          </div>
          <div>
            <InputLabel for="phone" value="phone" />
            <TextInput
              id="phone"
              type="tel"
              v-model="form.phone"
              placeholder="Enter your phone number"
              class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300"
              required
            />
            <InputError :message="form.errors.phone" class="mt-2" />
          </div>

          <div>
          <InputLabel for="gender" value="Gender" />
          <SelectInput
            :options="genders"
            :item_name="`name_${i18n_locale}`"
            id="currency_id"
            v-model="form.gender"
            class="mt-1 block w-full"
            autocomplete="new-password"
            required
          />
          <InputError :message="form.errors.gender" class="mt-2" />
        </div>
        <div>
          <InputLabel for="country_id" value="Country" />
          <SelectInput
            :options="countries"
            :item_name="`name_${i18n_locale}`"
            id="country_id"
            v-model="form.country_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            
          />
          <InputError :message="form.errors.country_id" class="mt-2" />
        </div>


          <div class="flex items-center gap-4">
          <!-- <SecondaryButton v-if="show_payonline_button" :disabled="form.processing" v-on:click="1+1">{{
            $t("Pay the selected online")
          }}</SecondaryButton> -->
          <PrimaryButton :disabled="form.processing">{{
            $t("Save")
          }}</PrimaryButton>

          <Transition
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
            class="transition ease-in-out"
          >
            <p
              v-if="form.recentlySuccessful"
              class="text-sm text-gray-600 dark:text-gray-400"
            >
              Saved.
            </p>
          </Transition>
        </div>
        </form>
      </div>
    </div>
  </div>
</template>
  <script setup>
  import { router } from "@inertiajs/vue3";
  import { Head } from "@inertiajs/vue3";
  import { useForm } from "@inertiajs/vue3";
  import Card from "@/Components/Card.vue";
  import Table from "@/Components/Table.vue";
  import PrimaryButton from "@/Components/PrimaryButton.vue";
  import SecondaryButton from "@/Components/SecondaryButton.vue";
  import DonatingPageLayout from '@/Layouts/DonatingPageLayout.vue';
  import ProposalCard from '@/Components/ProposalCard.vue';
  import InputError from "@/Components/InputError.vue";
  import TextInput from "@/Components/TextInput.vue";
  import InputLabel from "@/Components/InputLabel.vue";
  import SelectInput from "@/Components/SelectInput.vue";

  defineOptions({ layout: DonatingPageLayout });


  
  const props = defineProps({
    entity: Array,
    proposals: Array,
    countries: Array,
    genders: Array,
    show_payonline_button: Boolean,
  });
  const form = useForm({
    name: '',
    phone: '',
    country: '',
    gender: '',
    donations: [] 
  });
  const handleDonation = (proposalId, amount,currency_id, pay_online) => {
    const existingDonation = form.donations.find(d => d.proposal_id === proposalId);
    if (existingDonation) {
      existingDonation.amount = amount;
      existingDonation.pay_online = pay_online;
    } else {
      form.donations.push({ proposal_id: proposalId, amount: amount, currency_id: currency_id, pay_online: pay_online });
    }
  };

function saveWithPayOnline() {
  form.payOnline = true
}
  
  const submitDonations = () => {
  // form
  // .transform((data) => ({
  //   ...data,
  //   remember: data.remember ? 'on' : '',
  // }))
  form.post(route("donor.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};


//   const submitDonations = async () => {
//   try {
//     const payload = {
//       name: form.value.name,
//       phone: form.value.phone,
//       country: form.value.country,
//       donations: form.value.donations
//     };

//     console.log('Payload:', payload);

//     // Replace with your API endpoint
//     const response = await fetch('https://example.com/api/donations', {
//       method: 'POST',
//       headers: { 'Content-Type': 'application/json' },
//       body: JSON.stringify(payload)
//     });

//     if (response.ok) {
//       alert(`Thank you, ${form.value.name}! Your donations have been successfully submitted.`);
//       form.reset(); // Reset the form
//       proposals.value.forEach(proposal => (proposal.donationAmount = ''));
//     } else {
//       const errorData = await response.json();
//       console.error('Error:', errorData);
//       alert('Failed to submit donations. Please try again.');
//     }
//   } catch (error) {
//     console.error('Network error:', error);
//     alert('An error occurred while submitting your donations. Please try again.');
//   }
// };
  </script>
  