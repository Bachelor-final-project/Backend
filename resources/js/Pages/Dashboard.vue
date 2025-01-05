<script setup>
import { Head } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref, reactive, watch } from "vue";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Table from "@/Components/Table.vue";
import Test from "@/Components/Test.vue";
// import { Head } from "@inertiajs/vue3";
import OldContent from "@/Components/Dashboard/OldContent.vue";
import ColumnChart from "@/Components/Dashboard/Charts/Column.vue";
import StackedGroup from "@/Components/Dashboard/Charts/StackedGroup.vue";
import Pointed from "@/Components/Dashboard/Charts/Pointed.vue";
import BasicColumn from "@/Components/Dashboard/Charts/BasicColumn.vue";
import Group from "@/Components/Dashboard/Charts/Group.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AgentLayout from "@/Layouts/AgentLayout.vue";
import Edit from "./Profile/Edit.vue";
import { Link } from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import AgentsCard from "@/Components/AgentsCard.vue";
import { router } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();
let render_counter = ref(0);
let color_theme = ref(localStorage.getItem("color-theme"));

onMounted(() => {
  window.addEventListener("color-theme-localstorage-changed", (event) => {
    color_theme.value = event.detail.storage;
  });
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });
});

</script>

<template>
    <Head title="Dashboard" />

   
        <template>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard hi
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        You're logged in! 
                    </div>
                </div>
            </div>
        </div>
   
</template>
