<template>
  <Card>
    <div class="flex flex-col self-center">
      <h2 class="text-2xl self-center mb-10 mt-5 font-bold dark:text-white">
        {{ $t("Total reports in") + " " + page.props.auth.user.region_name }}
      </h2>
    </div>
    <div :key="render_count" id="chart">
      <apexchart
        type="bar"
        height="350"
        :options="chartOptions"
        :series="data"
      ></apexchart>
    </div>
  </Card>
</template>

<script>
import Card from "@/Components/ChartCard.vue";
import { handler } from "tailwindcss-rtl";
import { usePage } from "@inertiajs/vue3";

export default {
  components: { Card },
  props: {
    color_theme: String,
    data: Array,
  },
  data() {
    return {
      render_count: 1,
      page: usePage(),
      // series: [
      //   {
      //     name: "closed",
      //     data: [44, 55, 41, 67, 22, 43, 44, 55, 41, 67, 22, 43],
      //   },
      //   {
      //     name: "Opend",
      //     data: [13, 23, 20, 8, 13, 27, 13, 23, 20, 8, 13, 27],
      //   },
      // ],
      chartOptions: {
        theme: {
          mode: this.$props.color_theme,
          palette: "palette1",
          monochrome: {
            enabled: false,
          },
        },
        dataLabels: {
          enabled: false,
        },
        chart: {
          type: "bar",
          height: 350,
          stacked: true,
          toolbar: {
            show: true,
          },
          zoom: {
            enabled: true,
          },
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              legend: {
                position: "bottom",
                offsetX: -10,
                offsetY: 0,
              },
            },
          },
        ],
        plotOptions: {
          bar: {
            columnWidth: "45%",
            horizontal: false,
            borderRadius: 0,
          },
        },
        xaxis: {
          categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
        },
        legend: {
          position: "top",
        },
        colors: ["#fdbd28", "#e3e3e3", "#f00909", "#66262680", "#000", "#000"],
        fill: {
          opacity: 1,
        },
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
  },
};
</script>

<style></style>
