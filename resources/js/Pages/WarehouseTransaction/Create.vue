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
import CreateFullSizeLayout from "@/Layouts/CreateFullSizeLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { useI18n } from "vue-i18n";

const { t: $t } = useI18n();
import PhoneInput from "@/Components/PhoneInput.vue";
const props = defineProps({
  warehouses: Array,
  items: Array,
  transaction_types: Array,
  stakeholders: Array,
});

// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const defaultTransaction = {
  warehouse_id: "",
  item_id: "",
  amount: "",
  transaction_type: "",
  warehouse_stakeholder_id: "",
  recipient_name: "",
};

const form = useForm({
  transactions: [{ ...defaultTransaction }],
});

const getFilteredStakeholders = (transactionType) => {
  if (!transactionType) return props.stakeholders;
  return props.stakeholders.filter(stakeholder => stakeholder.type == transactionType);
};

const addTransaction = () => {
  const lastTransaction = form.transactions[form.transactions.length - 1];
  form.transactions.push({ ...lastTransaction });
};

const removeTransaction = (index) => {
  if (form.transactions.length > 1) {
    form.transactions.splice(index, 1);
  }
};
const submit = () => {
  form.post(route("warehouse_transaction.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>
<template>
  <Head :title="$t('Add Transaction')" />
  <CreateFullSizeLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("add new transaction") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div class="overflow-visible">
          <table class="min-w-full border border-gray-300 dark:border-gray-600" :dir="$i18n.locale === 'ar' ? 'rtl' : 'ltr'">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">#</th>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $t('Warehouse') }}</th>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $t('Item') }}</th>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $t('Quantity') }}</th>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $t('Type') }}</th>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $t('Stakeholder') }}</th>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $t('Recipient Name') }}</th>
                <th class="px-4 py-2 text-left rtl:text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $t('Action') }}</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="(transaction, index) in form.transactions" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-left rtl:text-right">{{ index + 1 }}</td>
                <td class="px-4 py-2">
                  <SelectInput
                    :options="warehouses"
                    :item_name="`name_${i18n_locale}`"
                    v-model="transaction.warehouse_id"
                    class="w-full"
                    required
                  />
                  <InputError :message="form.errors[`transactions.${index}.warehouse_id`]" class="mt-1" />
                </td>
                <td class="px-4 py-2">
                  <SelectInput
                    :options="items"
                    :item_name="`name_${i18n_locale}`"
                    v-model="transaction.item_id"
                    class="w-full"
                    required
                  />
                  <InputError :message="form.errors[`transactions.${index}.item_id`]" class="mt-1" />
                </td>
                <td class="px-4 py-2">
                  <TextInput
                    type="number"
                    class="w-full"
                    v-model="transaction.amount"
                    required
                  />
                  <InputError :message="form.errors[`transactions.${index}.amount`]" class="mt-1" />
                </td>
                <td class="px-4 py-2">
                  <SelectInput
                    :options="transaction_types"
                    :item_name="`name_${i18n_locale}`"
                    v-model="transaction.transaction_type"
                    class="w-full"
                    required
                  />
                  <InputError :message="form.errors[`transactions.${index}.transaction_type`]" class="mt-1" />
                </td>
                <td class="px-4 py-2 relative">
                  <SelectInput
                    :options="getFilteredStakeholders(transaction.transaction_type)"
                    :item_name="`name`"
                    v-model="transaction.warehouse_stakeholder_id"
                    :searchable="true"
                    class="w-full"
                    style="z-index: 1000;"
                  />
                  <InputError :message="form.errors[`transactions.${index}.warehouse_stakeholder_id`]" class="mt-1" />
                </td>
                <td class="px-4 py-2">
                  <TextInput
                    v-if="transaction.transaction_type == 2"
                    type="text"
                    class="w-full"
                    v-model="transaction.recipient_name"
                    :placeholder="$t('Enter recipient name')"
                  />
                  <InputError :message="form.errors[`transactions.${index}.recipient_name`]" class="mt-1" />
                </td>
                <td class="px-4 py-2">
                  <button
                    v-if="form.transactions.length > 1"
                    type="button"
                    @click="removeTransaction(index)"
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-lg"
                  >
                    ✕
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex justify-end">
          <button
            type="button"
            @click="addTransaction"
            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            + {{ $t('Add Transaction') }}
          </button>
        </div>

        <div class="flex items-center gap-4">
          <PrimaryButton :disabled="form.processing">{{
            $t("Save All Transactions")
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
              {{ $t('Saved') }}.
            </p>
          </Transition>
        </div>
      </form>
    </section>
  </CreateFullSizeLayout>
</template>
