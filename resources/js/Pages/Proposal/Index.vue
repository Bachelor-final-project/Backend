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

const actions = 
[
  {
    type: "btn",
    icon: "trash",
    icon_color: "red",
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
  {
    type: "btn",
    icon: "circle-check",
    icon_color: "green",
    funcName: "completingDonatingStatus",
    model: "proposal",
    tooltip: "complete donating status proposal",
    showFunc: function(item){
        return item.can_complete_donating_status;
    }
  },
  {
    type: "btn",
    icon: "circle-check",
    icon_color: "red",
    funcName: "completingExecutionStatus",
    model: "proposal",
    tooltip: "complete execution status proposal",
    showFunc: function(item){
        return item.can_complete_execution_status;
    }
  },
  {
    type: "btn",
    icon: "circle-check",
    icon_color: "#3b82f6",
    funcName: "completingArchivingStatus",
    model: "proposal",
    tooltip: "complete archiving status proposal",
    showFunc: function(item){
        return item.can_complete_archiving_status;
    }
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
