<template>
  <Head :title="$t('Proposal')" />
  <div class="dark:text-white">
    <Table
      title="Proposal"
      model="proposal"
      :actions="actions"
      :items="items"
      :headers="headers"
      :table_filters="table_filters"
      import_url="import-proposals"
      add_item_route="proposal.create"
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
});
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const table_filters = [
  {
    name: t("Status"),
    model: "status_filter",
    options: [{ id: 0, name: t("All Statuses") }, ...props.statuses],
  },
  {
    name: t("entity"),
    model: "entity_id_filter",
    options: [{ id: 0, name: t("All Entities") }, ...props.entities],
  },
  {
    name: t("currency"),
    model: "currency_id_filter",
    options: [{ id: 0, name: t("All currencies") }, ...props.currencies],
  },
  {
    name: t("proposal type"),
    model: "proposal_type_id_filter",
    options: [{ id: 0, name: t("All proposalTypes") }, ...props.proposalTypes],
  },
  {
    name: t("area"),
    model: "area_id_filter",
    options: [{ id: 0, name: t("All areas") }, ...props.areas],
  },
  {
    name: t("title"),
    model: "title_filter",
    type: "text"
  },
  {
    name: t("execution date"),
    model: "execution_date_filter",
    type: "date"
  },
];
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
  {
    type: "href",
    icon: "eye",
    icon_color: "blue",
    model: "proposal",
    tooltip: "show proposal",
    route: "proposal.show",  // Define the route here
    includeId: true
  }
];


function save() {
  console.log(user);
}
</script>
