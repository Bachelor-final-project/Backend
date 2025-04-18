<template>
  <Head :title="$t(entity.name)" />
  <div v-if="proposals.length > 0" class="p-4 rounded-lg dark:border-gray-700 mt-14">
    <div class="container mx-auto px-4 sm:px-1 lg:px-4 scroll-smooth">
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
        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 pb-2">{{ $t('Your Information') }}</h2>
        <form @submit.prevent="submitDonations" >
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
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
          <div
              v-show="hasDocument"
          >
            <InputLabel for="document_nickname" value="document_nickname" />
            <TextInput
              id="document_nickname"
              type="text"
              v-model="form.document_nickname"
              class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300"
              
            />
            <InputError :message="form.errors.document_nickname" class="mt-2" />

          </div>
          <div>
            <InputLabel for="phone" value="phone" />
            <TextInput
              id="phone"
              type="tel"
              v-model="form.phone"
              maxlength="15"
              placeholder="Enter your phone number"
              class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-300"
              required
              dir="ltr"
              @input="clearPhoneInput"
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
            searchable="true"
            
          />
          <InputError :message="form.errors.country_id" class="mt-2" />
        </div>
        <div>
          <InputLabel for="payment_method_id" value="Payment method" />
          <SelectInput
            :options="payment_methods"
            :item_name="`name_${i18n_locale}`"
            id="payment_method_id"
            v-model="form.payment_method_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            
          />
          <InputError :message="form.errors.payment_method_id" class="mt-2" />
        </div>
        </div>


        <div class="pt-2 flex justify-end">
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
  <div v-else class="p-4 rounded-lg dark:border-gray-700 mt-14">
    <div class="container m-auto px-4 h-full">
      <h1 class="text-4xl font-bold text-center text-gray-800 mb-10 text-gray-900 dark:text-gray-100">{{ $t('sorry, there is no any available projects to donate for at the moment') }}</h1>
      
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
  import { ref, watch } from "vue";

  defineOptions({ layout: DonatingPageLayout });
  const hasDocument = ref(false);
  
  const props = defineProps({
    entity: Array,
    proposals: Array,
    countries: Array,
    genders: Array,
    payment_methods: Array,
    show_payonline_button: Boolean,
  });
  const form = useForm({
    name: '',
    phone: '',
    country_id: '',
    gender: '',
    payment_method_id: '',
    document_nickname: '',
    donations: [] 
  });
  const handleDonation = (proposalId, amount,currency_id, pay_online, min_documenting_amount) => {

    const existingDonation = form.donations.find(d => d.proposal_id === proposalId);
    if (existingDonation) {
      existingDonation.amount = amount;
      existingDonation.pay_online = pay_online;
    } else {
      form.donations.push({ proposal_id: proposalId, amount: amount, currency_id: currency_id, pay_online: pay_online, min_documenting_amount:min_documenting_amount });
    }
    hasDocument.value = form.donations.some(item => item.amount >= item.min_documenting_amount);
    form.donations = form.donations.filter((item) => item.amount !== 0);
  };

function saveWithPayOnline() {
  form.payOnline = true
}
  

  const clearPhoneInput = () =>{
      console.log(form.phone)
      form.phone = form.phone.replace(/(?!^\+)[^\d]/g, "");
  }
  const submitDonations = () => {
  form.phone = form.phone.replace(/(^\+)/g, "00");
  form.post(route("store-donating-form"), {
    onFinish: () => {
      form.defaults();
    },
  });
};
  </script>
  