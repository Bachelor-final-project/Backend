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
const props = defineProps({
  warehouses: Array,
  beneficiary: Array
});

const datepicker = ref(null);
// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const form = useForm({
  name: props.beneficiary.name,
  email:props.beneficiary.email,
  phone:props.beneficiary.phone,
  dob:props.beneficiary.dob,
  national_id:props.beneficiary.national_id,
  father_national_id:props.beneficiary.father_national_id,
  warehouse_id:props.beneficiary.warehouse_id
});

const submit = () => {
  form.national_id = "" + form.national_id;
  form.father_national_id = "" + form.father_national_id;

  if (!isValidPalestinianID(form.national_id)) {
    form.errors.national_id = "Enter Valid National ID";
    return;
  } else {
    form.errors.national_id = '';
  }
  if (form.father_national_id != "" && !isValidPalestinianID(form.father_national_id)) {
    form.errors.father_national_id = "Enter Valid National ID";
    return;
  } else {
    form.errors.father_national_id = '';
  }
  if (form.national_id == form.father_national_id) {
    form.errors.father_national_id = 'Father National ID Can Not Be The Save As Your National ID';
    return;
  } else {
    form.errors.father_national_id = '';
  }

  form.dob = datePickerFormat(form.dob);

  form.put(route("beneficiary.update", props.beneficiary), {
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
  } else {
    return form.dob;
  }
}

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
  <Head :title="$t('Edit Entity')" />
  <TopRightLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("update entity") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>
          <InputLabel for="name" value="Name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            autofocus
            autocomplete="name"
            required
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
          <InputLabel for="email" value="Email" />

          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            autocomplete="username"
            required
          />

          <InputError class="mt-2" :message="form.errors.email" />
        </div>
        <div>
          <InputLabel for="phone" value="Phone" />

          <PhoneInput
            id="phone"
            type="text"
            class="mt-1 block w-full"
            v-model="form.phone"
            autocomplete="username"
            required
          />

          <InputError class="mt-2" :message="form.errors.phone" />
        </div>
        <div>
          <InputLabel for="national_id" value="National ID" />

          <NationalIDInput
            id="national_id"
            type="number"
            class="mt-1 block w-full"
            v-model="form.national_id"
            autocomplete="username"
            required
          />

          <InputError class="mt-2" :message="form.errors.national_id" />
        </div>
        <div>
          <InputLabel for="father_national_id" value="Fathe National ID" />

          <NationalIDInput
            id="father_national_id"
            type="number"
            class="mt-1 block w-full"
            v-model="form.father_national_id"
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.father_national_id" />
        </div>

        <div>
          <InputLabel for="warehouse_id" value="Warehouse" />
          <SelectInput
            :options="warehouses"
            :item_name="`name_${i18n_locale}`"
            id="warehouse_id"
            v-model="form.warehouse_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            required
          />
          <InputError :message="form.errors.region_id" class="mt-2" />
        </div>

        <div class="flex col-span-6 md:col-span-2 pe-32 flex-col">
          <InputLabel for="dob" value="Date Of Birth" />
        <VueDatePicker
            id="dob"
            class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1"
            v-model="form.dob"
            auto-apply
            :max-date="new Date()"
            :year-range="getDatePcikerYearsRange()"
            :enable-time-picker="false"
            :format="datePickerFormat"
            ref="datepicker"
            required
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
