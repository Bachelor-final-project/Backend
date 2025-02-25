<template v-if="item.allowedUserTypes.includes(user.type)">
  <Link
    data-drawer-target="logo-sidebar"
    data-drawer-hide="logo-sidebar"
    aria-controls="logo-sidebar"
    @click="handleActive"
    v-if="!item.items"
    :href="route(item.to)"
    :class="{
      ' bg-gray-700 dark:bg-gray-600':
        route(item.to) == route().current(),
    }"
    class="z-50 item-link flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-600 dark:hover:bg-gray-700"
  >
    <f-icon :icon="item.icon" />
    <span class="ms-3 z-10 item-link">{{ $t(item.title) }}</span>
  </Link>
  <div v-else>
    <button
      type="button"
      class="z-50 item-link flex items-center p-2 text-white rounded-lg dark:text-white hover:bg-gray-600 dark:hover:bg-gray-70 w-full"
      :aria-controls="`dropdown-${item.title}`"
      :data-collapse-toggle="`dropdown-${item.title}`"
    >
      <f-icon :icon="item.icon" />
      <span
        class="flex-1 ms-3 text-start whitespace-nowrap"
        sidebar-toggle-item
        >{{ $t(item.title) }}</span
      >
      <svg
        sidebar-toggle-item
        class="w-6 h-6"
        fill="currentColor"
        viewBox="0 0 20 20"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          fill-rule="evenodd"
          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
          clip-rule="evenodd"
        ></path>
      </svg>
    </button>
    <ul :id="`dropdown-${item.title}`" class="hidden py-2 space-y-2">
      <template v-for="(sub_item, index) in item.items" :key="index" >
        <li v-if="sub_item.allowedUserTypes.includes(user.type)" >
          <Link
            data-drawer-target="logo-sidebar"
            data-drawer-hide="logo-sidebar"
            aria-controls="logo-sidebar"
            :href="route(sub_item.to)"
            class="acrive flex items-center w-full p-2 text-white transition duration-75 rounded-lg ps-11 group hover:bg-gray-600 dark:hover:bg-gray-70 dark:text-white dark:hover:bg-gray-700"
            >{{ $t(sub_item.title) }} </Link
          >
        </li>
      </template>
    </ul>
  </div>
</template>

<script>

import { usePage } from '@inertiajs/vue3';

export default {

  props: ["item"],
  computed: {
    user(){
      // console.log('usePage()');
      // console.log(usePage().props.auth.user);
      return usePage().props.auth.user;
    }
  },
  methods: {
    handleActive(event) {
      let elements = document.querySelectorAll(".item-link");
      elements.forEach(function (element) {
        element.classList.remove("bg-gray-700", "dark:bg-gray-600");
      });

      if (event.target.parentElement.tagName == "A") {
        event.target.parentElement.className += " bg-gray-700 dark:bg-gray-600"; //parent of "target"
      } else {
        event.target.className += " bg-gray-700 dark:bg-gray-600";
      }
    },
  },
};
</script>

<style>
</style>
