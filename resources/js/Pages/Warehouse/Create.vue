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
import TopRightLayout from "@/Layouts/TopRightLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import PhoneInput from "@/Components/PhoneInput.vue";
const props = defineProps({
  status_options: Array,
});

// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const form = useForm({
  name: "",
  bio: "",
  status: "",
  location: ""
});
const submit = () => {
  // form
  // .transform((data) => ({
  //   ...data,
  //   remember: data.remember ? 'on' : '',
  // }))
  form.post(route("warehouse.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>
<template>
  <Head :title="$t('Add Warehouse')" />
  <TopRightLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("add new warehouse") }}
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
            required
            autofocus
            autocomplete="name"
          />

          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
          <InputLabel for="bio" value="Bio" />
          <TextInput
            id="bio"
            type="text"
            class="mt-1 block w-full"
            v-model="form.bio"
            autofocus
            autocomplete="bio"
          />

          <InputError class="mt-2" :message="form.errors.bio" />
        </div>

        <div>
          <InputLabel for="location" value="Location" />

          <TextInput
            id="location"
            type="text"
            class="mt-1 block w-full"
            v-model="form.location"
            required
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.location" />
        </div>

        <div>
          <InputLabel for="status" value="Status" />
          <SelectInput
            :options="status_options"
            :item_name="`name_${i18n_locale}`"
            id="status"
            v-model="form.status"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.status.type" class="mt-2" />
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
