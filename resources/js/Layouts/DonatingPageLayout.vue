<script>
import Aside from "@/Components/Dashboard/Aside.vue";
import GuestAppBar from "@/Components/Dashboard/GuestAppBar.vue";
import OldContent from "@/Components/Dashboard/OldContent.vue";
import Alert from "@/Components/Alert.vue";
export default {
  components: {
    OldContent,
    Aside,
    GuestAppBar,
    Alert,
  },
};
</script>
<template>
  <GuestAppBar />
  
  <Alert />
  <div class="p-4  min-h-full">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
      <slot />
    </div>
  </div>
</template>