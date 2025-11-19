<template>
  <Head :title="$t('Transactins')" />
  <div class="dark:text-white">
    <Table
      title="Warehouse Transactions"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="warehouse_transaction"
      :table_filters="warehouse_transactions_table_filters"
      refresh_only="items"
      add_item_route="warehouse_transaction.create"
      import_url="import-warehouses"
    />
  </div>
</template>
<script setup>
import { router } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Table from "@/Components/Table.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
  items: Array,
  headers: Array,
  warehouses: Array,
  products: Array,
  name: String,
});

const user = useForm({
  name: "",
  email: "",
  password: "",
  type: "",
  status: "",
  job_title: "",
  is_active: "",
});

const warehouse_transactions_table_filters = [
  {
    name: "All Warehouses",
    model: "warehouse_id",
    options: [{ id: 0, name: t("All Warehouses") }, ...props.warehouses],
  },
  {
    name: "All Items",
    model: "item_id",
    options: [{ id: 0, name: t("All Items") }, ...props.products],
  },
];

const actions = [
  {
    type: "btn",
    icon: "trash",
    icon_color: "#656565",
    funcName: "deleting",
    model: "warehouse_transaction",
    tooltip: "delete warehouse_transaction",
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "warehouse_transaction",
    tooltip: "edit warehouse_transaction",
  },
  // {
  //   type: "btn",
  //   icon: "ban",
  //   icon_color: "#f5425a",
  //   funcName: _this.blocking,
  //   tooltip: "block user",
  // },
];



function save() {
  console.log(user);
}
</script>
