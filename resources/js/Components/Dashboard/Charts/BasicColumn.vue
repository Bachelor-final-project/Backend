<template>
  <Card>
    <div class="flex flex-col self-center">
      <h2 class="text-2xl self-center mb-10 mt-5 font-bold dark:text-white">
        {{ $t(chart_title) }}
      </h2>
    </div>
    <div id="chart">
      <apexchart
        :key="render_count"
        type="bar"
        height="350"
        :options="chartOptions"
        :series="series"
      ></apexchart>
    </div>
  </Card>
</template>

<script>
import Card from "@/Components/ChartCard.vue";
export default {
  components: { Card },
  props: {
    color_theme: String,
    chart_title: {
      type: String,
      default: "title"
    },
    colors:{
      type: Array,
      default:[
          "#FF6384",
          "#36A2EB",
          "#FFCE56",
          "#4BC0C0",
          "#9966FF",
          "#FF9F40",
          "#00FA9A",
          "#DC143C",
          "#663399",
          "#00FF7F",
          "#FF69B4",
          "#FFA07A",
          "#008080",
          "#9400D3",
          "#BDB76B",
          "#9932CC",
          "#7FFFD4",
          "#FF4500",
          "#E9967A",
          "#FFB6C1",
          "#8B008B",
          "#00CED1",
          "#FFD700",
          "#FFC0CB",
          "#4682B4",
          "#8FBC8F",
          "#DB7093",
          "#32CD32",
          "#9932CC",
          "#B8860B",
          "#DA70D6",
          "#ADFF2F",
          "#FF00FF",
          "#D2B48C",
          "#8B0000",
          "#00FF00",
          "#4B0082",
          "#BC8F8F",
          "#00BFFF",
          "#7FFF00",
          "#CD853F",
          "#8A2BE2",
          "#F0E68C",
          "#BC8F8F",
          "#FFDAB9",
          "#2E8B57",
          "#FFA500",
          "#FF1493",
          "#1E90FF",
          "#008000",
          "#BA55D3",
          "#FF8C00",
          "#D2691E",
          "#0000CD",
          "#00BFFF",
          "#6B8E23",
        ],
    },
    data: Array,
  },
  data() {
    return {
      render_count: 1,
      series: [
        {
          data: this.$props.data.data,
        },
      ],
      chartOptions: {
        colors: this.$props.colors,
        theme: {
          mode: this.$props.color_theme,
        },
        chart: {
          height: 350,
          type: "bar",
          events: {
            click: function (chart, w, e) {},
          },
        },
        plotOptions: {
          bar: {
            columnWidth: "45%",
            distributed: true,
          },
        },

        xaxis: {
          categories: this.data.categories,
          labels: {
            show: false,
            enabled: true,
            rotate: 0,
            offsetX: 10,
            fontSize: "5px",
          },
        },
        legend: {
          show: true,
          position: "bottom",
          labels: {
            useSeriesColors: false,
          },
          onItemClick: {
            toggleDataSeries: true,
          },
          onItemHover: {
            highlightDataSeries: true,
          },
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
