<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Textaerea from "@/Components/Textaera.vue";
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
import { useI18n } from "vue-i18n";
    
const i18nLocale = useI18n();
// const i18n_locale = i18nLocale.locale.value
// console.log(i18n_locale)
const props = defineProps({
  supervisors: Array,
  
  
});



const form = useForm({
  name: "",
  supervisor_id: "",
  donating_form_path: "",
});

const submit = () => {


  

  form.post(route("entity.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};




</script>
<template>
  <Head :title="$t('Add Entity')" />
  <CreateFullSizeLayout >
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("add new entity") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
        <div>
          <InputLabel for="name" value="name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>
        <div>
          <InputLabel for="donating_form_path" value="donating_form_path" />
          <TextInput
            id="donating_form_path"
            type="text"
            class="mt-1 block w-full"
            v-model="form.donating_form_path"
            required
            autofocus
            autocomplete="donating_form_path"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>
        <div>
          <InputLabel for="supervisor_id" value="entity supervisor" />
          <SelectInput
            :options="supervisors"
            :item_name="`name`"
            id="supervisor_id"
            v-model="form.supervisor_id"
            class="mt-1 block w-full"
            required
          />
          <InputError :message="form.errors.supervisor_id" class="mt-2" />
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
