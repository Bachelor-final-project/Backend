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
  units: Array,
  item: Array
});

// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const form = useForm({
  name: props.item.name,
  estimated_price: props.item.estimated_price,
  description: props.item.description,
  unit_id: props.item.unit_id
});
const submit = () => {
  // form
  // .transform((data) => ({
  //   ...data,
  //   remember: data.remember ? 'on' : '',
  // }))
  form.put(route("item.update", props.item), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>
<template>
  <Head :title="$t('Edit Item')" />
  <TopRightLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("update item") }}
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

        <InputLabel for="estimated_price" value="Estimated Price" />
        <TextInput
          id="estimated_price"
          type="number"
          class="mt-1 block w-full"
          v-model="form.estimated_price"
          required
          autofocus
          autocomplete="name"
        />
        <InputError class="mt-2" :message="form.errors.estimated_price" />
        </div>

        <div>
          <InputLabel for="unit_id" value="Unit" />
          <SelectInput
            :options="units"
            :item_name="`name_${i18n_locale}`"
            id="unit_id"
            v-model="form.unit_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            required
          />
          <InputError :message="form.errors.unit_id" class="mt-2" />
        </div>


        <div>

          <InputLabel for="description" value="Description" />
          <TextInput
            id="description"
            type="text"
            class="mt-1 block w-full"
            v-model="form.description"
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.description" />
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
