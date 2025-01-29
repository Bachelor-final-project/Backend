<template>
  <Modal :show="upload_document_file" @close="modalFunctions['closeDocumentFileModal']">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $t("File Upload") }}
      </h2>
      <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="document_upload_files">
        {{ $t("Upload documentation files here") }}:
      </label>
      <FileInput 
        id="document_upload_files"
        model="document"
        v-model="documentFiles"
        attachment_type="1"
        :file_input_help="$t('Videos and Images')"
        :show_files_details="true"
        @fileinput-change="modalFunctions['documentFileinputChanged']"
        @finish-uploading="isFileInputLoading = false"
        @start-uploading="isFileInputLoading = true"
      />
      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="modalFunctions['closeDocumentFileModal']">
          {{ $t("Cancel") }}
        </SecondaryButton>

        <PrimaryButton class="ml-3" @click="modalFunctions['confirmDocumentFileUpload']">
          <span v-if="isFileInputLoading"> {{ $t("Uploading") }}...</span>
          <span v-else> {{ $t("Upload") }} </span>
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <Modal :show="delete_dialog" @close="modalFunctions['closeDeleteModal']">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 ">
        {{ $t("Are you sure you want to delete ?") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{
          $t("Once your accept, all of its data will be permanently deleted.")
        }}.
      </p>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="modalFunctions['closeDeleteModal']">
          {{ $t("Cancel") }}
        </SecondaryButton>

        <DangerButton class="ml-3" @click="modalFunctions['confirmDelete']">
          {{ $t("delete") }}
        </DangerButton>
      </div>
    </div>
  </Modal>
  <Modal :show="block_dialog" @close="modalFunctions['closeBlockModal']">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $t("Are you sure ?") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("Once your accept, User will be updated") }}.
      </p>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="modalFunctions['closeBlockModal']">
          {{ $t("Cancel") }}
        </SecondaryButton>
        
        <DangerButton class="ml-3" @click="modalFunctions['confirmBlock']">
          {{ modal_item.status == "active" ? $t("block") : $t("activate") }}
        </DangerButton>
      </div>
    </div>
  </Modal>
  <Modal :show="edit_dialog" @close="modalFunctions['closeEditModal']">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $t("Are you sure ?") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("Once your accept, User will be updated") }}.
      </p>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="modalFunctions['closeEditModal']">
          {{ $t("Cancel") }}
        </SecondaryButton>
        
        <PrimaryButton class="ml-3" @click="modalFunctions['confirmEdit']">
          {{ $t("edit") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <Modal :show="complete_archiving_status" @close="modalFunctions['closeCompleteArchivingStatusModal']">
    <div class="p-6 dark:bg-gray-800">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 ">
        {{ $t("Final project approval") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("Please attach the beneficiary list using the approved template so the program can read and add it to the database") }}.
      </p>
      
      <div class="auto-cols-max">
          <InputLabel for="beneficiariesFile" value="List of beneficiaries" />
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ $t("To download the approved beneficiary list template") }} <a download="BeneficiariesFileTemplate.xlsx" href="/assets/templates/BeneficiariesFileTemplate.xlsx">{{ $t("Click Here") }}</a>
          </p>
          <FileInput
            id="beneficiariesFile"
            model="proposal"
            :file_input_help="$t('Spreadsheets')"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            v-model="beneficiariesFile"
            attachment_type="2"
            :show_files_details="false"
            @fileinput-change="modalFunctions['beneficiariesFileinputChanged']"
            @finish-uploading="isFileInputLoading = false"
            @start-uploading="isFileInputLoading = true"
          />
      </div> 
      

      <div class="mt-6 flex justify-end">
        
        <SecondaryButton @click="modalFunctions['closeCompleteArchivingStatusModal']">
          {{ $t("Cancel") }}
        </SecondaryButton>


        <PrimaryButton class="ml-3" @click="modalFunctions['confirmCompleteArchivingStatusModal']">
          {{ $t("approve") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <Modal :show="complete_execution_status" @close="modalFunctions['closeCompleteExecutionStatusModal']">
    <div class="p-6 dark:bg-gray-800">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 ">
        {{ $t("Project approval to proceed to the archiving phase") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("Please attach the project's general documentation files") }}.
      </p>
      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="auto-cols">
          <InputLabel for="arabicVideoFile" value="Documentation video in Arabic" />
          <FileInput
            id="arabicVideoFile"
            model="proposal"
            v-model="arabicVideoFile"
            :file_input_help="$t('Videos and Images')"
            attachment_type="2"
            :show_files_details="false"
            @fileinput-change="modalFunctions['arabicFileinputChanged']"
            @finish-uploading="isFileInputLoading = false"
            @start-uploading="isFileInputLoading = true"
          />
      </div> 
      <div class="auto-cols">
          <InputLabel for="englishVideoFile" value="Documentation video in English" />
          <FileInput
            id="englishVideoFile"
            model="proposal"
            :file_input_help="$t('Videos and Images')"
            v-model="englishVideoFile"
            attachment_type="3"
            :show_files_details="false"
            @fileinput-change="modalFunctions['englishFileinputChanged']"
            @finish-uploading="isFileInputLoading = false"
            @start-uploading="isFileInputLoading = true"
          />
      </div> 
      </div> 

      <div class="mt-6 flex justify-end">
        
        <SecondaryButton @click="modalFunctions['closeCompleteExecutionStatusModal']">
          {{ $t("Cancel") }}
        </SecondaryButton>


        <PrimaryButton class="ml-3" @click="modalFunctions['confirmCompleteExecutionStatusModal']">
          {{ $t("approve") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <Modal :show="complete_donating_status" @close="modalFunctions['closeCompleteDonatingStatusModal']">
    <div class="p-6 dark:bg-gray-800">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 ">
        {{ $t("Project approval to proceed to documentation and implementation phase") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("If the donations are from outside the program, please enter the total donation amount") }}.
      </p>
      <div class="grid grid-cols-3 gap-4 mt-5">
        <div class="auto-cols-max">
          <InputLabel for="hasDonatingAmount" value="hasDonatingAmount" />
          <SwitchInput 
            id="hasDonatingAmount"
            v-model="hasDonatingAmount"
          />
      </div> 
      <div  v-show="hasDonatingAmount" class="col-span-2">
          <InputLabel for="donatingAmount" value="donatingAmount" />
          <TextInput
            id="donatingAmount"
            type="number"
            class="mt-1 block w-full"
            v-model="donatingAmount"
            required
            autofocus
            autocomplete="publishing_date"
           
          />
      </div> 
      </div> 

      <div class="mt-6 flex justify-end">
        
        <SecondaryButton @click="modalFunctions['closeCompleteDonatingStatusModal']">
          {{ $t("Cancel") }}
        </SecondaryButton>


        <PrimaryButton class="ml-3" @click="modalFunctions['confirmCompleteDonatingStatusModal']">
          {{ $t("approve") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>

  <div v-if="!isEmpty(table_filters)" class="p-4 shadow-md sm:rounded-lg my-2 dark:bg-gray-800 relative ">
    <div v-show="isFilterLoading" role="status" class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
        <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
        <span class="sr-only">Loading...</span>
    </div>
    
    <h2 
    class="py-2  text-center capitalize text-lg font-medium text-gray-900 dark:text-gray-100 cursor-pointer" 
    :data-collapse-toggle="'filter_dropdown'"
    
    >
    {{ $t('Filters') }}
  </h2>
    <div class="grid grid-cols-4 gap-4" id ="filter_dropdown">
      <template v-for="e in table_filters" :key="e">
        <div v-if="e.type && e.type != 'select'">
          <InputLabel :for="e.model + '_filter'" :value="e.name" />
          <TextInput
            :id="e.model + '_filter'"
            :type="e.type"
            class="mt-1 block w-full"
            v-model="filters[e.model]"
            :disabled="isFilterLoading"
          />
        </div>
        <div v-else>
          <InputLabel :for="e.model + '_filter'" :value="e.name" />
          <SelectInput
            :dir="this.$i18n.locale == 'ar' ? 'rtl' : 'ltr'"
            classes="mt-1 block w-full"
            :id="e.model + '_filter'"
            v-model="filters[e.model]"
            :options="e.options"
            :item_name="e.item_name || null"
            :disabled="isFilterLoading"
          />
        </div>
      </template>
        
    </div>
  </div>
  <div class="relative shadow-md sm:rounded-lg" >
    
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
          data-tooltip-target="add-item-tooltip"
          data-tooltip-trigger="hover"
        >
          <div
            id="add-item-tooltip"
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
                @click.prevent="handleSort(head.key)"
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
              @modal_function="modal_function"
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
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SwitchInput from "@/Components/SwitchInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { debounce } from "lodash";
import { isEmpty } from "lodash";
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
const upload_document_file = ref(false);
const edit_dialog = ref(false);
const delete_dialog = ref(false);
const block_dialog = ref(false);
const fileuploadInputTrigger = ref(0);
const isFileInputLoading = ref(false);
const isFilterLoading = ref(false);
const documentFiles = ref(false);
const beneficiariesFile = ref(false);
const arabicVideoFile = ref(false);
const englishVideoFile = ref(false);
const complete_archiving_status = ref(false);
const complete_execution_status = ref(false);
const complete_donating_status = ref(false);
const hasDonatingAmount = ref(0);
const donatingAmount = ref(0);

const modal_item = ref();

const statuses = {
  user: {
    "open": 1,
    "blocked": 3,
  },
  warehouse: {
    "open": 1,
    "blocked": 4
  }
};
const form = useForm({
  files: [],
  attachable_id: "",
  attachment_type: "",
  attachable_type: props.model,
});

const filters = reactive({
});
if(!isEmpty(props.table_filters)){
  props.table_filters.forEach((e) => {if(!e.type || e.type == 'select') filters[e.model] = 0});
}



const debouncedReload = debounce((newValue) => {
  isFilterLoading.value = true;
  router.reload({
    only: [props.refresh_only],
    data: { ...newValue, forms_page: 1 },
    onStart: () => {
      isFilterLoading.value = true
    },
    onFinish: () => {
      isFilterLoading.value = false
    }
  });
  table_key.value++;
}, 300); // Adjust debounce time in milliseconds (200ms here)
watch(filters, (newValue, old) => {
  debouncedReload(newValue);
});

watch(hasDonatingAmount, (newVal, oldVal) => {
  if (!newVal) {
    donatingAmount.value = 0; // Set to 0 when false
  }else{
    donatingAmount.value = modal_item.value.cost; 
  }
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
    case "document":
      switch (item['is_attached']) {
        case true:
          return "green_ths"
        case false:
          return "grey_ths"
        default:
          return ""
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
  upload_document_file.value = !upload_document_file.value
}
function handelDbClick(item) {
  if (props.model) {
    console.log("GFGFGF");
    console.log(props.model + ".edit, item.id");
    router.get(route(`${props.model}.edit`, item.id));
  }
}
const modalFunctions = {
  closeDocumentFileModal: function () {
    upload_document_file.value = false;
  },
  closeEditModal: function (item) {
    edit_dialog.value = false;
  },
  closeDeleteModal: function (item) {
    delete_dialog.value = false;
  },
  closeBlockModal: function (item) {
    block_dialog.value = false;
  },
  editing: function (item) {
    modal_item.value = item;
    edit_dialog.value = true;
  },
  deleting: function (item) {
    modal_item.value = item;
    delete_dialog.value = true;
  },
  blocking: function (item) {
    modal_item.value = item;
    block_dialog.value = true;
  },
  confirmEdit: function (item) {
    router.get(route(`${props.model}.edit`, modal_item.value.id));
    modalFunctions['closeEditModal'](item);
  },
  confirmDelete: function (item) {
    router.delete(route(`${props.model}.destroy`, modal_item.value.id));
    modalFunctions['closeDeleteModal'](item);
  },
  confirmBlock: function (item) {
    console.log("Item Status: " + statuses[props.model]['blocked'] + " " + statuses[props.model]['open'] + " " + modal_item.value.status)
    router.put(route(`${props.model}.update`, modal_item.value.id), {
      status: modal_item.value.status == 1 ? statuses[props.model]['blocked'] : statuses[props.model]['open']
    });
    modalFunctions['closeBlockModal'](item);
  },
  documentFileinputChanged: function (files) {
    documentFiles.value = files;
  },
  beneficiariesFileinputChanged: function (files) {
    beneficiariesFile.value = files;
  },
  arabicFileinputChanged: function (files) {
    arabicVideoFile.value = files;
  },
  englishFileinputChanged: function (files) {
    englishVideoFile.value = files;
  },
  closeCompleteArchivingStatusModal: function (item) {
    complete_archiving_status.value = false;
  },
  completingArchivingStatus: function (item) {
    modal_item.value = item;
    complete_archiving_status.value = true;
  },
  confirmCompleteArchivingStatusModal: function (item) {
    if(!beneficiariesFile.value) return false;
    router.post(route(`${props.model}.update`, modal_item.value.id), {
      _method: 'put',
      status: 8,
      beneficiariesFile: beneficiariesFile.value,
    });
    modalFunctions['closeCompleteArchivingStatusModal'](item);
  },
  closeCompleteExecutionStatusModal: function (item) {
    complete_execution_status.value = false;
  },
  completingExecutionStatus: function (item) {
    modal_item.value = item;
    complete_execution_status.value = true;
  },
  confirmCompleteExecutionStatusModal: function (item) {
    if(!arabicVideoFile.value) return false;
    router.post(route(`${props.model}.update`, modal_item.value.id), {
      _method: 'put',
      status: 3,
      arabicVideoFile: arabicVideoFile.value,
      englishVideoFile: englishVideoFile.value,
    },{forceFormData: true});
    modalFunctions['closeCompleteExecutionStatusModal'](item);
  },
  closeCompleteDonatingStatusModal: function (item) {
    complete_donating_status.value = false;
  },
  completingDonatingStatus: function (item) {
    modal_item.value = item;
    donatingAmount.value = item.cost;
    complete_donating_status.value = true;
  },
  confirmCompleteDonatingStatusModal: function (item) {
    router.put(route(`${props.model}.update`, modal_item.value.id), {
      status: 2,
      donatingAmount: Number(donatingAmount.value),
    });
    modalFunctions['closeCompleteDonatingStatusModal']();
  },
  confirmDocumentFileUpload: function (item) {
    if(!documentFiles.value) return false;
    router.post(route('attachment.store'), {
      files: documentFiles.value,
      attachable_id: modal_item.value.id,
      attachable_type: props.model,
      attachment_type: 1,
    },{forceFormData: true});
    modalFunctions['closeDocumentFileModal'](item);
  },
  uploadingDocumentFile: function (item) {
    modal_item.value = item;
    upload_document_file.value = true;
  },
}
function modal_function(funcName, item) {
  modalFunctions[funcName](item);
}
function urlParams() {
  return "?" + new URLSearchParams(filters).toString();
}
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
