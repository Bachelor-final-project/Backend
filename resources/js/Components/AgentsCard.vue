<template>
  <div>
    <div class="grid grid-cols-12 gap-5 dark:text-white">
      <div
        :class="hide_agents_side ? 'col-span-12' : 'sm:col-span-7 col-span-12'"
      >
        <Card>
          <div class="relative grid grid-cols-12">
            <div
              class="sm:col-span-3 col-span-12 flex sm:flex-col flex-row font-bold gap-4"
            >
              <f-icon icon="file-lines" class="self-start text-xl" />

              <div>
                <a class="cursor-pointer" href="#forms">
                  {{ $t("Total reports") }}

                  <div
                    id="export-statistics-tooltip"
                    role="tooltip"
                    class="absolute z-50 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                  >
                    {{ $t("Export to Excel") }}
                    <div class="tooltip-arrow" data-popper-arrow></div>
                  </div>
                </a>
                <a
                  :href="`/import-statistics${urlParams()}`"
                  color="blue"
                  class="text-center start-0 w-4 h-4 text-blue-500 clickable"
                  data-tooltip-target="export-statistics-tooltip"
                  data-tooltip-trigger="hover"
                >
                  <f-icon class="text-blue" icon="file-import"></f-icon>
                </a>
              </div>
              <div class="flex flex-row justify-between">
                <h2 class="text-xl">
                  {{ total_grievrance }}
                </h2>
                <h2
                  class="mx-5 text-xl text-red-600"
                  data-tooltip-target="tooltip-hover"
                  data-tooltip-trigger="hover"
                >
                  {{ `(${statistics[5]})` }}
                </h2>
                <div
                  id="tooltip-hover"
                  role="tooltip"
                  class="absolute z-50 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                >
                  {{
                    $t(
                      "Delayed: All grievaces with normal importance, 5 days have passed since creation, or high importance, 2 days have passed since creation."
                    )
                  }}
                  <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
              </div>
            </div>
            <div
              class="sm:col-span-9 col-span-12 flex flex-col justify-between"
            >
              <div class="flex flex-col">
                <div class="ps-4 flex justify-between">
                  <div
                    v-for="e in page.props.auth.user.type == 1
                      ? total_grievances_filters
                      : agent_total_grievances_filters"
                    :key="e"
                    class="relative w-1/4"
                  >
                    <SelectInput
                      classes="text-xs text-gray-400"
                      underline="true"
                      id="select"
                      v-model="global_stats[e.model]"
                      :options="e.options"
                    />
                  </div>
                </div>
                <hr class="h-px dark:bg-gray-400" />
              </div>
              <div class="flex flex-wrap justify-between">
                <div
                  class="border-s sm:text-base text-sm flex-auto"
                  v-for="(e, i) in total_grievances"
                  :key="e"
                >
                  <div class="text-center" :class="e[1]">
                    {{ e[0] }}
                  </div>
                  <div class="text-center">
                    {{ statistics[i] }}
                  </div>
                </div>
              </div>
            </div>
            <!-- <a
              href="#forms"
              class="absolute bottom-0 text-center end-0 bg-blue-500 w-5 h-6"
            >
              <f-icon class="text-white" icon="angles-down"></f-icon>
            </a> -->
          </div>
        </Card>
      </div>

      <div class="sm:col-span-5 col-span-12">
        <Card v-if="!hide_agents_side">
          <div class="grid grid-cols-4 relative">
            <div
              class="sm:col-span-1 col-span-12 flex sm:flex-col flex-row gap-4 justify-between font-bold"
            >
              <f-icon icon="user-group" class="self-start" />

              <a href="#users">
                <h2 class="cursor-pointer">{{ $t("Agents") }}</h2>
              </a>
              <h2 class="text-xl">{{ agents_count[0]["user_count"] }}</h2>
            </div>
            <div class="col-span-3 flex flex-col justify-between gap-2">
              <div class="flex flex-col">
                <div class="flex w-3/12 self-end">
                  <!-- <SelectInput
                    classes="text-xs text-gray-400"
                    underline="true"
                    id="select"
                    v-model="user_form.region_id"
                    :options="total_grievances_filters[0].options"
                  /> -->
                </div>
                <!-- <hr class="h-px mb-6 dark:bg-gray-400" /> -->
              </div>
              <div class="flex flex-wrap justify-between">
                <div
                  class="border-s pl-4 text-base text-center flex-auto"
                  v-for="(e, i) in regions"
                  :key="e"
                >
                  <div :class="e[1]">
                    {{ agents_count[i + 1]["name_" + i18n_locale] }}
                  </div>
                  <div class="text-center pr-8">
                    {{ agents_count[i + 1]["user_count"] }}
                  </div>
                </div>
              </div>
            </div>
            <!-- <a
              href="#users"
              class="absolute bottom-0 text-center end-0 bg-blue-500 w-5 h-6"
            >
              <f-icon class="text-white" icon="angles-down"></f-icon>
            </a> -->
          </div>
        </Card>
      </div>
      <br />
    </div>
  </div>
