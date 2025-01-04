<template>
  <div v-if="links.length > 3">
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
  </div>
</template>

<script setup>
const props = defineProps({
  links: Array,
  params: Object,
});
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
