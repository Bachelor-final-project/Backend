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
    icon: "block",
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
  headers: Array,
  name: String,
});

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
