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
import { ref, watch, computed } from "vue";
import PhoneInput from "@/Components/PhoneInput.vue";
const props = defineProps({
  status_options: Array,
  currencies: Array,
  proposals: Array,
  payment_methods: Array,
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
  document_nickname: "",
  currency_id: "",
  payment_method_id: "",
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
const hasDocument = computed(() => {
  const proposal = props.proposals.find(p => p.id == form.proposal_id);
  console.log("proposal", proposal);
  console.log("proposal_id", form.proposal_id);
  return proposal && proposal.min_documenting_amount <= form.amount;
});
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
        <div v-show="hasDocument">
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
          <InputLabel for="payment_method_id" value="Payment Method" />
          <SelectInput
            :options="payment_methods"
            :item_name="`name_${i18n_locale}`"
            id="payment_method_id"
            v-model="form.payment_method_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.payment_method_id" class="mt-2" />
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
