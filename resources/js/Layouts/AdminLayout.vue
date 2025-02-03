
<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
let type = ref(2);
const page = usePage();
type.value = page.props.auth.user.type;
console.log(type.value);
</script>
<template>
  <div >
    <slot />
  </div>
</template>
 