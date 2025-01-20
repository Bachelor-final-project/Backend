<template>
  <Head :title="$t('entity')" />
  <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
    <div class="container m-auto px-4 h-full">
      <h1 class="text-4xl font-bold text-center text-gray-800 mb-10 text-gray-900 dark:text-gray-100">{{ $t('Donating form is saved successfully') }}</h1>
      
  </div>
  </div>
</template>
<script setup>
import { router } from "@inertiajs/vue3";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import Table from "@/Components/Table.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DonatingPageLayout from '@/Layouts/DonatingPageLayout.vue';
defineOptions({ layout: DonatingPageLayout });
const props = defineProps({
    donor: Array,
    donations: Array,
  });

</script>
