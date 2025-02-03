<template>
  <Head :title="$t('Donations')" />
  <div class="dark:text-white">
    <Table
      title="Donations"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="donation"
      add_item_route="donation.create"
      :table_filters="table_filters"
      import_url="import-donations"
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

const { t } = useI18n();

const actions = [
 
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "edit",
    tooltip: "edit donation",
    showFunc: function(item){
        return item.status == 0;
    }
  },
  {
    type: "btn",
    icon: "ban",
    icon_color: "red",
    funcName: "rejectDonation",
    model: "donation",
    tooltip: "reject donation",
    showFunc: function(item){
        return item.status == 0;
    }
  },
  {
    type: "btn",
    icon: "check",
    icon_color: "#2eb32e",
    funcName: "approveDonation",
    model: "donation",
    tooltip: "approve donation",
    showFunc: function(item){
        return item.status == 0;
    }
  },
];

const props = defineProps({
  items: Array,
  currencies: Array,
  proposals: Array,
  donors: Array,
  statuses: Array,
  headers: Array,
  name: String,
});

const table_filters = [
  {
    name: t("currency"),
    model: "currency_id_filter",
    options: [{ id: 0, name: t("All currencies") }, ...props.currencies],
  },
  {
    name: t("status"),
    model: "status_filter",
    options: [{ id: -1, name: t("All statuses") }, ...props.statuses],
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
