<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Alert from "@/Components/Alert.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <div>
    <Alert />
    <slot />
  </div>
</template>
