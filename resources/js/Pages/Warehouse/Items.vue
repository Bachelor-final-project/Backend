<template>
  <Head :title="$t('Warehouse Details')" />
  <div class="dark:text-white">
    <Table
      title="Warehouses Items"
      :items="items"
      :headers="headers"
      :actions="actions"
      model="warehouse"
      :table_filters="warehouse_transactions_table_filters"
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

const { t: $t } = useI18n();
const actions = [
  {
    type: "href",
    icon: "exchange",
    icon_color: "#00cc66",
    funcName: () => { },
    model: "warehouse",
    tooltip: "show item transactions",
    route: "warehouse_transaction.index",  // Define the route here
    queryParams: {            // Define the query parameters here
      warehouse_id: 'warehouse_id',
      item_id: 'item_id',
    },
  }
  // {
  //   type: "btn",
  //   icon: "ban",
  //   icon_color: "#f5425a",
  //   funcName: _this.blocking,
  //   tooltip: "block user",
  // },
];



const props = defineProps({
  items: Array,
  headers: Array,
  name: String,
  warehouses: Array,
  products: Array
});

const warehouse_transactions_table_filters = [
  {
    name: "All Warehouses",
    model: "warehouse_id",
    options: [...props.warehouses],
  },
  {
    name: "All Items",
    model: "item_id",
    options: [{name : $t("All Items"), id:0}, ...props.products?? []],
  },
];

const user = useForm({
  name: "",
  email: "",
  password: "",
  type: "",
  status: "",
  job_title: "",
  is_active: "",
});

function save() {
  console.log(user);
}
</script>
