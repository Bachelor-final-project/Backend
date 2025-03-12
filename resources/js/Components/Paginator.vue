<template>
  <div v-if="links.length > 3" class="flex items-center justify-between">
    
    <div class="flex flex-wrap">

      <template v-for="(link, key) in links">
        <div
          v-if="link.url === null"
          :key="key"
          class="rounded px-4 py-3 text-gray-400 text-sm leading-4"
        >
          {{ $t(link.label) }}
        </div>
        <Link
          preserve-scroll
          preserve-state
          :data="getParams(link.url)"
          v-else
          :key="`link-${key}`"
          class="rounded px-4 py-3 text-sm leading-4 hover:bg-slate-400 dark:text-white"
          :class="{ 'bg-slate-500 text-white ': link.active }"
          :href="link.url"
          >{{ $t(link.label) }}</Link
        >
      </template>
    </div>


    <!-- Per Page Selector -->
    <div class="px-4">
      <label for="per-page" class="mr-2 text-sm text-gray-700 dark:text-white">{{ $t("Show") }}</label>
      <select
        id="per-page"
        class="rounded mx-2 min-w-16 px-2 border-gray-300 text-sm dark:bg-gray-800 dark:text-white"
        v-model="selectedPerPage"
        @change="updatePerPage"
      >
        <option v-for="option in perPageOptions" :key="option" :value="option">
          {{ option }}
        </option>
      </select>
      <span class="ml-2 text-sm text-gray-700 dark:text-white">{{ $t("entries") }}</span>
    </div>


  </div>
</template>

<script setup>
import { ref, watchEffect } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  links: Array,
  params: Object,
});

// Per Page Selection
const perPageOptions = [15, 25, 50, 100]; // Customizable options
const selectedPerPage = ref(props.params.per_page || 15);

const updatePerPage = () => {
  let updatedParams = { ...props.params, per_page: selectedPerPage.value, page: 1 };
  router.get(location.pathname, updatedParams, { preserveState: true, preserveScroll: true });
};


const convertParamsToObj = (params) => {
  if (!params) return ({});
  var search = params.substring(1);
  return JSON.parse(
    '{"' +
      decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') +
      '"}'
  );
};
const getParams = (url) => {
  let past_params = convertParamsToObj(location.search);
  const params = new URL(url).search;

  let url_params = convertParamsToObj(params);

  return { ...props.params, ...past_params, ...url_params };
};
</script>
