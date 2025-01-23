<template>
      <input
        accept="image/*,video/*" 
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
        id="multiple_files"
        type="file"
        :multiple="multiple"
        ref="fileuploadInput"
        @change="handleFileChange"
      />
      <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">
        MP4, PNG, JPG, or GIF (MAX. 5 MB).
      </p>
      <InputError v-for="error in form.errors" class="mt-2" :message="error" />
      <div v-if="show_files_details && form.files.length > 0" class="mt-3">
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $t("Selected Files") }}</h3>
        <ul>
          <li
            v-for="(file, index) in form.files"
            :key="index"
            class="text-sm text-gray-600 dark:text-gray-400"
          >
            {{ file.name }} ({{ (file.size / 1024).toFixed(2) }} KB)
          </li>
        </ul>
      </div>
</template>
<script setup>
import InputError from './InputError.vue';
import { watch, ref, defineProps, defineEmits } from 'vue';
import { useForm } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
const emit = defineEmits(['finishUploading', 'startUploading', 'successUploading', 'fileinputChange']);
const props = defineProps({
  trigger: {
    type: String,
    default: "",
  },
  item_id: {
    type: String,
    default: ""
  },
  model: {
    type: String,
    default: ""
  },
  attachment_type: {
    type: String,
    default: "",
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  show_files_details: {
    type: Boolean,
    default:true
  }
    
});

watch(() => props.trigger, (newValue, old) => {
    uploadFiles();
});

const isLoading = ref(false);
const fileuploadInput = ref(null);
const form = useForm({
  files: [],
  attachable_id: props.item_id,
  attachable_type: props.model,
  attachment_type: props.attachment_type,
});

const handleFileChange = () => {
  form.files = Array.from(fileuploadInput.value.files);
  emit('fileinputChange', form.files);
};

function uploadFiles() {
    form.post(route("attachment.store"), {
        onStart: () => {
            emit('startUploading');
        },
        onSuccess: () => {
            form.defaults();
            emit('successUploading');
        },
        onError: () => {
            emit('finishUploading');
        },
        onFinish: () => {
            emit('finishUploading');
        },
    });
}
</script>