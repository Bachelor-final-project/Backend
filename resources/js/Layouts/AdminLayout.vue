
<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
let type = ref(2);
const page = usePage();
type.value = page.props.auth.user.type;
</script>
<template>
  <div v-if="type == 1">
    <slot />
  </div>
</template>
 