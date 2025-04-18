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
import TopRightLayout from "@/Layouts/TopRightLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import PhoneInput from "@/Components/PhoneInput.vue";
import NationalIDInput from "@/Components/NationalIDInput.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import '@vuepic/vue-datepicker/dist/main.css'
import { isValidPalestinianID } from '@/utils/validators';
import { computed } from "vue";
const props = defineProps({
  proposals: Array,
  donors: Array,
  currencies: Array,
  document: Array,
});

const datepicker = ref(null);
// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const form = useForm({
  proposal_id: props.document.proposal_id,
  donor_id: props.document.donor_id,
  currency_id: props.document.currency_id,
  amount: props.document.amount,
  note: props.document.note,
  expected_date: props.document.expected_date,
  document_nickname: props.document.document_nickname,
});

const submit = () => {

  form.expected_date = datePickerFormat(form.expected_date);

  form.put(route("document.update", props.document), {
    onFinish: () => {
      form.defaults();
    },
  });
};

function getDatePcikerYearsRange() {
  const currentYear = new Date().getFullYear();
    const hundredYearsAgo = currentYear - 100;
  return [hundredYearsAgo, currentYear];
}

const datePickerFormat = (date) => {
  if (date && (date instanceof Date)) {
    // console.log("format Ran " + date);  
    let day = date.getDate();
    let month = date.getMonth() + 1;
    if (day < 10) day = "0" + day;
    if (month < 10) month = "0" + month;
    let formattedDate  = `${date.getFullYear()}-${month}-${day}`;
    // form.dob = formattedDate ;
    return formattedDate ;
  }
}
const hasDocument = computed(() => props.document.proposal.min_documenting_amount <= form.amount);

// function selectDate() {
//   // console.log("Hello, Close");
//   // console.log(this.$refs.datepicker);
//   if (datepicker.value) {
//     datepicker.value.selectDate();
//     datepicker.value.closeMenu();
//   }
// }

</script>
<template>
  <Head :title="$t('Edit Document')" />
  <TopRightLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("update document") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>
          <InputLabel for="proposal_id" value="Proposal" />
          <SelectInput
            :options="proposals"
            :item_name="`title`"
            id="proposal_id"
            v-model="form.proposal_id"
            class="mt-1 block w-full"
          />
          <InputError :message="form.errors.proposal_id" class="mt-2" />
        </div>

        <div>
          <InputLabel for="donor_id" value="Donor" />
          <SelectInput
            :options="donors"
            :item_name="`name_${i18n_locale}`"
            id="donor_id"
            v-model="form.donor_id"
            class="mt-1 block w-full"
          />
          <InputError :message="form.errors.donor_id" class="mt-2" />
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
          <InputLabel for="amount" value="Amount" />
          <TextInput
            id="amount"
            type="number"
            class="mt-1 block w-full"
            v-model="form.amount"
            autofocus
            autocomplete="amount"
          />
          <InputError class="mt-2" :message="form.errors.amount" />
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
          />
          <InputError :message="form.errors.currency_id" class="mt-2" />
        </div>
        <div>
          <InputLabel for="note" value="Note" />
          <TextInput
            id="note"
            type="text"
            class="mt-1 block w-full"
            v-model="form.note"
            autofocus
            autocomplete="note"
          />
          <InputError class="mt-2" :message="form.errors.note" />
        </div>
       
        <div class="flex col-span-6 md:col-span-2 pe-32 flex-col">
          <InputLabel for="expected_date" value="Expected Date" />
        <VueDatePicker
            id="expected_date"
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1"
            v-model="form.expected_date"
            auto-apply
            :min-date="new Date()"
            :year-range="getDatePcikerYearsRange()"
            :enable-time-picker="false"
            :format="datePickerFormat"
            ref="datepicker"
          >
          </VueDatePicker>      
        </div>  
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
  </TopRightLayout>
</template>
