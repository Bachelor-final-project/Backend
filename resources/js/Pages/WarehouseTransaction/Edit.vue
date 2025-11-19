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
import { useI18n } from "vue-i18n";

const { t: $t } = useI18n();
import PhoneInput from "@/Components/PhoneInput.vue";
const props = defineProps({
  warehouses: Array,
  items: Array,
  transaction_types: Array,
  stakeholders: Array,
  warehouse_transaction: Array,
});

// const isAdminChecked = ref(false);

// watch(isAdminChecked, (newValue, oldValue) => {
//   form.type = newValue ? 1 : 2;
//   form.region_id = newValue ? "" : form.region_id;
//   // newValue ? (form.region_id = "") : null;
// });

const form = useForm({
  warehouse_id: props.warehouse_transaction.warehouse_id,
  item_id: props.warehouse_transaction.item_id,
  amount: props.warehouse_transaction.amount,
  transaction_type: props.warehouse_transaction.transaction_type,
  warehouse_stakeholder_id: props.warehouse_transaction.warehouse_stakeholder_id || "",
});

const filteredStakeholders = computed(() => {
  if (!form.transaction_type) return props.stakeholders;
  return props.stakeholders.filter(stakeholder => stakeholder.type == form.transaction_type);
});
const submit = () => {
  // form
  // .transform((data) => ({
  //   ...data,
  //   remember: data.remember ? 'on' : '',
  // }))
  form.put(route("warehouse_transaction.update", props.warehouse_transaction), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>
<template>
  <Head :title="$t('Edit Transaction')" />
  <TopRightLayout>
    <section>
      <header>
        <h2
          class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
        >
          {{ $t("update transaction") }}
        </h2>

        <!-- <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("New user can review forms depens on his/her region.") }}
        </p> -->
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>
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
        </div>

        <div>
          <InputLabel for="item_id" value="Item" />
          <SelectInput
            :options="items"
            :item_name="`name_${i18n_locale}`"
            id="item_id"
            v-model="form.item_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.item_id" class="mt-2" />
        </div>

        <div>
          <InputLabel for="amount" value="Quantity" />

          <TextInput
            id="amount"
            type="number"
            class="mt-1 block w-full"
            v-model="form.amount"
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.amount" />
        </div>

        <div>
          <InputLabel for="transaction_type" value="Transaction Type" />
          <SelectInput
            :options="transaction_types"
            :item_name="`name_${i18n_locale}`"
            id="transaction_type"
            v-model="form.transaction_type"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.transaction_type" class="mt-2" />
        </div>

        <div>
          <InputLabel for="warehouse_stakeholder_id" value="Stakeholder" />
          <SelectInput
            :options="filteredStakeholders"
            :item_name="`name`"
            id="warehouse_stakeholder_id"
            v-model="form.warehouse_stakeholder_id"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.warehouse_stakeholder_id" class="mt-2" />
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
