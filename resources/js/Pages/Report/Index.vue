<script setup>
import { onMounted, ref, watch } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import BasicSparkLines from "@/Components/Dashboard/Charts/BasicSparkLines.vue";
import TextInput from "@/Components/TextInput.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const page = usePage();

const props = defineProps({
  approvedDonationLast30Days: Array,
  totalApprovedDonationLast30Days: Array,
  completedProposalsLast30Days: Array,
  filters: Object,
});

let color_theme = ref(localStorage.getItem("color-theme"));
const fromDate = ref(props.filters?.from_date || "");
const toDate = ref(props.filters?.to_date || "");

onMounted(() => {
  window.addEventListener("color-theme-localstorage-changed", (event) => {
    color_theme.value = event.detail.storage;
  });
});

watch([fromDate, toDate], () => {
  router.get(route("report.index"), {
    from_date: fromDate.value,
    to_date: toDate.value,
  }, {
    preserveState: false,
  });
});

const copyReport = () => {
  const donationsTotal = props.approvedDonationLast30Days?.data?.reduce((a, b) => a + b, 0) || 0;
  const proposalsTotal = props.completedProposalsLast30Days?.data?.reduce((a, b) => a + b, 0) || 0;
  const totalDonationsAmount = props.totalApprovedDonationLast30Days?.data?.reduce((a, b) => a + b, 0) || 0;

  const message = `${t('Reports')}

${t('From Date')}: ${fromDate.value || t('Not specified')}
${t('To Date')}: ${toDate.value || t('Not specified')}

${t('Donations')}: ${donationsTotal}
${t('Completed proposals')}: ${proposalsTotal}
${t('Total Donations')}: ${Intl.NumberFormat().format(totalDonationsAmount)}`;

  navigator.clipboard.writeText(message)
    .then(() => {
      page.props.flash = {
        message: t("copied to clipboard"),
        type: "success"
      };
    })
    .catch(err => {
      console.error("Failed to copy text: ", err);
    });
};
</script>

<template>
  <Head :title="$t('Reports')" />
  <div class="dark:text-white">
    <div class="mb-6 flex gap-4 items-end justify-between">
      <div class="flex gap-4">
        <div>
          <label class="block text-sm font-medium mb-2">{{ $t('From Date') }}</label>
          <TextInput
            v-model="fromDate"
            type="date"
          />
        </div>
        <div>
          <label class="block text-sm font-medium mb-2">{{ $t('To Date') }}</label>
          <TextInput
            v-model="toDate"
            type="date"
          />
        </div>
      </div>
      <button
        @click="copyReport"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2"
      >
        <i class="fa fa-copy"></i>
        {{ $t('copy') }}
      </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
      <BasicSparkLines
        v-if="approvedDonationLast30Days"
        :data="approvedDonationLast30Days"
        :color_theme="color_theme"
        :show_filter="true"
        :subtitle="$t('Donations')"
      />
      <BasicSparkLines
        v-if="completedProposalsLast30Days"
        :data="completedProposalsLast30Days"
        :color_theme="color_theme"
        :show_filter="true"
        :subtitle="$t('Completed proposals')"
      />
      <BasicSparkLines
        v-if="totalApprovedDonationLast30Days"
        :data="totalApprovedDonationLast30Days"
        :color_theme="color_theme"
        :show_filter="true"
        :subtitle="$t('Total Donations')"
      />
    </div>
  </div>
</template>
