<template>
  <div class="dark:text-white">
    <Table
      title="proposal table"
      :actions="actions"
      :items="items"
      :headers="headers"
    />
  </div>
</template>
<script>
import { router } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
export default {
  components: { Table },
  data: () => {
    return {
      actions: [
        {
          type: "btn",
          icon: "trash",
          icon_color: "grey",
          funcName: "deleting",
          tooltip: "delete user",
        },
        {
          type: "btn",
          icon: "ban",
          icon_color: "#f5425a",
          funcName: "blocking",
          tooltip: "block user",
        },
      ],
    };
  },
  props: {
    name: String,
    headers: Array,
    items: Array,
  },
  mounted() {},
  methods: {
    deleting() {
      alert("delete");
    },
    blocking() {
      alert("blocking");
    },
  },
};
</script>
