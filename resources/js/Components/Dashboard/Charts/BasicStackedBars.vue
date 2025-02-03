<template>
  <Card>
    <div class="flex flex-col self-center relative">
      <h2 class="text-2xl self-center mb-10 mt-5 font-bold dark:text-white">
        {{ $t(chart_title) }}
      </h2>
      <h6 v-if="chart_tips" class="text-l self-center font-bold dark:text-white absolute top-10 ltr:left-10 rtl:right-10">
        {{ $t(chart_tips) }}
      </h6>
    </div>
    <div id="chart">
      <apexchart
       :key="render_count"
       class="hover:cursor-pointer"
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
    chart_tips: {
      type: String,
      default: null
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
    const propsData = this.$props.data;
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
        stackType: '100%',
        events: {
          dataPointSelection: function(event, chartContext, config) {
            const dataPointIndex = config.dataPointIndex;
            const id = propsData.ids[dataPointIndex];
            const category = [2, 0, -1][config.seriesIndex];
            if(event.ctrlKey && category >= 0)
              window.open(route('donation.index', {proposal_id_filter: id, status_filter:category}))
            // this.handleBarClick(config);
            
          }
         }
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
  methods: {
    handleBarClick(config) {
      // // Extract the clicked group and category information
      // const seriesIndex = config.seriesIndex;
      // const dataPointIndex = config.dataPointIndex;
      // const value = this.series[seriesIndex].data[dataPointIndex];
      // const category = this.$props.data.categories[dataPointIndex];

      // // Prepare the link with query parameters
      // const proposal_id = value; // Adjust this to get the related value for the proposal_id
      // const group = category;    // The group name is the category in this case

      // const link = `donation?proposal_id=${proposal_id}&group=${group}`;

      // // Redirect the user to the generated link
      // window.location.href = link;
      const seriesIndex = config.seriesIndex;
        const dataPointIndex = config.dataPointIndex;
        // const value = this.series[seriesIndex].data[dataPointIndex];
        // const category = this.$props.data.categories[dataPointIndex];
      console.log(this.$props.data.data);
        console.log('seriesIndex:');
        console.log(seriesIndex);
        console.log('dataPointIndex:');
        console.log(dataPointIndex);
        console.log('value:');
        console.log(value);
        console.log('category:');
        console.log(category);

    }
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
