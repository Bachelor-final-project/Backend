<template>
  <Modal :show="delete_dialog" @close="closeModal">
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
        <SecondaryButton @click="closeModal">
          {{ $t("Cancel") }}
        </SecondaryButton>

        <DangerButton class="ml-3" @click="confirmDelete">
          {{ $t("delete") }}
        </DangerButton>
      </div>
    </div>
  </Modal>
  <Modal :show="block_dialog" @close="closeBlockModal">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $t("Are you sure ?") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("Once your accept, User will be updated") }}.
      </p>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeBlockModal">
          {{ $t("Cancel") }}
        </SecondaryButton>

        <DangerButton class="ml-3" @click="confirmBlock">
          {{ item.status == "active" ? $t("block") : $t("activate") }}
        </DangerButton>
      </div>
    </div>
  </Modal>
  <Modal :show="edit_dialog" @close="closeEditModal">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ $t("Are you sure ?") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("Once your accept, User will be updated") }}.
      </p>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeEditModal">
          {{ $t("Cancel") }}
        </SecondaryButton>

        <PrimaryButton class="ml-3" @click="confirmEdit">
          {{ $t("edit") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <Modal :show="complete_donating_status" @close="closeCompleteDonatingStatusModal">
    <div class="p-6 dark:bg-gray-800">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 ">
        {{ $t("titleForCompleteDonatingStatusModal") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("bodyForCompleteDonatingStatusModal") }}.
      </p>
      <div class="grid grid-cols-3 gap-4 mt-5">
        <div class="auto-cols-max">
          <InputLabel for="hasDonatingAmount" value="hasDonatingAmount" />
          <SwitchInput 
            id="hasDonatingAmount"
            v-model="this.hasDonatingAmount"
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
        
        <SecondaryButton @click="closeCompleteDonatingStatusModal">
          {{ $t("Cancel") }}
        </SecondaryButton>


        <PrimaryButton class="ml-3" @click="confirmCompleteDonatingStatusModal">
          {{ $t("approve") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <Modal :show="complete_execution_status" @close="closeCompleteExecutionStatusModal">
    <div class="p-6 dark:bg-gray-800">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 ">
        {{ $t("titleForCompleteExecutionStatusModal") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("bodyForCompleteExecutionStatusModal") }}.
      </p>
      <div class="grid grid-cols-2 gap-4 mt-5">
        <div class="auto-cols">
          <InputLabel for="arabicVideoFile" value="arabicVideoFile" />
          <FileInput
            id="arabicVideoFile"
            model="proposal"
            v-model="arabicVideoFile"
            :file_input_help="$t('Videos and Images')"
            attachment_type="2"
            :show_files_details="false"
            @fileinput-change="arabicFileinputChanged"
            @finish-uploading="isFileInputLoading = false"
            @start-uploading="isFileInputLoading = true"
          />
      </div> 
      <div class="auto-cols">
          <InputLabel for="englishVideoFile" value="englishVideoFile" />
          <FileInput
            id="englishVideoFile"
            model="proposal"
            :file_input_help="$t('Videos and Images')"
            v-model="englishVideoFile"
            attachment_type="3"
            :show_files_details="false"
            @fileinput-change="englishFileinputChanged"
            @finish-uploading="isFileInputLoading = false"
            @start-uploading="isFileInputLoading = true"
          />
      </div> 
      </div> 

      <div class="mt-6 flex justify-end">
        
        <SecondaryButton @click="closeCompleteExecutionStatusModal">
          {{ $t("Cancel") }}
        </SecondaryButton>


        <PrimaryButton class="ml-3" @click="confirmCompleteExecutionStatusModal">
          {{ $t("approve") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <Modal :show="complete_archiving_status" @close="closeCompleteArchivingStatusModal">
    <div class="p-6 dark:bg-gray-800">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 ">
        {{ $t("titleForCompleteArchivingStatusModal") }}
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ $t("bodyForCompleteArchivingStatusModal") }}.
      </p>
      
      <div class="auto-cols-max">
          <InputLabel for="beneficiariesFile" value="beneficiariesFile" />
          <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              {{ $t("toDownloadTheBeneficiariesFileTemplate") }} <a download="BeneficiariesFileTemplate.xlsx" href="/assets/templates/BeneficiariesFileTemplate.xlsx">{{ $t("Click Here") }}</a>
          </p>
          <FileInput
            id="beneficiariesFile"
            model="proposal"
            :file_input_help="$t('Spreadsheets')"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            v-model="beneficiariesFile"
            attachment_type="2"
            :show_files_details="false"
            @fileinput-change="beneficiariesFileinputChanged"
            @finish-uploading="isFileInputLoading = false"
            @start-uploading="isFileInputLoading = true"
          />
      </div> 
      

      <div class="mt-6 flex justify-end">
        
        <SecondaryButton @click="closeCompleteArchivingStatusModal">
          {{ $t("Cancel") }}
        </SecondaryButton>


        <PrimaryButton class="ml-3" @click="confirmCompleteArchivingStatusModal">
          {{ $t("approve") }}
        </PrimaryButton>
      </div>
    </div>
  </Modal>
  <span class="p-1">
   
    <button
      v-on:click="
        typeof action.funcName == 'string'
          ? this[action.funcName](action.model)
          : action.funcName()
      "
      v-if="action.type == 'btn'"
      type="button"
      class="text-white px-1 py-1 hover:bg-gray-100"
    >
      <f-icon
        v-if="action.icon"
        :color="action.icon_color || ''"
        :icon="action.icon"
        class="text-xl"
      ></f-icon>
    </button>

    <a
      :href="generateUrl(action.route, action.queryParams)"
      class="text-white px-1 py-1 hover:bg-gray-100"
      :title="action.tooltip"
      v-else-if="action.type == 'href'"
    >
      <f-icon
        v-if="action.icon"
        :color="action.icon_color || ''"
        :icon="action.icon"
        class="text-xl"
      ></f-icon>
    </a>
  </span>
</template>

<script>
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SwitchInput from "@/Components/SwitchInput.vue";
import Modal from "@/Components/Modal.vue";
import { router } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import FileInput from "@/Components/FileInput.vue";

export default {
  components: {
    DangerButton,
    SecondaryButton,
    PrimaryButton,
    TextInput,
    InputLabel,
    SwitchInput,
    Modal,
    FileInput,
  },
  props: ["action", "item"],
  data: () => {
 
    return {
      t: useI18n(),
      delete_dialog: false,
      block_dialog: false,
      edit_dialog: false,
      complete_donating_status: false,
      complete_execution_status: false,
      complete_archiving_status: false,
      hasDonatingAmount: false,
      donatingAmount: 0,
      arabicVideoFile: null,
      englishVideoFile: null,
      beneficiariesFile: null,
      statuses: {
        user: {
          "open": 1,
          "blocked": 3,
        },
        warehouse: {
          "open": 1,
          "blocked": 4
        }
      }
    };
  },
  created() {
    this.donatingAmount = this.item.cost; // Access `this.item.cost` here
  },
  watch: {
    hasDonatingAmount(newVal) {
      if (!newVal) {
        this.donatingAmount = 0; // Set to 0 when false
      }else{
        this.donatingAmount = this.item.cost; 
      }
    }
  },
  methods: {
    closeModal() {
      this.delete_dialog = false;
    },
    closeBlockModal() {
      this.block_dialog = false;
    },
    closeEditModal() {
      this.edit_dialog = false;
    },
    closeCompleteDonatingStatusModal() {
      this.complete_donating_status = false;
    },
    closeCompleteExecutionStatusModal() {
      this.complete_execution_status = false;
    },
    closeCompleteArchivingStatusModal() {
      this.complete_archiving_status = false;
    },
    confirmDelete() {
      router.delete(route(`${this.action.model}.destroy`, this.item.id));
    },
    confirmBlock() {
      router.put(route(`${this.action.model}.update`, this.item.id), {
        status: this.item.status == 1 ? this.statuses[this.action.model].blocked : this.statuses[this.action.model].open
      });
      this.closeBlockModal();
    },
    confirmEdit() {
      router.get(route(`${this.action.model}.edit`, this.item.id));
      this.closeBlockModal();
    },
    approveDonation() {
      router.put(route(`${this.action.model}.update`, this.item.id), {
        status: this.item.status = 2, // approved status value 2
      });
    },
    rejectDonation() {
      router.put(route(`${this.action.model}.update`, this.item.id), {
        status: this.item.status = 3, // rejected status value 3
      });
    },
    confirmCompleteDonatingStatusModal() {
      router.put(route(`${this.action.model}.update`, this.item.id), {
        status: 2,
        donatingAmount: Number(this.donatingAmount),
      });
      this.closeCompleteDonatingStatusModal();
    },
    confirmCompleteExecutionStatusModal() {
      if(!this.arabicVideoFile) return false;
      router.post(route(`${this.action.model}.update`, this.item.id), {
        _method: 'put',
        status: 3,
        arabicVideoFile: this.arabicVideoFile,
        englishVideoFile: this.englishVideoFile,
      },{forceFormData: true});
      this.closeCompleteExecutionStatusModal();
    },
    confirmCompleteArchivingStatusModal() {
      if(!this.beneficiariesFile) return false;
      router.post(route(`${this.action.model}.update`, this.item.id), {
        _method: 'put',
        status: 8,
        beneficiariesFile: this.beneficiariesFile,
      });
      this.closeCompleteArchivingStatusModal();
    },
    blocking() {
      this.block_dialog = true;
    },
    editing() {
      this.edit_dialog = true;
    },
    deleting() {
      this.delete_dialog = true;
    },
    completingDonatingStatus() {
      this.complete_donating_status = true;
    },
    completingExecutionStatus() {
      this.complete_execution_status = true;
    },
    completingArchivingStatus() {
      this.complete_archiving_status = true;
    },
    generateUrl(routeName, queryParams = {}) {
      // Generate the base URL using the Ziggy `route` function
      console.log("generatedURL");
      console.log(this.t.locale);
      let url = route(routeName);
      console.log(url);
      const queryParamObj = new URLSearchParams()

      for (const [key, value] of Object.entries(queryParams)) {
        console.log("dynamic value: ");
        console.log(value);
        let dynamicValue = this.item[value] || value;
        console.log("fdgf" + dynamicValue);
        queryParamObj.append(key, dynamicValue);
      }
      if (queryParamObj.toString()) {
        url += `?${queryParamObj.toString()}`;
      }
      console.log(url);
      return url;
    },
    arabicFileinputChanged(files) {
      this.arabicVideoFile = files;
    },
    englishFileinputChanged(files) {
      this.englishVideoFile = files;
    },
    beneficiariesFileinputChanged(files) {
      this.beneficiariesFile = files;
    }
  },
};
</script>

<style></style>
