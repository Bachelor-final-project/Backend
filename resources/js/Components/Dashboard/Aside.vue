 <script setup>
import menus from "../../utils/aside_menu.js";
import ItemList from "@/Components/ItemList.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, onMounted } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
const page = usePage();
const menu = computed(() => menus[page.props.auth.user.type - 1]);


</script>

<template>
  <aside
    :class="i18n_locale == 'en' ? '-translate-x-full' : 'translate-x-full'"
    id="logo-sidebar"
    class="fixed top-0 start-0 z-40 w-64 h-screen transition-transform border-r border-gray-200 sm:translate-x-0 bg-gray-800 dark:border-gray-700"
    aria-label="Sidebar"
  >
    <div
      class="h-full px-3 pb-4 overflow-y-auto block bg-gray-800 dark:bg-gray-800"
    >
      <Link :href="route('dashboard')">
        <ApplicationLogo class="fill-current text-gray-500" />
      </Link>
      <ul class="space-y-3 mt-5 font-medium">
        <li :key="index" v-for="(menu_item, index) in menu">
          <ItemList :item="menu_item" />
        </li>
      </ul>
    </div>
  </aside>
</template>



