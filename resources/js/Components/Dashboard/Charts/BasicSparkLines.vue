<template>
  <Card>
    
    <div id="chart">
      <apexchart
        :key="render_count"
        type="area"
        height="360"
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
    title: {
      type: String,
      default: "title"
    },
    subtitle: {
      type: String, 
    },
    colors:{
      type: Array,
      default: ['#379cfa'],
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
          height: 160,
          type: "area",
          sparkline: {
            enabled: true
          },
        },
        stroke: {
          curve: 'straight'
        },
        fill: {
          opacity: 0.3,
        },
        title: {
          text: this.$props.data?.data?.length>0? this.$props.data.data.reduce((sum, e) => {return sum + e}): 0,
          style: {
            fontSize: '32px',
          },
          offsetX: 0,
        },
        subtitle: {
          text: this.$props.subtitle,
          style: {
            fontSize: '24px',
          },
          offsetX: 0,
        },
        tooltip: {
          fixed: {
            enabled: false
          },
          x: {
            show: false
          },
          y: {
            title: {
              formatter: function (seriesName) {
                return ''
              }
            }
          },
          marker: {
            show: false
          }
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
