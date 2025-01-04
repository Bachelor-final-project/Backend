<template>
  <Card class="p-5">
    <div class="grid grid-cols-6 mb-5">
      <div class="flex col-span-6 md:col-span-2 pe-32 flex-col">
        <VueDatePicker
          class="w-56"
          v-model="group_chart_year_interval"
          :start-date="group_chart_year_interval"
          range
          :clearable="false"
          year-picker
          @update:model-value="dateSelected"
        />
      </div>
      <div class="col-span-6 md:col-span-2">
        <div class="flex flex-col items-center">
          <h2 class="text-2xl top-0 font-bold dark:text-white">
            {{ $t("Total reports by type") }}
          </h2>
          <!-- <h4 class="mb-10 text-sm text-gray-500">
            {{ $t("The most four famous types") }}
          </h4> -->
        </div>
      </div>
    </div>
    <div :key="render_count" id="chart" dir="ltr">
      <apexchart
        type="bar"
        :height="Math.max(data.groups.length * 100, 300)"
        :options="chartOptions"
        :series="series"
      ></apexchart>
    </div>
  </Card>
</template>

<script>
import Card from "@/Components/ChartCard.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import { ref } from "vue";
import "@vuepic/vue-datepicker/dist/main.css";
import { router } from "@inertiajs/vue3";
import colors from "tailwindcss/colors";
export default {
  components: { Card, VueDatePicker },
  props: {
    color_theme: String,
    data: Array,
    show_filter: {
      type: Boolean,
      default: false,
    },
    chart_prop_name: {
      type: String,
      default: "group_chart",
    },
  },
  data() {
    return {
      group_chart_year_interval: [1900, new Date().getFullYear()],
      // group_chart_year_from: ref(new Date().getFullYear()),
      // group_chart_year_to: ref(new Date().getFullYear()),
      render_count: 1,
      series: this.$props.data.chart_data,

      // {
      //   data: [44, 55, 41, 64, 22, 43, 21],
      // },
      // {
      //   data: [53, 32, 33, 52, 13, 44, 32],
      // },
      // {
      //   data: [26, 24, 10, 85, 27, 66, 16],
      // },
      // {
      //   data: [33, 24, 17, 62, 25, 50, 12],
      // },

      // selectDateFrom: () => {
      //   // console.log("Hello, Close");
      //   // console.log(this.$refs.datepicker);
      //   if (this.$refs.datepicker_from) {
      //     this.$refs.datepicker_from.selectDate();
      //     router.reload({
      //       only: ["group_chart"],
      //       data: { group_chart_year: this.group_chart_year },
      //       preserveState: true,
      //       preserveScroll: true,
      //     });
      //   }
      // },
      // selectDateTo: () => {
      //   // console.log("Hello, Close");
      //   // console.log(this.$refs.datepicker);
      //   if (this.$refs.datepicker_to) {
      //     this.$refs.datepicker_to.selectDate();
      //     router.reload({
      //       only: ["group_chart"],
      //       data: { group_chart_year: this.group_chart_year },
      //       preserveState: true,
      //       preserveScroll: true,
      //     });
      //   }
      // },
      dateSelected: () => {
        this.$nextTick();
        router.reload({
          only: [this.$props.chart_prop_name],
          data: { group_chart_year_interval: this.group_chart_year_interval },
          preserveState: true,
          preserveScroll: true,
        });
        // router.replace({ query: {} });
        // router.replace({ query: {} });
        // router.activeVisit.url.search = "";
        // router.page.url = "/administration/management/";
        // console.log(router);
        // this.$router.replace({ query: {} });
      },
      chartOptions: {
        legend: {
          show: true,
          showForSingleSeries: true,
          customLegendItems: this.$props.data.regions,
          labels: {
            colors: ["#fff", "#fff", "#fff", "#fff"],
            // useSeriesColors: false,
          },
          markers: {
            width: 12,
            height: 12,
            strokeWidth: 10,
            strokeColor: "#f00",
            fillColors: ["#349c55", "#ffbb00", "#1668bd"],
            radius: 12,
            customHTML: function () {
              return '<span dir="rtl" lang="ar" class="custom-marker"><i class="fas fa-chart-pie"></i></span>';
            },
            onClick: undefined,
            offsetX: 0,
            offsetY: 0,
          },
          onItemClick: {
            toggleDataSeries: true,
          },
          onItemHover: {
            highlightDataSeries: true,
          },
        },
        colors: ["#349c55", "#ffbb00", "#1668bd"],
        chart: {
          type: "bar",
          height: "auto",
        },
        plotOptions: {
          bar: {
            horizontal: true,
            // columnWidth: "100%",
            barHeight: "75%",
            distributed: false,
            dataLabels: {
              position: "top",
            },
          },
        },
        dataLabels: {
          enabled: true,
          offsetX: -6,
          style: {
            fontSize: "12px",
            colors: ["#fff"],
          },
        },
        stroke: {
          show: true,
          width: 1,
          colors: ["#fff"],
        },
        tooltip: {
          shared: true,
          intersect: false,
        },
        yaxis: {
          labels: {
            style: {
              fontSize: "12px",
              fontWeight: 900,
            },
          },
        },
        xaxis: {
          categories: this.$props.data.groups.map((e) => e.title),
          group: {
            style: {
              fontSize: "19px",
              fontWeight: 1000,
              colors: ["#33b2df", "#13d8aa", "#f9a3a4"],
            },
            // groups: this.$props.data.groups,
          },

          labels: {
            style: {
              fontSize: "16px",
            },
            rotate: 0,
            wrap: true,
          },
        },
        theme: {
          mode: this.$props.color_theme,
          palette: "palette1",
          monochrome: {
            enabled: false,
          },
        },

        // legend: {
        //   show: true,
        //   showForSingleSeries: true,
        //   customLegendItems: this.$props.data.groups.map((e) => e.title),
        //   labels: {
        //     colors: ["#fff", "#fff", "#fff", "#fff"],
        //     // useSeriesColors: false,
        //   },
        //   markers: {
        //     width: 12,
        //     height: 12,
        //     strokeWidth: 10,
        //     strokeColor: "#f00",
        //     fillColors: ["#33b2df", "#13d8aa", "#f9a3a4", "#69d2e7"],
        //     radius: 12,
        //     customHTML: function () {
        //       return '<span dir="rtl" lang="ar" class="custom-marker"><i class="fas fa-chart-pie"></i></span>';
        //     },
        //     onClick: undefined,
        //     offsetX: 0,
        //     offsetY: 0,
        //   },
        //   onItemClick: {
        //     toggleDataSeries: true,
        //   },
        //   onItemHover: {
        //     highlightDataSeries: true,
        //   },
        // },
        // // labels: ["value 1", "value 2", "value 3", "value 4"],
        // plotOptions: {
        //   bar: {
        //     distributed: true,
        //     columnWidth: "60%",
        //   },
        // },
        // colors: [
        //   //           "#A5978B",
        //   // "#2b908f",
        //   // "#90ee7e",
        //   // "#f48024",
        //   "#33b2df",
        //   "#33b2df",
        //   "#33b2df",
        //   "#13d8aa",
        //   "#13d8aa",
        //   "#13d8aa",
        //   "#f9a3a4",
        //   "#f9a3a4",
        //   "#f9a3a4",
        //   "#69d2e7",
        //   "#69d2e7",
        //   "#69d2e7",
        // ],
        // theme: {
        //   mode: this.$props.color_theme,
        //   palette: "palette1",
        //   monochrome: {
        //     enabled: false,
        //   },
        // },
        // // colors: ["#F44336", "#E91E63", "#9C27B0"],
        // chart: {
        //   type: "bar",
        //   height: 500,
        // },
        // xaxis: {
        //   type: "category",

        //   group: {
        //     style: {
        //       fontSize: "11px",
        //       fontWeight: 1000,
        //       colors: ["#33b2df", "#13d8aa", "#f9a3a4", "#69d2e7"],
        //     },
        //     // groups: this.$props.data.groups,
        //   },
        // },
      },
    };
  },
  watch: {
    color_theme: {
      handler(val) {
        this.chartOptions.theme.mode = val;
        this.render_count++;
      },
      immediate: true,
    },
    data: {
      deep: true,
      immediate: true,
      handler(data) {
        // console.log("Hello Data From Watcher");
        // // console.log("WatcherData");
        // console.log(data.data);
        this.series = data.chart_data;
        this.chartOptions.legend.customLegendItems = data.regions;
        this.chartOptions.xaxis.categories = data.groups.map((e) => e.title);
        this.render_count++;
      },
    },
  },
};
</script>

<style>
.minus-margin {
  margin-top: -15px;
}
.margin-right {
  padding-right: 5px;
}
.padding-top {
  padding-top: 10px;
}
.space-table-rows {
  border-collapse: separate;
  border-spacing: 7px;
}
[dir="rtl"] .apexcharts-legend {
  flex-direction: row-reverse;
}

.apexcharts-xaxis-labels {
  white-space: normal !important;
  max-width: 100px;
}
</style>
