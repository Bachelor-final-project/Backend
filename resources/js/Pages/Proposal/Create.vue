<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Textarea from "@/Components/Textaera.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import FileInput from "@/Components/FileInput.vue";
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
import { useI18n } from "vue-i18n";

const isFileInputLoading = ref(false);

const i18nLocale = useI18n();
// const i18n_locale = i18nLocale.locale.value
// console.log(i18n_locale)
const props = defineProps({
  // status_options: Array,
  currencies: Array,
  entities: Array,
  proposal_types: Array,
  areas: Array,
  
  
});



const form = useForm({
  title: "",
  body: "",
  notes: "", 
  currency_id: "", 
  proposal_effects: "",
  cost: "",
  share_cost: "",
  expected_benificiaries_count: "", 
  min_documenting_amount: "", 
  execution_date: "",
  publishing_date: "",
  entity_id: "",
  proposal_type_id: "",
  area_id: "",
  isPayableOnline: false,
  files: "",
});

const submit = () => {
  form.post(route("proposal.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};

function fileinputChanged(files) {
  form.files = files;
}


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
        <div class="grid grid-cols-4 gap-4">
        <div>
          <InputLabel for="title" value="title" />
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
          <InputLabel for="proposal_type_id" value="proposal type" />
          <SelectInput
            :options="proposal_types"
            :item_name="`type_${i18n_locale}`"
            id="proposal_type_id"
            v-model="form.proposal_type_id"
            class="mt-1 block w-full"
            required
          />
          <InputError :message="form.errors.proposal_type_id" class="mt-2" />
        </div>
        <div>
          <InputLabel for="area_id" value="area" />
          <SelectInput
            :options="areas"
            :item_name="`name`"
            id="area_id"
            v-model="form.area_id"
            class="mt-1 block w-full"
            required
          />
          <InputError :message="form.errors.area_id" class="mt-2" />
        </div>
        <div>
          <InputLabel for="entity_id" value="entity" />
          <SelectInput
            :options="entities"
            :item_name="`name_${i18n_locale}`"
            id="entity_id"
            v-model="form.entity_id"
            class="no-scrollbar mt-1 block w-full"
            required
          />
          <InputError :message="form.errors.entity_id" class="mt-2" />
        </div>

        <div>
          <InputLabel for="cost" value="cost" />
          <TextInput
            id="cost"
            type="number"
            class="mt-1 block w-full"
            v-model="form.cost"
            required
            autofocus
            autocomplete="cost"
          />
          <InputError :message="form.errors.cost" class="mt-2" />
        </div> 
        <div>
          <InputLabel for="share_cost" value="share cost" />
          <TextInput
            id="share_cost"
            type="number"
            class="mt-1 block w-full"
            v-model="form.share_cost"
            required
            autofocus
            autocomplete="share_cost"
          />
          <InputError :message="form.errors.share_cost" class="mt-2" />
        </div> 
        <div>
          <InputLabel for="min_documenting_amount" value="min documenting amount" />
          <TextInput
            id="min_documenting_amount"
            type="number"
            class="mt-1 block w-full"
            v-model="form.min_documenting_amount"
            required
            autofocus
            autocomplete="min_documenting_amount"
          />
          <InputError :message="form.errors.min_documenting_amount" class="mt-2" />
        </div>  
        <div>
          <InputLabel for="currency_id" value="currency" />
          <SelectInput
            :options="currencies"
            :item_name="`name`"
            id="currency_id"
            v-model="form.currency_id"
            class="mt-1 block w-full"
            required
          />
          <InputError :message="form.errors.currency_id" class="mt-2" />
        </div>
        <div>
          <InputLabel for="expected_benificiaries_count" value="expected benificiaries count" />
          <TextInput
            id="expected_benificiaries_count"
            type="number"
            class="mt-1 block w-full"
            v-model="form.expected_benificiaries_count"
            required
            autofocus
            autocomplete="expected_benificiaries_count"
          />
          <InputError :message="form.errors.expected_benificiaries_count" class="mt-2" />
        </div> 
        <div class="auto-cols-max">
          <InputLabel for="publishing_date" value="publishing date" />
          <TextInput
            id="publishing_date"
            type="date"
            class="mt-1 block w-full"
            v-model="form.publishing_date"
            required
            autofocus
            autocomplete="publishing_date"
          />
          <InputError :message="form.errors.publishing_date" class="mt-2" />
        </div> 

        <div>
          <InputLabel for="execution_date" value="execution date" />
          <TextInput
            id="execution_date"
            type="date"
            class="mt-1 block w-full"
            v-model="form.execution_date"
            required
            autofocus
            autocomplete="execution_date"
          />
          <InputError :message="form.errors.execution_date" class="mt-2" />
        </div> 
        <div class="auto-cols-max">
          <InputLabel for="proposal_file" value="cover photo" />
          <FileInput 
            id="proposal_file"
            model="proposal"
            v-model="form.files"
            attachment_type="1"
            :show_files_details="false"
            @fileinput-change="fileinputChanged"
            @finish-uploading="isFileInputLoading = false"
            @start-uploading="isFileInputLoading = true"

          />
          <InputError :message="form.errors.proposal_file" class="mt-2" />
        </div> 
        <div class="auto-cols-max" v-if="form.currency_id == 1">
          <InputLabel for="isPayableOnline" value="can be payed online" />
          <div class="py-3">
            <SwitchInput
              id="isPayableOnline"
              :value="1"
              v-model="form.isPayableOnline"
              class="mx-3"
              
            />
          </div>
        </div> 
        <div class="col-span-2">
          <InputLabel for="body" value="proposal body" />
          <Textarea
            placeholder=""
            id="body"
            class="mt-1 block w-full"
            v-model="form.body"
            required
            autofocus
            autocomplete="body"
          />
          <InputError class="mt-2" :message="form.errors.body" />
        </div>
        <div class="col-span-2">
          <InputLabel for="proposal_effects" value="proposal effects" />
          <Textarea
            placeholder=""
            id="proposal_effects"
            type="text"
            class="mt-1 block w-full"
            v-model="form.proposal_effects"
            required
            autofocus
            autocomplete="proposal_effects"
          />
          <InputError class="mt-2" :message="form.errors.proposal_effects" />
        </div>
        
        
        
        <div class="col-span-4">
          <InputLabel for="notes" value="notes" />
          <Textarea
            placeholder=""
            id="notes"
            type="text"
            class="mt-1 block w-full"
            v-model="form.notes"
            required
            autofocus
            autocomplete="notes"
          />
          <InputError class="mt-2" :message="form.errors.notes" />
        </div>


        </div>

       
        
        <div class="flex items-center gap-4">
          <PrimaryButton :disabled="form.processing">
            <span v-if="isFileInputLoading"> {{ $t("Saving") }}...</span>
            <span v-else> {{ $t("Save") }} </span>
          </PrimaryButton>

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
