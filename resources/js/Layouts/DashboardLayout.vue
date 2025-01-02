<script>
import Aside from "@/Components/Dashboard/Aside.vue";
import AppBar from "@/Components/Dashboard/AppBar.vue";
import OldContent from "@/Components/Dashboard/OldContent.vue";
import Alert from "@/Components/Alert.vue";
export default {
  components: {
    OldContent,
    Aside,
    AppBar,
    Alert,
  },
};
</script>
<template>
  <AppBar />
  <Aside />
  <Alert />
  <div class="p-4 sm:ms-64 min-h-full">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
      <slot />
    </div>
  </div>
</template>