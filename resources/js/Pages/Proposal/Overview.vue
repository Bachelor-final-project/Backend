<template>
  <Head :title="$t('Proposals Overview')" />
  <div class="dark:text-white">
    <Table
      title="Proposals Overview"
      model="proposal_overview"
      :actions="actions"
      :items="items"
      :headers="headers"
      :table_filters="table_filters"
      import_url="import-proposals-overview"
      :users="users"
    />
  </div>
</template>

<script setup>

import { Head } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";

const props = defineProps({
  items: Array,
  statuses: Array,
  entities: Array,
  currencies: Array,
  proposalTypes: Array,
  areas: Array,
  headers: Array,
  name: String,
  users: Array,
});
import { useI18n } from "vue-i18n";

const { t } = useI18n();

// const actions = 
// [
//   {
//     type: "href",
//     icon: "eye",
//     icon_color: "blue",
//     model: "proposal",
//     tooltip: "show proposal",
//     route: "proposal.show",  // Define the route here
//     includeId: true
//   }
// ];


function save() {
  console.log(user);
}
</script>
