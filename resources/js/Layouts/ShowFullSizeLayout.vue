<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { twMerge } from "tailwind-merge";
import { Link } from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3'

defineProps({
  classes: String,
});
const goBack = () => {
  window.history.length > 1 ? window.history.back() : (router.visit('/'));
};

</script>

<template>
  <div class="flex justify-end  px-6">

          <button
          @click="goBack"
          class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
        >
          {{ $t("Go Back") }}
        </button>
    </div>
  <div
    class="flex flex-col  items-center pt-6 sm:pt-0 "
  >
  
    <div
      :class="twMerge('w-full   px-6 py-4  overflow-hidden sm:rounded-lg   overflow-y-auto	',classes)"
    >

      <slot />
    </div>
  </div>
</template>
