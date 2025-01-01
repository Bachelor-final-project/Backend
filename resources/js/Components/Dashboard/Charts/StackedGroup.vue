<template>
  <Card class="p-5">
    <div class="grid grid-cols-6 mb-5">
      <div class="flex col-span-6 md:col-span-2 pe-32 flex-col">
        <Multiselect
          class="checkbox_type w-56"
          v-model="formtype_value"
          placeholder="Select Form Type"
          track-by="name"
          label="name"
          :caret="true"
          :object="true"
          value-prop="name"
          :close-on-select="true"
          :searchable="true"
          :options="form_type_filter"
          v-if="show_formtype_filter"
          :allow-empty="false"
          @select="selectType()"
        >
          <template v-slot:placeholder>
            <div class="place_holder">
              {{ $t("Select Form Type") }}
            </div>
          </template>
        </Multiselect>

        <VueDatePicker
          class="flex-shrink w-56"
          v-model="stack_group_chart_year"
          year-picker
          :space-confirm="true"
          :clearable="false"
          mode-height="160"
          ref="datepicker"
        >
          <template #action-row="{}">
            <div class="action-row">
              <p class="current-selection mt-4 clickable" @click="allYear()">
                {{ $t("All Years") }}
              </p>
              <button class="select-button mt-4" @click="selectDate()">
                {{ $t("Select") }}
              </button>
            </div>
          </template>
        </VueDatePicker>
      </div>
      <div class="flex flex-col col-span-6 md:col-span-2 items-center">
        <h2
          class="flex-shrink-0 text-2xl top-0 text-center md:text-start font-bold dark:text-white"
        >
          {{ $t(chart_title) }}
        </h2>
      </div>
    </div>
    <div :key="render_count" id="chart">
      <apexchart
        type="bar"
        height="350"
        :options="chartOptions"
        :series="series"
      ></apexchart>
    </div>
  </Card>
</template>

<script>
import Multiselect from "@vueform/multiselect";
import "@vuepic/vue-datepicker/dist/main.css";
import { router } from "@inertiajs/vue3";
import Card from "@/Components/ChartCard.vue";
import VueDatePicker from "@vuepic/vue-datepicker";
// import { DatePickerInstance } from "@vuepic/vue-datepicker";
import { ref } from "vue";

export default {
  components: { Card, VueDatePicker, Multiselect },
  props: {
    color_theme: String,
    data: Array,
    all_types: Array,
    show_filter: {
      type: Boolean,
      default: false,
    },
    show_formtype_filter: {
      type: Boolean,
      default: false,
    },
    chart_prop_name: {
      type: String,
      default: "stacked_group_chart",
    },
    chart_title: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      form_type_filter: [{ id: 0, name: this.$t("All Types") }].concat(
        this.$props.all_types
      ),
      //   { id: 0, name: this.$t("All Types") },
      //   ...this.$props.all_types,
      // ],
      formtype_value: {},
      stack_group_chart_year: ref(new Date().getFullYear()),
      datepicker: ref(null),
      render_count: 1,
      series: this.$props.data,
      chartOptions: {
        theme: {
          mode: this.$props.color_theme,
          palette: "palette1",
          monochrome: {
            enabled: false,
          },
        },
        chart: {
          type: "bar",
          height: 350,
          stacked: true,
        },
        stroke: {
          width: 1,
          colors: ["#fff"],
        },
        dataLabels: {
          formatter: (val) => {
            return "";
          },
        },
        plotOptions: {
          bar: {
            columnWidth: "60%",
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
          labels: {
            style: {
              fontSize: "16px",
            },
          },
        },
        fill: {
          opacity: 1,
        },
        colors: [
          "#ffff00",
          "#2a027a",
          "#3aff29",
          "#ffffd6",
          "#b996ff",
          "#c1f7bc",
        ],
        yaxis: {
          labels: {
            style: {
              fontSize: "16px",
            },
          },
        },
        legend: {
          position: "top",
          horizontalAlign: "center",
          fontSize: "16px",
        },
      },
      handleInternal: (date) => {
        this.year = date.getFullYear();
      },
      allYear: () => {
        // console.log("Hello, Close");
        // console.log(this.$refs.datepicker);
        if (this.$refs.datepicker) {
          this.stack_group_chart_year = "-";
          this.$refs.datepicker.closeMenu();
          this.selectDate();
        }
      },
      selectDate: () => {
        // console.log("Hello, Close");
        // console.log(this.$refs.datepicker);
        if (this.$refs.datepicker) {
          this.$refs.datepicker.selectDate();
          this.fetchData();
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
    data: {
      handler(val) {
        // console.log("WatcherData");
        // console.log(val);
        this.series = val;
        this.render_count++;
      },
    },
  },
  methods: {
    selectType(v, id) {
      // console.log(this.formtype_value);
      this.fetchData();
    },
    fetchData() {
      let formtype = this.formtype_value || this.form_type_filter[0];
      router.reload({
        only: [this.$props.chart_prop_name],
        data: {
          stack_group_chart_year: this.stack_group_chart_year,
          stack_group_chart_formtype_id: formtype.id,
        },
        preserveState: true,
        preserveScroll: true,
      });
    },
  },
  mounted() {
    this.formtype_value = this.form_type_filter[0];
  },
};
</script>

<style>
.minus-margin {
  margin-top: -20px;
}

.action-row {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  justify-content: space-around;
}
.clickable {
  cursor: pointer;
}
.space-table-rows {
  border-collapse: separate;
  border-spacing: 0 15px;
}
</style>
