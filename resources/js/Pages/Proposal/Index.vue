<template>
  <Head :title="$t('Proposal')" />
  <div class="dark:text-white">
    <Table
      title="Proposal"
      model="proposal"
      :actions="actions"
      :items="items"
      :headers="headers"
      import_url="import-proposals"
      add_item_route="proposal.create"

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
    model: "proposal",
    tooltip: "delete proposal",
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "proposal",
    tooltip: "edit proposal",
  },
];

const props = defineProps({
  items: Array,
  headers: Array,
  name: String,
});

// const user = useForm({
//   name: "",
//   email: "",
//   password: "",
//   type: "",
//   status: "",
//   job_title: "",
//   is_active: "",
// });

// function save() {
//   console.log(user);
// }
</script>
