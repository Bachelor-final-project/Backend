<template>
  <Head :title="$t('Donors')" />
  <div class="dark:text-white">
    <Table
      title="Donors"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="donor"
      add_item_route="donor.create"
      import_url="import-donors"
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
    funcName: "editing",
    tooltip: "edit donor",
  },
  {
    type: "btn",
    icon: "trash",
    icon_color: "#656565",
    funcName: "deleting",
    model: "donor",
    tooltip: "delete donor",
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
