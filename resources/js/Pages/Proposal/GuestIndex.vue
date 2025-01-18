<template>
    <Head :title="$t('Proposal')" />
    <div class="dark:text-white">
      <Table
        title="Proposal"
        :actions="actions"
        :items="items"
        :headers="headers"  
      />
    </div>
  </template>
  <script setup>
  import { router } from "@inertiajs/vue3";
  import { Head } from "@inertiajs/vue3";
  import { useForm } from "@inertiajs/vue3";
  import Card from "@/Components/Card.vue";
  import Table from "@/Components/Table.vue";
  import PrimaryButton from "@/Components/PrimaryButton.vue";
  import SecondaryButton from "@/Components/SecondaryButton.vue";
  
  const actions = [
    {
      type: "btn",
      icon: "plus",
      icon_color: "#50,200,50",
      funcName: "plusShare",
      model: "proposal",
      tooltip: "add share",
    },
    {
      type: "btn",
      icon: "minus",
      icon_color: "red",
      funcName: "minusShare",
      model: "proposal",
      tooltip: "sub share",
    },
    // {
    //   type: "btn",
    //   icon: "ban",
    //   icon_color: "#f5425a",
    //   funcName: _this.blocking,
    //   tooltip: "block user",
    // },
  ];
  
  const props = defineProps({
    items: Array,
    headers: Array,
    name: String,
  });
  
  const user = useForm({
    name: "",
    email: "",
    password: "",
    type: "",
    status: "",
    job_title: "",
    is_active: "",
  });
  
  function save() {
    console.log(user);
  }
  </script>
  