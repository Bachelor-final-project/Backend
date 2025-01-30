<template>
  <Head :title="$t('Warehouses')" />
  <div class="dark:text-white">
    <Table
      title="Warehouses"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="warehouse"
      add_item_route="warehouse.create"
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
    icon: "basket-shopping",
    icon_color: "#00cc66",
    funcName: () => { },
    model: "warehouse",
    tooltip: "show warehouse items",
    route: "warehouse.items",  // Define the route here
    queryParams: {            // Define the query parameters here
      warehouse_id: 'id',
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
});


function save() {
  console.log(user);
}
</script>
