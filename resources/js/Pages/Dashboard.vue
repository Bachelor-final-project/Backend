<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import BasicColumn from "@/Components/Dashboard/Charts/BasicColumn.vue";
import BasicStackedBars from "@/Components/Dashboard/Charts/BasicStackedBars.vue";
import BasicSparkLines from "@/Components/Dashboard/Charts/BasicSparkLines.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useI18n } from "vue-i18n";

import Table from "@/Components/Table.vue";
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
    tr.querySelector('td:nth-child(1)').style.backgroundColor = colors[counter];
    tr.querySelector('td:nth-child(2)').style.backgroundColor = colors[counter++];
    tr.querySelector('td:nth-child(3)').style.backgroundColor = '#c9daf8';
    tr.querySelector('td:nth-child(4)').style.backgroundColor = '#fff2cc';
    // tr.querySelector('td:nth-child(3) div').classList.add("bg-[#fff2cc]-100", "!text-[#fff2cc]-800", "me-2", "px-2.5", "py-0.5", "rounded-full", "dark:bg-gray-700", "dark:text-blue-400", "border", "border-[#fff2cc]-400");
    console.log(tr);
  });
});


</script>

<template>
  <Head :title="$t('Dashboard')" />
  <div>
    <AdminLayout>
      <div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
          <BasicSparkLines
            :data="approvedDonationLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            chart_title="Total Proposals by Status"
            :subtitle="$t('Donations last 30 days')"
          />
          <BasicSparkLines
            :data="completedProposalsLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            chart_title="Total Proposals by Status"
            
            :subtitle="$t('Completed proposals last 30 days')"
          />
          <BasicSparkLines
            :data="benefitsLast30Days"
            :color_theme="color_theme"
            :show_filter="true"
            :subtitle="$t('Total Benefits last 30 days')"
          />

        </div>
        <div class="dark:text-white" id="proposals_overview">
          <Table
            title="Proposals Overview"
            model="proposal_overview"
            class=""
            :actions="actions"
            :items="proposals_overview"
            :headers="proposals_overview_headers"
            import_url="import-proposals-overview"
            
          />
        </div>

        <BasicStackedBars
          :data="donatingStatusProposalsStackedGroup"
          :color_theme="color_theme"
          chart_prop_name="stacked_group_chart"
          :chart_title="$t('Donating Status Proposals')"
          :chart_tips="$t('Hold down the Ctrl key and click on a group to go to the donations page with the selected status.')"
        />
        <BasicColumn
          :data="proposalsByStatus"
          :color_theme="color_theme"
          chart_title="Total Proposals by Status"
          :colors="['grey', '#2ff22f','red',   '#3b82f6']"
        />
        <BasicColumn
          :data="proposalsByTypes"
          :color_theme="color_theme"
          chart_title="Total Proposals by Types"
        />
        <BasicColumn
          :data="donationsByStatues"
          :color_theme="color_theme"
          chart_title="Total Donations by Statues"
        />
        <BasicColumn
          :data="documentsByStatues"
          :color_theme="color_theme"
          chart_title="Total Documents by Statues"
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
#proposals_overview table td:first-child {
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
