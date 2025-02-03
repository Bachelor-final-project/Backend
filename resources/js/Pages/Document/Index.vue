<template>
  <Head :title="$t('Documents')" />
  <div class="dark:text-white">
    <Table
      title="Documents"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="document"
      :table_filters="table_filters"
      add_item_route="document.create"
    />
  </div>
</template>
<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Table from "@/Components/Table.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useI18n } from "vue-i18n";
import { computed } from "vue";
const { t } = useI18n();
const page = usePage();
const user = computed(() => page.props.auth.user);
// console.log(page.props.auth.user);
// console.log(user.value);
const actions = [
  {
    type: "btn",
    icon: "trash",
    icon_color: "#656565",
    funcName: "deleting",
    model: "document",
    tooltip: "delete document",
    showFunc: function(item){
        return [1, 5].includes(user.value.type);
    }
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "document",
    tooltip: "edit document",
    showFunc: function(item){
        return [1, 5].includes(user.value.type);
    }
  },
  {
    type: "btn",
    icon: "upload",
    icon_color: "purple",
    funcName: "uploadingDocumentFile",
    model: "document",
    tooltip: "upload document file",
    showFunc: function(item){
        return [1, 5].includes(user.value.type);
    }
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
  currencies: Array,
  proposals: Array,
  donors: Array,
  name: String,
});

const table_filters = [
  {
    name: t("currency"),
    model: "currency_id_filter",
    options: [{ id: 0, name: t("All currencies") }, ...props.currencies],
  },
  {
    name: t("proposals"),
    model: "proposal_id_filter",
    options: [{ id: 0, name: t("All Proposals") }, ...props.proposals],
    item_name: 'title',
    searchable: true
  },
  {
    name: t("donors"),
    model: "donor_id_filter",
    options: [{ id: 0, name: t("All Donors") }, ...props.donors],
    searchable: true
  },
];

function save() {
  console.log(user);
}
</script>
