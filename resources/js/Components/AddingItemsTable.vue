<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
    <SecondaryButton class="m-1" @click="addItem">{{$t("Add")}}</SecondaryButton>
    <header>
      <h2
        class="py-2 bg-white dark:bg-gray-800 text-center capitalize text-lg font-medium text-gray-900 dark:text-gray-100"
      >
        {{ $t(title) }}
        
      </h2>
    </header>
    
    <table
      class="w-full text-sm text-start text-gray-500 dark:text-gray-400"
      :key="table_key"
    >
      <thead
        class="border-b-2 text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400"
      >
        <tr>
          <th
            :key="index"
            v-for="(head, index) in headers"
            scope="col"
            class="px-6 py-3"
          >
            <div class="flex items-center">
              {{ $t(head.value) }}
              <a
                v-if="head.sortable"
                @click.prevent="handleSort(head.value)"
                href="#"
                ><svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-3 h-3 ml-1"
                  aria-hidden="true"
                  fill="currentColor"
                  viewBox="0 0 320 512"
                >
                  <path
                    d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"
                  /></svg
              ></a>
            </div>
          </th>
          <th scope="col" class="px-6 py-3">
            {{ $t("actions") }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in items" 
          :key="index"
          class="cursor-pointer bg-white border-b dark:bg-gray-800 dark:border-gray-700 items-center"
        >
          <td
            :key="index"
            v-for="(header, index) in headers"
            scope="row"
            class="px-6 py-2 font-semibold text-black whitespace-nowrap dark:text-white"
          >
            <div v-if="header.type == 'select'">
              <SelectInput
                :options="header.options"
                :item_name="`name_${i18n_locale}`"
                :id="header.key"
                class="mt-1 block w-full"
                v-model="item[header.key]"
                required
                autofocus
              />

            </div>
            <div v-else >
              <TextInput
                :id="header.key"
                :type="header.type"
                class="mt-1 block w-full dark:text-gray-800"
                v-model="item[header.key]"
                required
                autofocus
              />
            </div>
            
          </td>
          <td>
            <button type="button" @click="removeItem(index)">Remove</button>
          </td>
         
        </tr>
      </tbody>
      
    </table>
  </div>
</template>

<script setup>
import Paginator from "@/Components/Paginator.vue";
import TableAction from "@/Components/TableAction.vue";
import SelectInput from "@/Components/SelectInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";

import TextInput from "@/Components/TextInput.vue";


import { router } from "@inertiajs/vue3";
import { reactive, watch, ref, onMounted } from "vue";
import { useI18n } from "vue-i18n";

const props = defineProps({
  model: { 
    type: String,
    default: "",
  },
  headers: {
    type: [Array],
    default: [],
  },
 
  actions: {
    type: [Array],
    default: null,
  },
  title: {
    type: String,
    default: "",
  },
  import_url: {
    type: String,
    default: "",
  },
  add_item_route: {
    type: String,
    default: "",
  },
});

const { t } = useI18n();

const table_key = ref(0);

const modelValue = defineModel(); //passed by v-model
const items = reactive(modelValue.length ? [...modelValue] : []);
const emit = defineEmits(["update:modelValue"]);


// Watch changes in the reactive `items` and emit updates
watch(
  () => items,
  (newItems) => {
    emit("update:modelValue", newItems);
  
  },
  { deep: true }
);
onMounted(() => {
  if (items.length === 0) {
    addItem();
  }
});


const haveData = props.items && props.items.data && props.items.data[0];



function urlParams() {
  return "?" + new URLSearchParams(filters).toString();
}
function addItem() {
  const newItem = props.headers.reduce((obj, header) => {
    obj[header.key] = header.type === "number" ? 0 : "";
    return obj;
  }, {});
  items.push(newItem);

}

function removeItem(index) {
  items.splice(index, 1);
}

</script>
<style>
._1_status {
  background: rgb(250, 66, 66);
  color: white;
  text-align: center;
  position: relative;
}
._1_status::after {
  content: "";
  width: 17px;
  height: 17px;
  top: -5px;
  left: 0;
  position: absolute;
  border-radius: 50px;
  background-color: red;
  border: solid 2px white;
  outline: red solid 1px;
}
._2_status {
  padding: 4px 12px;
  background: rgb(200, 254, 200);
  color: rgb(46, 179, 46);
}
._3_status {
  background: rgb(255, 243, 221);
  color: rgb(255, 166, 0);
}
._4_status {
  background: rgb(228, 228, 228);
  color: rgb(110, 110, 110);
}
._5_status {
  background: rgb(255, 216, 216);
  color: red;
}

._1_status,
._2_status,
._3_status,
._4_status,
._5_status {
  padding: 4px 12px;
  border-radius: 15px;
  font-size: 15px;
  text-transform: capitalize;
  text-align: center;
}
.red_ths th {
  color: red !important;
}
</style>
