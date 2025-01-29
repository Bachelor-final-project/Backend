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
       height="500" 
       :options="chartOptions"
        :series="series"></apexchart>
    </div>
  </Card>
</template>

<script>
import Card from "@/Components/ChartCard.vue";
import _ from "lodash";
import { split } from "lodash";
export default {
  components: { Card },
  props: {
    color_theme: String,
    chart_title: String,
    title: {
      type: String,
      default: "title"
    },
    subtitle: {
      type: String,
    },
    colors: {
      type: Array,
      default: ['#2ECC71', '#F39C12', '#E74C3C'],
    },
    data: {
      type: Array,
      default: {
        data: []
      }
    },
  },
  data() {
    return {
      render_count: 1,
      series: this.$props.data.data,
      chartOptions: {
        colors: this.$props.colors,
        theme: {
          mode: this.$props.color_theme,
        },
      

      chart: {
        type: 'bar',
        height: 350,
        stacked: true,
        stackType: '100%'
      },
      plotOptions: {
        bar: {
          // horizontal: true,
          dataLabels: {
            
            total: {
              enabled: true,
              offsetX: 0,
              style: {
                fontSize: '13px',
                fontWeight: 900
              },
              formatter: function(val) {
                  return Math.round(val * 100) / 100;
              },
            }
          }
        },
      },
      stroke: {
        width: 1,
        colors: ['#fff']
      },

      xaxis: {
        categories: this.$props.data.categories,
        labels: {
          rotate: 0,
          formatter: function (val) {
            return _.chunk(val.split(" "), 2).map(chunk => chunk.join(" "));
          }
        }
      }, 
      yaxis: {
        title: {
          text: undefined
        },
        labels: {
          formatter: function (val) {
            return val + '%';
          }
        }
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return val 
          }
        }
      },
      fill: {
        opacity: 1
      },
      legend: {
        position: 'top',
        horizontalAlign: 'center',
        offsetX: 0,
        fontSize: '20px',
      }
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
