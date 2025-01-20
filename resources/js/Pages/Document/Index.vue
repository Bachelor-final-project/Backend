<template>
  <Head :title="$t('Users')" />
  <div class="dark:text-white">
    <Table
      title="Document"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="document"
      add_item_route="document.create"
      add_file_input="add"
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
    model: "beneficiary",
    tooltip: "delete beneficiary",
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "beneficiary",
    tooltip: "edit beneficiary",
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
