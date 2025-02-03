<template>
  <Head :title="$t('Items')" />
  <div class="dark:text-white">
    <Table
      title="Items"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="item"
      add_item_route="item.create"
      import_url="import-items"
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
    model: "item",
    tooltip: "delete item",
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "item",
    tooltip: "edit item",
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
});


function save() {
  console.log(user);
}
</script>
