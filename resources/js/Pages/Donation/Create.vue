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
  currencies: Array,
  proposals: Array,
});

// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const form = useForm({
  phone: "",
  proposal_id: "",
  currency_id: "",
  amount: "",
  status: 2 // set donation status as 'approved' by default
});
const submit = () => {
  form.post(route("donation.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>
<template>
  <Head :title="$t('Add Donation')" />
  <TopRightLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("add new donation") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>

          <InputLabel for="phone" value="Phone" />
          <PhoneInput
            id="phone"
            type="text"
            class="mt-1 block w-full"
            v-model="form.phone"
            required
            autofocus
            autocomplete="phone"
          />
          <InputError class="mt-2" :message="form.errors.phone" />
        </div>

        <div>
          <InputLabel for="proposal_id" value="Project" />
          <SelectInput
            :options="proposals"
            :item_name="'title'"
            id="currency_id"
            v-model="form.proposal_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            required
          />
          <InputError :message="form.errors.proposal_id" class="mt-2" />
        </div>

        <div>
          <InputLabel for="currency_id" value="Currency" />
          <SelectInput
            :options="currencies"
            :item_name="'name'"
            id="currency_id"
            v-model="form.currency_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
            required
          />
          <InputError :message="form.errors.currency_id" class="mt-2" />
        </div>

        <div>

          <InputLabel for="amount" value="Amount" />
          <TextInput
            id="amount"
            type="number"
            class="mt-1 block w-full"
            v-model="form.amount"
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.amount" />
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
            required
          />
          <InputError :message="form.errors.status" class="mt-2" />
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
