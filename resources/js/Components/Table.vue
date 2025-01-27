<template>
  <Modal :show="showFileUploadModal" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $t("File Upload") }}
      </h2>
      <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">
        {{ $t("Upload documentation files here") }}:
      </label>
      <FileInput 
        id="multiple_files"
        :trigger="fileuploadInputTrigger"
        :item_id="form.attachable_id"
        :model="model"
        attachment_type="1"
        @success-uploading="closeModal"
        @finish-uploading="isFileInputLoading = false"
        @start-uploading="isFileInputLoading = true"
      />

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal">
          {{ $t("Cancel") }}
        </SecondaryButton>

        <PrimaryButton class="ml-3" @click="triggerFileInput">
          <span v-if="isFileInputLoading"> {{ $t("Uploading") }}...</span>
          <span v-else> {{ $t("Upload") }} </span>
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <div class="relative shadow-md sm:rounded-lg">
    <header>
      <h2
        class="py-2 bg-white dark:bg-gray-800 text-center capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
      >
        {{ $t(title) }}

        <a
          :href="`/${import_url}${urlParams()}`"
          v-if="import_url"
          color="blue"
          class="absolute right-4 text-center w-4 h-4 text-blue-500 clickable"
          data-tooltip-target="export-statistics-tooltip"
          data-tooltip-trigger="hover"
        >
          <div
            id="export-statistics-tooltip"
            role="tooltip"
            class="absolute z-50 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
          >
            {{ $t("Export to Excel") }}
            <div class="tooltip-arrow" data-popper-arrow></div>
          </div>
          <f-icon class="text-blue" icon="file-import"></f-icon>
        </a>
        <a
          :href="route(add_item_route)"
          v-if="add_item_route"
          color="green"
          class="absolute left-4 text-center w-4 h-4 text-blue-500 clickable"
          data-tooltip-target="export-statistics-tooltip"
          data-tooltip-trigger="hover"
        >
          <div
            id="export-statistics-tooltip"
            role="tooltip"
            class="absolute z-50 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-400 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
          >
            {{ $t("Add Item") }}
            <div class="tooltip-arrow" data-popper-arrow></div>
          </div>
          <f-icon class="text-blue" icon="plus"></f-icon>
        </a>
      </h2>
    </header>
    <header>
      <div class="flex flex-row justify-start mx-2" dir="ltr">
        <div v-for="e in table_filters" :key="e" class="relative w-1/4">
          <SelectInput
            :dir="this.$i18n.locale == 'ar' ? 'rtl' : 'ltr'"
            classes="text-xs text-gray-400"
            underline="true"
            id="select"
            v-model="filters[e.model]"
            :options="e.options"
          />
        </div>
      </div>
    </header>
    <div class="overflow-x-auto">
      <table
      class="table-auto w-full text-sm text-start text-gray-500 dark:text-gray-400"
      :key="table_key"
    >
      <thead
        class="border-b-2 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400"
      >
        <tr>
          <th
            :key="index"
            v-for="(head, index) in headers"
            scope="col"
            class="px-6 py-3 max-w-80"
          >
            <div class="flex items-center">
              {{ $t(head.value) }}
              <a
                v-if="head.sortable"
                @click.prevent="handleSort(head.value)"
                href="#"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-3 h-3 ml-1"
                  aria-hidden="true"
                  fill="currentColor"
                  viewBox="0 0 320 512"
                >
                  <path
                    d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"
                  /></svg
              ></a>
            </div>
          </th>
          <th v-if="add_file_input" scope="col" class="px-6 py-3">
            {{ $t("upload documents") }}
          </th>
          <th v-if="actions" scope="col" class="px-6 py-3">
            {{ $t("actions") }}
          </th>
        </tr>
      </thead>
      <tbody v-if="haveData">
        <tr
          @dblclick="handelDbClick(item)"
          :key="item.id"
          v-for="item in items.data"
          class="cursor-pointer bg-white border-b dark:bg-gray-800 dark:border-gray-700 items-center"
          :class="rowClass(item)"
        >
          <th
            :key="index"
            v-for="(head, index) in headers"
            scope="row"
            class="px-6 py-2 max-w-80 text-pretty font-semibold text-black whitespace-nowrap dark:text-white"
          >
          <a 
          v-if="head.type && head.type == 'link'"
          :href="item[head.key]"
          target="_blank"
          >
          {{ item[head.key] }}
          </a>
            <div
              v-else
              :class="
                head.has_class
                  ? `_${item[head.class_value_name]}_${model}_${head.value.replace(' ', '_')}`
                  : ''
              "
            >
              {{ head.translate ? $t(item[head.key] + "") : item[head.key] }}
            </div>
          </th>

          <td v-if="add_file_input" class="px-6 py-4 min-w-40">
            <span class="p-1">
              <button
                v-on:click="fileuploadInputIconClick(item.id)"
                type="button"
                class="text-gray-700 dark:text-white"
              >
                <f-icon
                  icon="upload"
                ></f-icon>
              </button>
            </span>
          </td>
          <td v-if="actions" class="px-6 py-4">
            <template v-for="(action, i) in actions" >
              <TableAction
              
              :item="item"
              :action="action"
              :key="i"
              v-if="action.showFunc? action.showFunc(item): true"
            />
            </template>
            
          </td>
        </tr>
      </tbody>
      <tbody class="w-full text-center text-gray-300 capitalize text-lg" v-else>
        <tr>
          <th class="p-5" colspan="100%">there is no data</th>
        </tr>
      </tbody>
    </table>
    </div>
    <Paginator :params="route().params" :links="items.links" />
  </div>
