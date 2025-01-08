<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
// import CheckBox from "@/Components/Checkbox.vue";
import SwitchInput from "@/Components/SwitchInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import CenterLayout from "@/Layouts/CenterLayout.vue";
import SiteLayout from "@/Layouts/SiteLayout.vue";
import CreateFullSizeLayout from "@/Layouts/CreateFullSizeLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import PhoneInput from "@/Components/PhoneInput.vue";
import NationalIDInput from "@/Components/NationalIDInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import { isValidPalestinianID } from '@/utils/validators';
import AddingItemsTable from "@/Components/AddingItemsTable.vue";

const props = defineProps({
  status_options: Array,
  currencies: Array,
  proposal_detail_headers: Array,
  
  
});


const form = useForm({
  title: "",
  body: "",
  status: "",
  notes: "",
  currency_id: "",
  proposal_details: [],
});

const submit = () => {


  

  form.post(route("proposal.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};




</script>
<template>
  <Head :title="$t('Add Proposal')" />
  <CreateFullSizeLayout >
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("add new proposal") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
        <div>
          <InputLabel for="title" value="Title" />
          <TextInput
            id="title"
            type="text"
            class="mt-1 block w-full"
            v-model="form.title"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.title" />
        </div>
        <div>
          <InputLabel for="body" value="Body" />
          <TextInput
            id="body"
            type="text"
            class="mt-1 block w-full"
            v-model="form.body"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.body" />
        </div>
        <div>
          <InputLabel for="status" value="Status" />
          <SelectInput
            :options="status_options"
            id="status"
            v-model="form.status"
            class="mt-1 block w-full"
          />
          <InputError :message="form.errors.status" class="mt-2" />
        </div> 
        <div>
          <InputLabel for="currency_id" value="Currency" />
          <SelectInput
            :options="currencies"
            :item_name="`name_${i18n_locale}`"
            id="currency_id"
            v-model="form.currency_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            required
          />
          <InputError :message="form.errors.currency_id" class="mt-2" />
        </div>

        </div>


        <AddingItemsTable 
          :headers="proposal_detail_headers"
          v-model="form.proposal_details"
        />
       
        
        <div class="flex items-center gap-4">
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
    </section>
  </CreateFullSizeLayout>
</template>
