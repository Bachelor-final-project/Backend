<script setup>
import { onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import BasicColumn from "@/Components/Dashboard/Charts/BasicColumn.vue";
import BasicStackedBars from "@/Components/Dashboard/Charts/BasicStackedBars.vue";
import BasicSparkLines from "@/Components/Dashboard/Charts/BasicSparkLines.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useI18n } from "vue-i18n";

import Table from "@/Components/Table.vue";
const page = usePage();
const { t } = useI18n();

const props = defineProps({
  proposalsByStatus: Array,
  proposalsByTypes: Array,
  donationsByStatues: Array,
  documentsByStatues: Array,
  approvedDonationLast30Days: Array,
  completedProposalsLast30Days: Array,
  benefitsLast30Days: Array,
  donatingStatusProposalsStackedGroup: Array,
  proposals_overview: Array,
  proposals_overview_headers: Array,
  allTransactionsLast30Days: Array,
  inboundTransactionsLast30Days: Array,
  outboundTransactionsLast30Days: Array,
  transactionsByType: Array,
  transactionsByWarehouse: Array,
});

let color_theme = ref(localStorage.getItem("color-theme"));

onMounted(() => {
  window.addEventListener("color-theme-localstorage-changed", (event) => {
    color_theme.value = event.detail.storage;
  });
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });


  // const colors = ['#EAD1DC', '#EA9999', '#B6D7A8', '#E69138', '#B7B7B7', '#B4A7D6', '#93C47D', '#76A5AF', '#D9D2E9', '#9FC5E8', '#F9CB9C', '#45818E', '#FCE5CD'];
  const colors = ['#B7B7B7', '#FCE5CD', '#9FC5E8', '#B4A7D6', '#93C47D', '#B6D7A8', '#76A5AF', '#EAD1DC', '#F9CB9C', '#EA9999', '#26A69A', '#E69138', '#D9D2E9', '#A1C7EB', '#B6A9D9', '#B8DAAA', '#FFD2A1', '#B9B9B9', '#79ABB5', '#ED9B9B'];
  let counter = 0;
  document.querySelectorAll('#proposals_overview table tbody tr').forEach((tr) => {
    const td1 = tr.querySelector('td:nth-child(1)');
    const td2 = tr.querySelector('td:nth-child(2)');
    const td3 = tr.querySelector('td:nth-child(3)');
    const td4 = tr.querySelector('td:nth-child(4)');
    const td5 = tr.querySelector('td:nth-child(5)');
    
    if (td1) td1.style.backgroundColor = colors[counter];
    if (td2) td2.style.backgroundColor = colors[counter];
    if (td3) td3.style.backgroundColor = colors[counter++];
    if (td4) td4.style.backgroundColor = '#c9daf8';
    if (td5) td5.style.backgroundColor = '#fff2cc';
  });
});




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

<template>
  <Head :title="$t('Dashboard')" />
  <div>
    <AdminLayout>
      <div id="EntityDirectorStatistics">
        <div v-if="approvedDonationLast30Days || completedProposalsLast30Days || benefitsLast30Days || allTransactionsLast30Days || inboundTransactionsLast30Days || outboundTransactionsLast30Days" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
          <BasicSparkLines
            v-if="approvedDonationLast30Days"
            :data="approvedDonationLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            chart_title="Total Proposals by Status"
            :subtitle="$t('Donations last 30 days')"
          />
          <BasicSparkLines
            v-if="completedProposalsLast30Days"
            :data="completedProposalsLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            chart_title="Total Proposals by Status"
            :subtitle="$t('Completed proposals last 30 days')"
          />
          <BasicSparkLines
            v-if="benefitsLast30Days"
            :data="benefitsLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            :subtitle="$t('Total Benefits last 30 days')"
          />
          <BasicSparkLines
            v-if="allTransactionsLast30Days"
            :data="allTransactionsLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            :subtitle="$t('All Transactions last 30 days')"
          />
          <BasicSparkLines
            v-if="inboundTransactionsLast30Days"
            :data="inboundTransactionsLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            :subtitle="$t('Inbound Transactions last 30 days')"
          />
          <BasicSparkLines
            v-if="outboundTransactionsLast30Days"
            :data="outboundTransactionsLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            :subtitle="$t('Outbound Transactions last 30 days')"
          />
        </div>
        <div v-if="proposals_overview && proposals_overview_headers" class="dark:text-white" id="proposals_overview">
          <Table
            title="Proposals Overview"
            model="proposal_overview"
            class=""
            :actions="actions"
            :items="proposals_overview"
            :headers="proposals_overview_headers"
            import_url="import-proposals-overview"
            :selectable="true"
            :bulk-actions="[
              {
                name: 'copy',
                icon: 'copy',
                onClick: (selected) => {
                  // Your logic, e.g. emit event or call API
                  copySelected(selected);
                }
              }
            ]"
            
          />
        </div>

        <BasicStackedBars
          v-if="donatingStatusProposalsStackedGroup"
          :data="donatingStatusProposalsStackedGroup"
          :color_theme="color_theme"
          chart_prop_name="stacked_group_chart"
          :chart_title="$t('Donating Status Proposals')"
          :chart_tips="$t('Hold down the Ctrl key and click on a group to go to the donations page with the selected status.')"
        />
        <BasicColumn
          v-if="proposalsByStatus"
          :data="proposalsByStatus"
          :color_theme="color_theme"
          chart_title="Total Proposals by Status"
          :colors="['grey', '#2ff22f','red',   '#3b82f6']"
        />
        <BasicColumn
          v-if="proposalsByTypes"
          :data="proposalsByTypes"
          :color_theme="color_theme"
          chart_title="Total Proposals by Types"
        />
        <BasicColumn
          v-if="donationsByStatues"
          :data="donationsByStatues"
          :color_theme="color_theme"
          chart_title="Total Donations by Statues"
        />
        <BasicColumn
          v-if="documentsByStatues"
          :data="documentsByStatues"
          :color_theme="color_theme"
          chart_title="Total Documents by Statues"
        />
        <BasicColumn
          v-if="transactionsByType"
          :data="transactionsByType"
          :color_theme="color_theme"
          chart_title="Transactions by Type"
        />
        <BasicColumn
          v-if="transactionsByWarehouse"
          :data="transactionsByWarehouse"
          :color_theme="color_theme"
          chart_title="Transactions by Warehouse"
        />
      </div>
    </AdminLayout>
    
  </div>
</template>

<style>
#proposals_overview table thead tr{
  background-color: #6aa84f ;
  color: #fff ;
}
#proposals_overview table,
#proposals_overview table td,
#proposals_overview table th,
#proposals_overview table tr
{
  border-color: #333;
  border-width: 1px;
}
/*#proposals_overview table td:first-child*/
#proposals_overview table td:nth-child(2)
 {
  width: 100%;
  /* white-space: nowrap; */
}
#proposals_overview table td {
  text-align: center;
  font-size: large;
  font-family: Arial, Helvetica, sans-serif;
  /* white-space: nowrap; */
}
</style>
