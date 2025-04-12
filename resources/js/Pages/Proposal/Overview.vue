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
      :selectable="true"
      :bulk-actions="[
  {
    name: 'copy',
    icon: 'copy',
    onClick: (selected) => {
      // Your logic, e.g. emit event or call API
      copySelected(selected);
    }
  },
]"
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
import { usePage } from "@inertiajs/vue3";

const page = usePage();

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


const copySelected = async (selected) => {
  let s = "الحملات الجديدة المطلوبة:\n\n";
  let last_s = "المجموع الكلي= ";
  let total = 0;
  selected.forEach((e, idx, array) => {
    console.log("idx: " + idx)
    total += parseInt(e.paid_amount);
    
    s += `*${Intl.NumberFormat().format(e.paid_amount)} ${e.currency_name}* ${e.title}`;
    last_s += `*${Intl.NumberFormat().format(e.paid_amount)} ${e.currency_name}* `;
    if(idx != array.length - 1){
      s += "+";
      last_s += "+";
    }else{
      last_s += ` = ${Intl.NumberFormat().format(total)} ${e.currency_name}`;
    }
    s += "\n";


  });
  const msg = t("copied to clipboard");
  const text = `${s}\n\n\n*************\n\n${last_s}`;
  console.log(msg);
  navigator.clipboard.writeText(text)
  .then(() => {
    console.log("Text copied to clipboard!");
    page.props.flash = {
      message: msg,
      type: "success"
    };
  })
  .catch(err => {
    console.error("Failed to copy text: ", err);
  });
}
</script>