</template>

<script setup>
import Paginator from "@/Components/Paginator.vue";
import TableAction from "@/Components/TableAction.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { router, useForm } from "@inertiajs/vue3";
import { reactive, watch, ref } from "vue";
import { useI18n } from "vue-i18n";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileInput from "@/Components/FileInput.vue";
import SecondaryButton from "./SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
const { t } = useI18n();

const props = defineProps({
  refresh_only: {
    type: String,
    default: "",
  },
  table_filters: {
    type: Object,
    default: {},
  },
  model: {
    type: String,
    default: "",
  },
  headers: {
    type: [Array],
    default: [],
  },
  items: {
    type: [Array],
    default: null,
  },
  actions: {
    type: [Array],
    default: null,
  },
  title: {
    type: String,
    default: "",
  },
  import_url: {
    type: String,
    default: "",
  },
  add_item_route: {
    type: String,
    default: "",
  },
  add_file_input: {
    type: String,
    default: "",
  },
});

const table_key = ref(0);
const showFileUploadModal = ref(true);
const fileuploadInputTrigger = ref(0);
const isFileInputLoading = ref(false);

const form = useForm({
  files: [],
  attachable_id: "",
  attachment_type: "",
  attachable_type: props.model,
});

const filters = reactive({
  warehouse_id: 0,
});

watch(filters, (newValue, old) => {
  router.reload({
    only: [props.refresh_only],
    data: { ...newValue, forms_page: 1 },
  });
  table_key.value++;
});

const rowClass = (item) => {
  switch (props.model) {
    case "user":
      switch (item['status']) {
        case 2:
          return "red_ths";
        case 3:
          return "grey_ths";
        default:
          return "";
    
      }
    case "warehouse":
      switch (item['status']) {
        case 1:
          return "green_ths";
        case 2:
          return "grey_ths";
        case 3:
          return "";
        case 4:
          return "red_ths";
        default:
          return "";
      }
    case "proposal":
      switch (item['status']) {
        case 1:
          return "grey_ths";
        case 2:
          return "green_ths";
        case 3:
          return "red_ths";
        case 8:
          return "blue_ths";
        default:
          return "";
      }
  }
};
let sortBy = [];
let sortDesc = [];
const handleSort = (head) => {
  if (!sortBy.includes(head)) {
    sortBy = [head];
    sortDesc = [false];
  } else {
    sortDesc[0] = !sortDesc[0];
  }
  const page = route().params.page;
  const url = page
    ? route(route().current()) + "?page=" + page
    : route(route().current());
  router.get(
    url,
    { sortDesc, sortBy },
    { preserveState: true, preserveScroll: true }
  );
};

const haveData = props.items && props.items.data && props.items.data[0];

