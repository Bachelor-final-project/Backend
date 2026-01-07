<template>
  <Head :title="$t(entity.name)" />
  <div v-if="proposals.length > 0" class="p-4 rounded-lg dark:border-gray-700 mt-14">
    <div class="container mx-auto px-4 sm:px-1 lg:px-4 scroll-smooth">
      <h1 class="text-4xl font-bold text-center text-gray-800 mb-10 text-gray-900 dark:text-gray-100">{{ entity.home_title && entity.home_title[i18n_locale] ? entity.home_title[i18n_locale] : $t(entity.name) }}</h1>
      <div v-if="entity.home_description && entity.home_description[i18n_locale]" class="text-center mb-8">
        <p class="text-lg text-gray-600 dark:text-gray-300">{{ entity.home_description[i18n_locale] }}</p>
      </div>
      <div v-if="entity.whatsapp_number" class="text-center mb-8">
        <a :href="`https://wa.me/${entity.whatsapp_number}`" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
          </svg>
          {{ $t('Contact via WhatsApp') }}
        </a>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <NewProposalCard
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
            <PhoneInput
              v-model="form.phone"
              :countries="countries"
              :default-country-code="entity.country?.calling_code"
              placeholder="Enter your phone number"
              required
            />
            <InputError :message="form.errors.phone" class="mt-2" />
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
          <SelectButtonsInput
            :options="payment_methods"
            :item_name="`name_${i18n_locale}`"
            id="payment_method_id"
            v-model="form.payment_method_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            
          />
          <InputError :message="form.errors.payment_method_id" class="mt-2" />
        </div>
        <div class=" w-full">
          <InputLabel for="gender" value="Gender" />
          <SelectButtonsInput
            :options="genders"
            :item_name="`name_${i18n_locale}`"
            id="gender_id"
            v-model="form.gender"
            class="mt-1"
            required
          />
          <InputError :message="form.errors.gender" class="mt-2" />
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
      <h1 class="text-4xl font-bold text-center text-gray-800 mb-10 text-gray-900 dark:text-gray-100">{{ entity.home_title && entity.home_title[i18n_locale] ? entity.home_title[i18n_locale] : $t(entity.name) }}</h1>
      <div v-if="entity.home_description && entity.home_description[i18n_locale]" class="text-center mb-8">
        <p class="text-lg text-gray-600 dark:text-gray-300">{{ entity.home_description[i18n_locale] }}</p>
      </div>
      <div v-if="entity.whatsapp_number" class="text-center mb-8">
        <a :href="`https://wa.me/${entity.whatsapp_number}`" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
          </svg>
          {{ $t('Contact via WhatsApp') }}
        </a>
      </div>
      <p class="text-2xl font-bold text-center text-gray-800 mb-10 text-gray-900 dark:text-gray-100">{{ $t('sorry, there is no any available projects to donate for at the moment') }}</p>
      
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
  import NewProposalCard from '@/Components/NewProposalCard.vue';
  import InputError from "@/Components/InputError.vue";
  import TextInput from "@/Components/TextInput.vue";
  import PhoneInput from "@/Components/PhoneInput.vue";
  import InputLabel from "@/Components/InputLabel.vue";
  import SelectInput from "@/Components/SelectInput.vue";
  import SelectButtonsInput from "@/Components/SelectButtonsInput.vue";
  import { ref, watch, computed } from "vue";
  import { useI18n } from "vue-i18n";
  
  const { locale } = useI18n();
  const i18n_locale = computed(() => locale.value);

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
    country_id: props.entity.country_id || '',
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
    console.log(hasDocument.value);
    form.donations = form.donations.filter((item) => item.amount !== 0);
  };

function saveWithPayOnline() {
  form.payOnline = true
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
  