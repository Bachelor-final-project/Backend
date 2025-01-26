<template>
  <Card class="p-5">
    <div :key="render_count" id="chart-line">
      <div class="grid grid-cols-6 mb-5">
        <div class="flex col-span-6 md:col-span-2 pe-32 flex-col">
          <VueDatePicker
            class="w-56"
            v-model="pointed_chart_params.pointed_chart_year"
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
        <div class="col-span-6 md:col-span-2">
          <div class="flex flex-col self-center">
            <h2
              class="text-xl sm:text-2xl self-center mb-10 font-bold dark:text-white whitespace-nowrap"
            >
              {{
                $t("Total reports by type for") +
                " " +
                selected_region["name_" + i18n_locale]
              }}
            </h2>
          </div>
        </div>
      </div>
      <div class="flex justify-center text-lg mb-5">
        <div class="flex-row self-center text-xl">
          <span class="mr-5 text-[#6b7280]">{{
            $t("Select Region:") + "  "
          }}</span>
        </div>
        <button
          v-for="region in all_regions"
          :key="region"
          class="border-2 sm:px-9 text-xl p-4 mx-2 rounded-md dark:text-gray-400"
          :class="{
            'font-bold bg-[#fafbfc]': selected_region === region,
          }"
          @click="select_region(region)"
        >
          {{ region["name_" + i18n_locale] }}
        </button>
      </div>

      <apexchart
        type="line"
        height="400"
        :options="chartOptions"
        :series="tegs_value"
      ></apexchart>
    </div>
    <div class="flex justify-center">
      <div class="w-1/2">
        <Multiselect
          class="checkbox_type"
          v-model="tegs_value"
          mode="tags"
          placeholder="Select Form Type"
          track-by="name"
          label="name"
          :caret="true"
          :object="true"
          value-prop="name"
          :close-on-select="false"
          :searchable="true"
          :options="data"
        >
          <template v-slot:tag="{ option, handleTagRemove, disabled }">
            <!-- style="background:green !important" -->
            <div
              class="multiselect-tag is-user"
              :class="{
                'is-disabled': disabled,
              }"
            >
              {{ option.name }}
              <span
                v-if="!disabled"
                class="multiselect-tag-remove"
                @click="handleTagRemove(option, $event)"
              >
                <span class="multiselect-tag-remove-icon"></span>
              </span>
            </div>
          </template>
          <template v-slot:placeholder>
            <div class="place_holder">{{ $t("Select Form Type") }}</div>
          </template>
        </Multiselect>
      </div>
    </div>
  </Card>
</template>

<script setup>
import Multiselect from "@vueform/multiselect";
import VueDatePicker from "@vuepic/vue-datepicker";
import Card from "@/Components/ChartCard.vue";
import { router } from "@inertiajs/vue3";

import { reactive, ref, onMounted, watch } from "vue";
import ApexCharts from "apexcharts";
const render_count = ref(1);
const tegs_value = ref([]);
// import * as dropdown from "@/multiselect-dropdown";

// let v = new MultiSelectTag("countries");
const stack_group_chart_year = ref(new Date().getFullYear());
const datepicker = ref(null);
const props = defineProps({
  color_theme: {
    type: String,
  },
  data: Array,
  all_regions: {
    type: Array,
    default: ['dd', 'ff']
  },
});
function allYear() {
  // console.log("Hello, Close");
  // console.log(this.$refs.datepicker);
  if (datepicker.value) {
    datepicker.value.closeMenu();
    selectDate();
    pointed_chart_params.pointed_chart_year = "-";
  }
}

function selectDate() {
  // console.log("Hello, Close");
  // console.log(this.$refs.datepicker);
  if (datepicker.value) {
    datepicker.value.selectDate();
    datepicker.value.closeMenu();
  }
}

watch(
  () => props.color_theme,
  (newValue) => {
    chartOptions.theme.mode = newValue;
    render_count.value++;
  }
);

watch(
  () => props.data,
  (newValue) => {
    series = newValue;
    updateDataToShow(series, tegs_value.value);
    render_count.value++;
  }
);

watch(tegs_value, (newValue) => {
  series = newValue;
  updateDataToShow(series, tegs_value.value);
  render_count.value++;
});



function select_region(region) {
  selected_region.value = region;
  pointed_chart_params.region_id = region.id;
}

let selected_region = ref(props.all_regions[0]);

const pointed_chart_params = reactive({
  region_id: props.all_regions[0].id,
  pointed_chart_year: new Date().getFullYear(),
});

watch(
  pointed_chart_params,
  (newValue, old) => {
    // console.log("Pointed Chart: ", newValue);
    router.reload({
      only: ["pointed_chart"],
      data: newValue,
      preserveState: true,
      preserveScroll: true,
    });
  },
  { deep: true }
);

function updateDataToShow(newSeries, toUpdateArray) {
  var hashmapSeries = newSeries.reduce(function (map, obj) {
    map[obj.name] = obj.data;
    return map;
  }, {});

  toUpdateArray.forEach((item, index) => {
    item.data = hashmapSeries[item.name];
  });
}
let series = ref(props.data);

let dataToShow = reactive([]);
let chartOptions = {
  theme: {
    mode: props.color_theme,
    palette: "palette1",
    monochrome: {
      enabled: false,
    },
  },
  legend: {
    show: true,
    fontSize: "16px",
  },
  chart: {
    id: "fb",
    group: "social",
    type: "line",
    height: 120,
  },
  // colors: [
  //   "#FF6384",
  //   "#36A2EB",
  //   "#FFCE56",
  //   "#4BC0C0",
  //   "#9966FF",
  //   "#FF9F40",
  //   "#FF0000",
  //   "#00FF00",
  //   "#0000FF",
  //   "#808080",
  // ],
  markers: {
    size: 8,
    strokeWidth: 1,
    strokeColors: "#fff",
    strokeOpacity: 0.7,
    fillOpacity: 1,
    shape: "circle",
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
  yaxis: {
    labels: {
      style: {
        fontSize: "16px",
      },
    },
  },
};
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
<style>
.checkbox_type {
  margin-bottom: 10px;
  border: none;
  border-bottom: 1px solid #e0e0e0;
}
.place_holder {
  color: #a0aec0;
  text-align: center;
  width: 100%;
}

.region_button {
  border: 1px solid;
  border-radius: 10px;
  margin: 20px;
  padding: 15px;
  padding-left: 50px;
  padding-right: 50px;
  border-color: rgb(219, 216, 216);
}
.button-container {
  display: flex;
  justify-content: center;
}
</style>