</template>

<script setup>
import Card from "@/Components/Card.vue";
import { useI18n } from "vue-i18n";
import SelectInput from "@/Components/SelectInput.vue";
// import MultiSelect from "@/Components/MultiSelect.vue";
// import Multiselect from "vue-multiselect";
// import VueMultiselect from "vue-multiselect";
// import Multiselect from "vue-multiselect/dist/vue-multiselect.esm";
import { useForm } from "@inertiajs/vue3";
import { reactive, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

const emits = defineEmits(["formsFiltersUpdated", "usersFiltersUpdated"]);

const page = usePage();
const { t } = useI18n();
const global_stats = reactive({
  region_id:
    page.props.auth.user.type == 1 ? 0 : page.props.auth.user.region_id,
  form_type: 0,
  time_period: "all_years",
  priority: 0,
});
watch(
  global_stats,
  (newValue, old) => {
    router.reload({
      only: ["global_statistics", "total_grievrance"],
      data: newValue,
    });
  },
  { immediate: true }
);
// const user_form = reactive({
//   region_id: 0,
// });
// watch(
//   user_form,
//   (newValue, old) => {
//     router.reload({
//       only: ["all_users"],
//       data: { ...newValue, forms_page: 1 },
//     });
//   },
//   { immediate: true }
// );
const props = defineProps({
  hide_agents_side: Boolean,
  total_grievrance: String,
  all_types: Array,
  all_regions: Array,
  statistics: Array,
  agents_count: Array,
});

const test = function () {
  downloadCSVData(
    "Total grievances",
    this.extractHeaders(this.total_grievances),
    [[...this.statistics]],
    true
  );
};

function urlParams() {
  return "?" + new URLSearchParams(global_stats).toString();
}

const total_grievances_filters = [
  {
    name: "All Regions",
    model: "region_id",
    options: [{ id: 0, name: t("All Regions") }, ...props.all_regions],
  },
  {
    name: "All Types",
    model: "form_type",
    options: [{ id: 0, name: t("All Types") }, ...props.all_types],
  },
  {
    name: "All Years",
    model: "time_period",
    options: [
      { id: "this_week", name: t("This Week") },
      { id: "this_month", name: t("This Month") },
      { id: "this_quarter", name: t("This Quarter") },
      { id: "this_year", name: t("This Year") },
      { id: "all_years", name: t("All Years") },
    ],
  },
  {
    name: "Priority",
    model: "priority",
    options: [
      { id: 0, name: t("All Priorities") },
      { id: 1, name: t("Normal") },
      { id: 2, name: t("Critical") },
    ],
  },
];
const agent_total_grievances_filters = [
  {
    name: "All Types",
    model: "form_type",
    options: [{ id: 0, name: t("All Types") }, ...props.all_types],
  },
  {
    name: "All Years",
    model: "time_period",
    options: [
      { id: "this_week", name: t("This Week") },
      { id: "this_month", name: t("This Month") },
      { id: "this_quarter", name: t("This Quarter") },
      { id: "this_year", name: t("This Year") },
      { id: "all_years", name: t("All Years") },
    ],
  },
  {
    name: "Priority",
    model: "priority",
    options: [
      { id: 0, name: t("priority") },
      { id: 1, name: t("normal") },
      { id: 2, name: t("Critical") },
    ],
  },
];
const total_grievances = [
  [t("new"), "text-green-400"],
  [t("closed"), "text-blue-800"],
  [t("pending"), "text-yellow-300"],
  [t("in process"), "text-green-600"],
  [t("rejected"), "text-purple-900"],
  // [t("delayed"), "text-red-400"],
];
let total_grievances_vals = [100, 5, 10, 20, 5];
let filters_menu = [];

const regions = [
  ["Al Raqa", "text-yellow-300", 20],
  ["Hawwas", "text-blue-400", 20],
  ["Basira", "text-green-400", 20],
];
const regions_filters = ["All Regions"];

const extractHeaders = function (total_grievances) {
  /*
  fetch grievances types(first value) from total_grievances
    convert [
      ["closed", "text-green-400"],
      ["delayed", "text-red-400"],
      ["pending", "text-yellow-300"],
      ["in process", "text-green-600"],
      ["rejected", "text-purple-900"]
    ]

    to 

    ["closed", "delayed", "pending", "in process", "rejected"]
  */
  return total_grievances.map((item) => item[0]);
};
</script>

<style>
.clickable {
  cursor: pointer;
}
</style>
