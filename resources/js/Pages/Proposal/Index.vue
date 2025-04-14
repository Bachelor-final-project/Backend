<template>
  <Head :title="$t('Proposals')" />
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
      :users="users"
      :selectable="true"
      :bulk-actions="bulkActions"
            
    />
  </div>
</template>
<script setup>
import { Head, usePage } from "@inertiajs/vue3";
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

const page = usePage();
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
    searchable: true
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
    icon: "clone",
    icon_color: "orange",
    funcName: "cloning",
    model: "proposal",
    tooltip: "clone proposal",
    showFunc: function(item){
        return page.props.auth.user.type === 1;
    }
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

const bulkActions = [
        {
          name: 'copy',
          icon: 'copy',
          onClick: (selected) => {
            // Your logic, e.g. emit event or call API
            copySelected(selected);
          }
        },
        {
          name: 'copy documents',
          icon: 'copy',
          onClick: (selected) => {
            // Your logic, e.g. emit event or call API
            copyDocumentsSelected(selected);
          }
        }
      ];


const copySelected = async (selected) => {
    let s = "الحملات الجديدة المطلوبة:\n\n";
    let last_s = "المجموع الكلي= ";
    let total = 0;
    selected.forEach((e, idx, array) => {
            total += parseInt(e.paid_amount);
      
      s += `*${Intl.NumberFormat().format(e.paid_amount)} ${e.currency_name}* ${e.title}`;
      last_s += `*${Intl.NumberFormat().format(e.paid_amount)} ${e.currency_name}* `;
      if(idx != array.length - 1){
        s += " + ";
        last_s += "+";
      }else{
        last_s += ` = ${Intl.NumberFormat().format(total)} ${e.currency_name}`;
      }
      s += "\n";


    });
    const msg = t("copied to clipboard");
    const text = `${s}\n\n\n*************\n\n${last_s}`;
    
    navigator.clipboard.writeText(text)
    .then(() => {
      
      page.props.flash = {
        message: msg,
        type: "success"
      };
    })
    .catch(err => {
      console.error("Failed to copy text: ", err);
    });
}
const copyDocumentsSelected = async (selected) => {
    let s = "";
    let allTotal = 0;
    selected.forEach((e, idx, array) => {
      s += `*توثيق حملة ${e.title}* \n\n`;

      let total = 0;
      e.documents.forEach((document, idx, array) => {
        total += parseInt(document.amount);
        
        
        s += `${document.document_nickname || document.donor_name}\n`;
      });
      s += `من *${e.entity_name}*`;
      s += `\n\nالمجموع: *${Intl.NumberFormat().format(total)} ${e.currency_name}*`;
      s += `\n*************\n`;
      allTotal += total;

    });
    const last_s =  `المجموع: *${Intl.NumberFormat().format(allTotal)} ${selected[0].currency_name}*`;
    const msg = t("copied to clipboard");
    const text = `${s}\n${last_s}`;
    
    navigator.clipboard.writeText(text)
    .then(() => {
      
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
