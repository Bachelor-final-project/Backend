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
import { Head } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import PhoneInput from "@/Components/PhoneInput.vue";
const props = defineProps({
  status_options: Array,
  type_options: Array,
});

// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const form = useForm({
  type: 2,
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  job_title: "",
  is_active: true
});
const submit = () => {
  // form
  // .transform((data) => ({
  //   ...data,
  //   remember: data.remember ? 'on' : '',
  // }))
  form.post(route("user.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>
<template>
  <Head :title="$t('Add User')" />
  <CenterLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("add new user") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>
          <!-- <div class="flex">
            <InputLabel for="is_admin" value="Set Admin" />
            <SwitchInput
              id="is_admin"
              :value="1"
              v-model="isAdminChecked"
              class="mx-3"
            />
          </div> -->
          <InputLabel for="name" value="Name" />

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
          <InputLabel for="email" value="Email" />

          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
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
            required
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.phone" />
        </div>
        <div>
          <InputLabel for="job_title" value="job title" />

          <TextInput
            id="job_title"
            type="text"
            class="mt-1 block w-full"
            v-model="form.job_title"
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.job_title" />
        </div>

        <div>
          <InputLabel for="type" value="Type" />
          <SelectInput
            :options="type_options"
            :item_name="`name_${i18n_locale}`"
            id="regions"
            ref="regionInput"
            v-model="form.type"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.type" class="mt-2" />
        </div>

        <div>
          <InputLabel for="password" value="Password" />

          <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />

          <InputError :message="form.errors.password" class="mt-2" />
        </div>

        <div class="mt-4">
          <InputLabel for="password_confirmation" value="Confirm Password" />

          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />

          <InputError
            class="mt-2"
            :message="form.errors.password_confirmation"
          />
        </div>
        <!-- <div>
          <InputLabel for="status" value="Status" />
          <SelectInput
            :options="status_options"
            :item_name="`name_${i18n_locale}`"
            id="status"
            ref="regionInput"
            v-model="form.status"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.status" class="mt-2" />
        </div> -->
        
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
  </CenterLayout>
</template>
