<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Textarea from "@/Components/Textaera.vue";
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
import CreateFullSizeLayout from "@/Layouts/CreateFullSizeLayout.vue";
import { isValidPalestinianID } from '@/utils/validators';
const props = defineProps({
  warehouses: Array,
  beneficiary: Array,
  social_statuses: Array,
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
  warehouse_id: props.beneficiary.warehouse_id,
  num_of_family_members: props.beneficiary.num_of_family_members,
  social_status: props.beneficiary.social_status,
  address: props.beneficiary.address
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
  if (form.father_national_id && !isValidPalestinianID(form.father_national_id)) {
    console.log("Father ID: " + form.father_national_id);
    form.errors.father_national_id = "Enter Valid Father National ID";
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
  <Head :title="$t('Edit Beneficiary')" />
  <CreateFullSizeLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("update beneficiary") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
        <div>
          <InputLabel for="name" value="Name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            autofocus
            autocomplete="name"
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
          <InputLabel for="num_of_family_members" value="Num Of Family Members" />

          <TextInput
            id="num_of_family_members"
            type="number"
            class="mt-1 block w-full"
            v-model="form.num_of_family_members"
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.num_of_family_members" />
        </div>

        <div>
          <InputLabel for="social_status" value="Social Status" />
          <SelectInput
            :options="social_statuses"
            :item_name="`name`"
            id="social_status"
            v-model="form.social_status"
            class="mt-1 block w-full"
          />
          <InputError :message="form.errors.social_status" class="mt-2" />
        </div>

        

        
        <!-- <div>
          <InputLabel for="warehouse_id" value="Warehouse" />
          <SelectInput
            :options="warehouses"
            :item_name="`name_${i18n_locale}`"
            id="warehouse_id"
            v-model="form.warehouse_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.warehouse_id" class="mt-2" />
        </div> -->

        <div>
          <InputLabel for="dob" value="Date Of Birth" />
          <TextInput
            id="dob"
            type="date"
            class="mt-1 block w-full"
            v-model="form.dob"
            autocomplete="dob"
          />
          <InputError :message="form.errors.dob" class="mt-2" />

        </div>  

        <div>
          <InputLabel for="address" value="Address" />
          <Textarea
            placeholder=""
            id="address"
            type="text"
            class="mt-1 block w-full"
            v-model="form.address"
            autofocus
            autocomplete="address"
          />
          <InputError :message="form.errors.address" class="mt-2" />

        </div>  
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
  </CreateFullSizeLayout>

</template>
