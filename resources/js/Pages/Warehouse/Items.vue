<template>
  <Head :title="$t('Warehouse Details')" />
  <div class="dark:text-white">
    <Table
      title="Warehouses Items"
      :items="items"
      :headers="headers"
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

const actions = [
  {
    type: "btn",
    icon: "trash",
    icon_color: "#656565",
    funcName: "deleting",
    model: "warehouse",
    tooltip: "delete warehouse",
  },
  {
    type: "btn",
    icon: "ban",
    icon_color: "#f5425a",
    funcName: "blocking",
    model: "warehouse",
    tooltip: "close warehouse",
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "warehouse",
    tooltip: "edit warehouse",
  },
  {
    type: "href",
    icon: "items",
    icon_color: "green",
    funcName: () => {},
    model: "warehouse",
    tooltip: "show warehouse items",
    route: route('warehouse.items')
  },
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
  warehouses: Array
});

const warehouse_transactions_table_filters = [
  {
    name: "All Warehouses",
    model: "warehouse_id",
    options: [...props.warehouses],
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
