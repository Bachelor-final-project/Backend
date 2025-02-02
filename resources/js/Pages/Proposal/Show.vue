<script setup>
import { usePage } from "@inertiajs/vue3";
import ShowFullSizeLayout from "@/Layouts/ShowFullSizeLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import Table from "@/Components/Table.vue";

const i18nLocale = useI18n();
const props = defineProps({
  proposal: Object,
  currencies: Array,
  entities: Array,
  proposal_types: Array,
  areas: Array,
  donations: Array,
  donations_headers: Array,
  documents: Array,
  documents_headers: Array,
  beneficiaries: Array,
  beneficiaries_headers: Array,
});


const donations_table_actions = [
 
  {
    type: "btn",
    icon: "edit",
    icon_color: "grey",
    funcName: "edit",
    tooltip: "edit donation",
    showFunc: function(item){
        return item.status == 0;
    }
  },
  {
    type: "btn",
    icon: "ban",
    icon_color: "red",
    funcName: "rejectDonation",
    model: "donation",
    tooltip: "reject donation",
    showFunc: function(item){
        return item.status == 0;
    }
  },
  {
    type: "btn",
    icon: "check",
    icon_color: "green",
    funcName: "approveDonation",
    model: "donation",
    tooltip: "approve donation",
    showFunc: function(item){
        return item.status == 0;
    }
  },
];
</script>

<template>
  <Head :title="$t('View Proposal')" />
  <ShowFullSizeLayout>
    <section class=" bg-white dark:bg-gray-800 my-4 px-6 py-4 shadow-md rounded-lg">
      <header>
        <h2 class="capitalize text-3xl text-center  font-medium text-gray-900 dark:text-gray-100">
          {{ $t("proposal details") }}
        </h2>
      </header>

      <div class="mt-6 space-y-6">
        <div class="grid grid-cols-4 gap-4">
          <div>
            <p class="ont-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("title") }}</p>
            <p class="text-gray-700 dark:text-gray-300">{{ proposal.title }}</p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("proposal type") }}</p>
            <p class="text-gray-700 dark:text-gray-300">
              {{ proposal.proposal_type_type_ar }}
            </p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("area") }}</p>
            <p class="text-gray-700 dark:text-gray-300">
              {{  proposal.area_name }}
            </p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("entity") }}</p>
            <p class="text-gray-700 dark:text-gray-300">
              {{ proposal.entity_name }}
            </p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("cost") }}</p>
            <p class="text-gray-700 dark:text-gray-300">{{ proposal.cost }}</p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("share cost") }}</p>
            <p class="text-gray-700 dark:text-gray-300">{{ proposal.share_cost }}</p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("min documenting amount") }}</p>
            <p class="text-gray-700 dark:text-gray-300">{{ proposal.min_documenting_amount }}</p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("currency") }}</p>
            <p class="text-gray-700 dark:text-gray-300">
              {{ proposal.currency_name }}
            </p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("expected benificiaries count") }}</p>
            <p class="text-gray-700 dark:text-gray-300">{{ proposal.expected_benificiaries_count }}</p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("publishing date") }}</p>
            <p class="text-gray-700 dark:text-gray-300">{{ proposal.publishing_date }}</p>
          </div>

          <div>
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("execution date") }}</p>
            <p class="text-gray-700 dark:text-gray-300">{{ proposal.execution_date }}</p>
          </div>


          <div v-if="proposal.currency_id == 1">
            <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("can be paid online") }}</p>
            <p class="text-gray-700 dark:text-gray-300">
              {{ proposal.isPayableOnline ? $t("yes") : $t("no") }}
            </p>
          </div>
        </div>

        <div>
          <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("proposal body") }}</p>
          <p class="text-gray-700 dark:text-gray-300">{{ proposal.body }}</p>
        </div>

        <div>
          <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("proposal effects") }}</p>
          <p class="text-gray-700 dark:text-gray-300">{{ proposal.proposal_effects }}</p>
        </div>

        <div>
          <p class="font-medium dark:bg-gray-700 dark:text-gray-400">{{ $t("notes") }}</p>
          <p class="text-gray-700 dark:text-gray-300">{{ proposal.notes }}</p>
        </div>
      </div>
    </section>
    <section v-if="proposal.files.length > 0" class=" bg-white dark:bg-gray-800 my-4  pt-4 shadow-md rounded-lg">
      <header>
        <h2
        class="py-2 bg-white dark:bg-gray-800 text-center capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
      >
          {{ $t("Proposal Files") }}
        </h2>
      </header>

      <div class="">
        <!-- Table -->
        <table class="table-auto w-full text-sm text-start text-gray-500 dark:text-gray-400">
          <thead class="border-b-2 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th class="px-6 py-3 max-w-80">
                #
              </th>
              <th class="px-6 py-3 max-w-80">
                {{ $t("File Type") }}
              </th>
              <th class="px-6 py-3 max-w-80">
                {{ $t("File Name") }}
              </th>
              <th class="px-6 py-3 max-w-80">
                {{ $t("Link") }}
              </th>
            </tr>
          </thead>
          <tbody >
            <!-- Loop through files -->
            <tr v-for="(file, index) in proposal.files" :key="file.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 items-center">
              <!-- Counter -->
              <td class="px-6 py-2 max-w-80 text-pretty font-semibold text-black whitespace-nowrap dark:text-white">
                {{ index + 1 }}
              </td>
              <!-- File Type -->
              <td class="px-6 py-2 max-w-80 text-pretty font-semibold text-black whitespace-nowrap dark:text-white">
                {{ file.attachment_type_name }}
              </td>
              <!-- File Name -->
              <td class="px-6 py-2 max-w-80 text-pretty font-semibold text-black whitespace-nowrap dark:text-white">
                {{ file.filename }}
              </td>
              <!-- Link -->
              <td class="px-4 py-2 text-blue-600 dark:text-blue-400">
                <a :href="file.url" target="_blank" rel="noopener noreferrer" class="underline">
                  {{ $t("Download") }}
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
    <section v-if="documents.data.length > 0" class=" my-4 shadow-md rounded-lg">
      <div class="dark:text-white">
        <Table
          title="Documents"
          :items="documents"
          :headers="documents_headers"
          model="document"
          add_item_route="document.create"
          import_url="import-documents"
        />
      </div>
    </section>
    <section v-if="donations.data.length > 0" class=" my-4 shadow-md rounded-lg">
      <div class="dark:text-white">
        <Table
          title="Donations"
          :actions="donations_table_actions"
          :items="donations"
          :headers="donations_headers"
          model="donation"
          add_item_route="donation.create"
          import_url="import-donations"
        />
      </div>
    </section>
    

    <section v-if="beneficiaries.data.length > 0" class=" my-4 shadow-md rounded-lg">
      <div class="dark:text-white">
        <Table
          title="Beneficiaries"
          :actions="beneficiaries_table_actions"
          :items="beneficiaries"
          :headers="beneficiaries_headers"
          model="beneficiary"
          add_item_route="beneficiary.create"
          import_url="import-beneficiaries"
        />
      </div>
    </section>
    
  </ShowFullSizeLayout>
</template>
