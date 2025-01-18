<template>
  <Modal :show="delete_dialog" @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
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
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
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

export default {
  components: {
    DangerButton,
    SecondaryButton,
    PrimaryButton,
    TextInput,
    InputLabel,
    SwitchInput,
    Modal,
  },
  props: ["action", "item"],
  data: () => {
 
    return {
      delete_dialog: false,
      block_dialog: false,
      edit_dialog: false,
      complete_donating_status: false,
      hasDonatingAmount: false,
      donatingAmount: 0,
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
    confirmCompleteDonatingStatusModal() {
      router.put(route(`${this.action.model}.update`, this.item.id), {
        status: 2,
        donatingAmount: Number(this.donatingAmount),
      });
      this.closeCompleteDonatingStatusModal();
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
  },
};
</script>

<style></style>
