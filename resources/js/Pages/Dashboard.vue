<script setup>
import { onMounted, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import BasicColumn from "@/Components/Dashboard/Charts/BasicColumn.vue";
import BasicStackedBars from "@/Components/Dashboard/Charts/BasicStackedBars.vue";
import BasicSparkLines from "@/Components/Dashboard/Charts/BasicSparkLines.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { useI18n } from "vue-i18n";

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
});


</script>

<template>
  <Head :title="$t('Dashboard')" />
  <div>
    <AdminLayout>
      <div>
        <div class="grid grid-cols-3 gap-10">
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
        <BasicStackedBars
          :data="donatingStatusProposalsStackedGroup"
          :color_theme="color_theme"
          chart_prop_name="stacked_group_chart"
          :chart_title="$t('Donating Status Proposals')"
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
</style>