function fileuploadInputIconClick(id) {
  form.attachable_id = id; 
  showFileUploadModal.value = !showFileUploadModal.value
}
function handelDbClick(item) {
  if (props.model) {
    console.log("GFGFGF");
    console.log(props.model + ".edit, item.id");
    router.get(route(`${props.model}.edit`, item.id));
  }
}
function closeModal() {
  showFileUploadModal.value = false;
}

function triggerFileInput() {
  fileuploadInputTrigger.value = !fileuploadInputTrigger.value;
}
function urlParams() {
  return "?" + new URLSearchParams(filters).toString();
}
/*
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
    name: "Pending",
    model: "status",
    options: [
      { id: 0, name: t("All Statuses") },
      { id: 1, name: t("New") },
      { id: 2, name: t("In_process") },
      { id: 3, name: t("Pending") },
      { id: 4, name: t("Closed") },
      { id: 5, name: t("Rejected") },
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
*/
</script>
<style>
._2_user_status {
  background: rgb(250, 66, 66);
  color: white;
  text-align: center;
  position: relative;
}
/*._2_user_status::after {
  content: "";
  width: 17px;
  height: 17px;
  top: -5px;
  left: 0;
  position: absolute;
  border-radius: 50px;
  background-color: red;
  border: solid 2px white;
  outline: red solid 1px;
}*/
._1_user_status {
  padding: 4px 12px;
  background: rgb(200, 254, 200);
  color: rgb(46, 179, 46);
}
._3_user_status {
  background: rgb(228, 228, 228);
  color: rgb(110, 110, 110);
}
/*._4_user_status {
  background: rgb(255, 243, 221);
  color: rgb(255, 166, 0);
}
._5_user_status {
  background: rgb(255, 216, 216);
  color: red;
}*/

._0_donation_status {
  background: rgb(255, 243, 221);
  color: rgb(255, 166, 0);
}
._2_donation_status {
  padding: 4px 12px;
  background: rgb(200, 254, 200);
  color: rgb(46, 179, 46);
}
._3_donation_status {
  background: rgb(250, 66, 66);
  color: white;
  text-align: center;
  position: relative;
}

._1_warehouse_status {
  padding: 4px 12px;
  background: rgb(200, 254, 200);
  color: rgb(46, 179, 46);
}
._2_warehouse_status {
  background: rgb(255, 243, 221);
  color: rgb(255, 166, 0);
}
._3_warehouse_status {
  background: rgb(228, 228, 228);
  color: rgb(110, 110, 110);
}
._4_warehouse_status {
  background: rgb(250, 66, 66);
  color: white;
  text-align: center;
  position: relative;
}

._1_warehouse_transaction_transaction_type {
  padding: 4px 12px;
  background: rgb(200, 254, 200);
  color: rgb(46, 179, 46);
}
._2_warehouse_transaction_transaction_type {
  background: rgb(250, 66, 66);
  color: white;
  text-align: center;
  position: relative;
}

._1_proposal_status {
  background: rgb(228, 228, 228);
  color: rgb(110, 110, 110);

}
._2_proposal_status {
  background: rgb(47, 242, 47);
  color: white;
}
._3_proposal_status {
  padding: 4px 12px;
  background: rgb(250, 66, 66);
  color: white;
}
._8_proposal_status {
  background: rgb(127, 99, 238);
  color: white;
  padding: 4px 12px;
}

._1_warehouse_transaction_transaction_type,
._2_warehouse_transaction_transaction_type,
._1_warehouse_status,
._2_warehouse_status,
._3_warehouse_status,
._4_warehouse_status,
._1_proposal_status,
._2_proposal_status,
._3_proposal_status,
._8_proposal_status,
._0_donation_status,
._2_donation_status,
._3_donation_status,
._1_user_status,
._2_user_status,
._3_user_status,
._4_user_status,
._5_user_status {
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 15px;
  text-transform: capitalize;
  text-align: center;
  max-width: 200px;
}
.red_ths th {
  color: red !important;
}
.grey_ths th {
  color: grey !important;
}
.green_ths th {
  color: rgb(47, 242, 47) !important;
}
.blue_ths th {
  color: #3b82f6 !important;
}
</style>
