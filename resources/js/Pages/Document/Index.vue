<template>
  <Head :title="$t('Users')" />
  <div class="dark:text-white">
    <Table
      title="Document"
      :actions="actions"
      :items="items"
      :headers="headers"
      model="document"
      :table_filters="table_filters"
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
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const actions = [
  {
    type: "btn",
    icon: "trash",
    icon_color: "#656565",
    funcName: "deleting",
    model: "document",
    tooltip: "delete document",
  },
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "editing",
    model: "document",
    tooltip: "edit document",
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
  name: String,
});

const table_filters = [
  {
    name: t("currency"),
    model: "currency_id_filter",
    options: [{ id: 0, name: t("All currencies") }, ...props.currencies],
  },
];

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
