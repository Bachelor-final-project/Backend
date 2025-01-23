<template>
    <Modal :show="complete_donating_status" @close="closeCompleteDonatingStatusModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ $t("Are you sure you want to complete donating status?") }}
        </h2>
  
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("Once your accept, User will be updated") }}.
        </p>
  
        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeCompleteDonatingStatusModal">
            {{ $t("Cancel") }}
          </SecondaryButton>
          <TextInput
              id="donatingAmount"
              type="number"
              class="mt-1 block w-full"
              v-model="form.title"
              required
              autofocus
              autocomplete="name"
          >
  
          </TextInput>
          <PrimaryButton class="ml-3" @click="confirmCompleteDonatingStatusModal">
            {{ $t("approve") }}
          </PrimaryButton>
        </div>
      </div>
    </Modal>

  </template>
  
  <script>
  import SecondaryButton from "@/Components/SecondaryButton.vue";
  import PrimaryButton from "@/Components/PrimaryButton.vue";
  import TextInput from "@/Components/TextInput.vue";
  import Modal from "@/Components/Modal.vue";
  import { router } from "@inertiajs/vue3";
  
  export default {
    components: {
      SecondaryButton,
      PrimaryButton,
      Modal,
    },
    props: ["action", "item", "complete_donating_status"],
  
    methods: {
      
      closeCompleteDonatingStatusModal() {
        this.complete_donating_status = false;
      },
      confirmCompleteDonatingStatusModal() {
        router.put(route(`${this.action.model}.update`, this.item.id), {
          donated_amount: 100,
          status: 2,
        });
        this.closeCompleteDonatingStatusModal();
      },
      completingDonatingStatus() {
        this.complete_donating_status = true;
      },
    },
  };
  </script>
  
  <style></style>
  