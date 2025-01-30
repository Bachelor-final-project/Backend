<template>
  <span class="p-1">
   
    <button
      v-on:click="
        typeof action.funcName == 'string'
          ? this[action.funcName](action.model)
          : action.funcName()
      "
      v-if="action.type == 'btn'"
      type="button"
      class="text-white px-1 py-1 hover:bg-gray-100"
    >
      <f-icon
        v-if="action.icon"
        :color="action.icon_color || ''"
        :icon="action.icon"
        class="text-2xl "
      ></f-icon>
    </button>

    <a
      :href="action.includeId? generateUrl(action.route, action.queryParams, item.id): generateUrl(action.route, action.queryParams)"
      class="text-white px-1 py-[0.75rem] hover:bg-gray-100"
      :title="action.tooltip"
      v-else-if="action.type == 'href'"
    >
      <f-icon
        v-if="action.icon"
        :color="action.icon_color || ''"
        :icon="action.icon"
        class="text-2xl solid tex"
      ></f-icon>
    </a>
  </span>
</template>

<script>
import { useI18n } from "vue-i18n";

export default {
  props: ["action", "item"],
  data: () => {
    return {
      t: useI18n(),
    };
  },
  methods: {
    blocking() {
      this.$emit('modal_function', 'blocking', this.item);
      // this.block_dialog = true;
    },
    editing() {
      this.$emit('modal_function', 'editing', this.item);
      // this.$emit('editing', this.item.id);
      // this.edit_dialog = true;
    },
    deleting() {
      this.$emit('modal_function', 'deleting', this.item);
      // this.$emit('deleting', this.item.id);
      // this.delete_dialog = true;
    },
    uploadingDocumentFile() {
      this.$emit('modal_function', 'uploadingDocumentFile', this.item);
      // this.complete_donating_status = true;
    },
    completingDonatingStatus() {
      this.$emit('modal_function', 'completingDonatingStatus', this.item);
      // this.complete_donating_status = true;
    },
    completingExecutionStatus() {
      this.$emit('modal_function', 'completingExecutionStatus', this.item);
      // this.complete_execution_status = true;
    },
    completingArchivingStatus() {
      this.$emit('modal_function', 'completingArchivingStatus', this.item);
      // this.complete_archiving_status = true;
    },
    generateUrl(routeName, queryParams = {}, id = null) {
      // Generate the base URL using the Ziggy `route` function
      let url = id? route(routeName, id) : route(routeName);
      const queryParamObj = new URLSearchParams()

      for (const [key, value] of Object.entries(queryParams)) {
        let dynamicValue = this.item[value] || value;
        queryParamObj.append(key, dynamicValue);
      }
      if (queryParamObj.toString()) {
        url += `?${queryParamObj.toString()}`;
      }
      return url;
    },
  },
};
</script>

<style></style>
