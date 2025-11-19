<template>
  <Head :title="$t('Warehouse Stakeholders')" />
  <div class="dark:text-white">
    <Table
      title="Warehouse Stakeholders"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="warehouse_stakeholder"
      add_item_route="warehouse_stakeholder.create"
    />
  </div>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";

const actions = [
  {
    type: "btn",
    icon: "trash",
    icon_color: "#656565",
    funcName: "deleting",
    model: "warehouse_stakeholder",
    tooltip: "delete warehouse stakeholder",
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "warehouse_stakeholder",
    tooltip: "edit warehouse stakeholder",
  },
];

const props = defineProps({
  items: Array,
  headers: Array,
});
</script>