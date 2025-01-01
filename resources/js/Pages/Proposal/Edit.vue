<script setup>
import Modal from "@/Components/Modal.vue";
import Card from "@/Components/ChartCard.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Textaera from "@/Components/Textaera.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, watch, computed, reactive } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import DangerButton from "@/Components/DangerButton.vue";
import { twMerge } from "tailwind-merge";
import { useI18n } from "vue-i18n";

const page = usePage();
const user = page.props.auth.user;
const userRoleName = computed(() => {
  return user.type == 1 ? "Admin" : "Agent";
});

const lateDate = ref(false);

// let item = usePage().props.item;
const props = defineProps({
  item: Object,
});
const { t } = useI18n();
watch(props.item, (new_val) => {}, { deep: true });
const form = useForm({
  ...props.item,
});

const FormStatues = [
  { id: 2, name: t("in process") },
  { id: 4, name: t("closed") },
  { id: 3, name: t("pending") },
  { id: 5, name: t("rejected") },
];
const zero = ref(0);
const test_ref = ref("");
const dialog = ref(false);
const dialog2 = ref(false);
const selected_image = ref("");

const calculateRemainingTime = () => {
  lateDate.value = Boolean(parseInt(form.remaining_time[2]));
  return (
    (lateDate.value ? "-" : "") +
    form.remaining_time[0] +
    " " +
    t("Day") +
    (lateDate.value ? ", -" : ", ") +
    form.remaining_time[1] +
    " " +
    t("Hour")
  );
};

const showSelectedIamge = (url) => {
  selected_image.value = url;
  dialog.value = true;
};

const closeModal = () => {
  dialog.value = false;
  selected_image.value = "";
};
const closeModal2 = () => {
  dialog2.value = false;
};
const formatDateTime = (pure_date) => {
  if (!pure_date) return;
  const just_date = pure_date.slice(0, 10);
  const date = new Date(pure_date);
  let hours = date.getHours();
  let minutes = date.getMinutes();
  const ampm = hours >= 12 ? "pm" : "am";

  hours %= 12;
  hours = hours || 12;
  minutes = minutes < 10 ? `0${minutes}` : minutes;

  const strTime = `${just_date} ${hours}:${minutes} ${ampm}`;

  return strTime;
};

const submit = () => {
  delete form.submitted_at;
  form.patch(route("form.update", form.id));
};
</script>

<template>
  <Modal :show="dialog" @close="closeModal">
    <div class="p-6">
      <SecondaryButton @click="closeModal"> X </SecondaryButton>
      <div class="border-2 flex justify-center">
        <img class="h-auto mt-2 rounded-md" :src="selected_image" alt="" />
      </div>
    </div>
  </Modal>
  <div class="mt-20">
    <Modal :show="dialog2" @close="closeModal2">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{
            $t(
              "Are you sure you want to change status to " +
                FormStatues.find((e) => e.id == form.status).name +
                " ?"
            )
          }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ $t("Please enter your changes befor update status") }}.
        </p>

        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal2">
            {{ $t("Cancel") }}
          </SecondaryButton>

          <DangerButton
            class="ml-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="submit"
          >
            {{ $t("Change Status") }}
          </DangerButton>
        </div>
      </div>
    </Modal>
  </div>
  <section>
    <header class="grid grid-cols-12">
      <div
        class="p-4 sm:p-5 text-white bg-gray-800 col-span-12 sm:col-span-9 text-2xl shadow-md"
      >
        {{ form.form_type_name }}
        <div class="flex text-sm">
          <div class="text-gray-300">
            <span class="uppercase">{{ $t("sent in") }} :</span>
            {{ formatDateTime(form.created_at) }}
          </div>
        </div>
        # {{ form.id }}
      </div>
      <div
        class="col-span-12 sm:col-span-3 bg-white shadow-md dark:bg-gray-800 dark:text-white"
      >
        <div class="sm:p-10 p-4" v-if="form.is_anonymous">
          {{ $t("Sent By") }} :
          <span class="font-semibold">{{ $t("Anonymous") }}</span>
        </div>
        <div class="px-6 py-4" v-else>
          <div>
            {{ $t("Sent By") }} :
            <span class="font-semibold capitalize">
              {{
                user.type == 1 ? form.client_name : $t("hidden by admin")
              }}</span
            >
          </div>
          <ul class="list-disc list-inside m-0" style="white-space: warp">
            <li class="list-item text-gray-400">
              {{ user.type == 1 ? form.email : $t("hidden by admin") }}
            </li>
            <li class="list-item text-green-500">
              {{ user.type == 1 ? form.phone : $t("hidden by admin") }}
            </li>
          </ul>
        </div>
      </div>
    </header>
  </section>
  <section>
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 dark:text-white">
      <div class="flex sm:flex-row flex-col justify-between">
        <div>
          <div
            class="text-xl font-semibold"
            :class="{ 'text-red-700': form.priority_name == 'critical' }"
          >
            {{ $t(form.priority_name) }}
          </div>
          <div>
            <span class="me-1">{{ $t("Remaining time") }} :</span
            ><span :class="lateDate ? 'text-red-600' : ''">
              {{ calculateRemainingTime() }}</span
            >
          </div>
        </div>
        <div class="sm:m-0 my-5">
          <div>
            <span class="font-semibold capitalize">{{ $t("logged by") }}</span
            >: <span class="text-sm">{{ form.logs[0]?.user_name }}</span>
          </div>
          <div class="text-gray-400 flex justify-end text-sm">
            {{ formatDateTime(form.logs[0]?.created_at) }}
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="text-gray-600">
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 dark:text-white">
      <div class="flex sm:flex-row flex-col justify-between mb-5">
        <div class="text-2xl font-semibold flex items-start">
          <div>{{ form.title }}</div>
        </div>
      </div>
      <p>
        {{ form.details }}
      </p>
    </div>
    <hr />
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 dark:text-white">
      <div>
        <h1 class="text-lg mb-5">
          <span class="font-semibold">{{ $t("Raised Previously") }} </span>
          {{ $t(form.problem_status_name) }}
        </h1>
      </div>
      <div class="font-semibold text-xl">
        {{ $t("Why the report has not been resolved to your satisfaction") }}
      </div>

      <p class="mt-5">{{ form.problem_status_details }}</p>
    </div>
  </section>
  <hr />
  <section>
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 dark:text-white">
      <div>
        <h1 class="text-lg mb-5">
          <span class="font-semibold">{{ $t("Solution suggestion") }} </span>
        </h1>
      </div>

      <p>{{ form.suggested_solution }}</p>
    </div>
  </section>
  <hr />
  <section>
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 dark:text-white">
      <div>
        <h1 class="text-lg mb-5">
          <span class="font-semibold">{{ $t("Attached files") }} </span>
        </h1>
      </div>

      <div class="flex flex-wrap">
        <img
          class="w-32 cursor-pointer px-1 rounded-3xl"
          :key="attachment.id"
          v-for="attachment in form.attachments"
          :src="attachment.url"
          @click="showSelectedIamge(attachment.url)"
          alt="g"
        />
      </div>
    </div>
  </section>
  <hr />
  <section>
    <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 dark:text-white">
      <div
        id="accordion-flush"
        data-accordion="collapse"
        data-active-classes="bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
        data-inactive-classes="text-gray-900 dark:text-white"
      >
        <h2 id="accordion-flush-heading-1">
          <button
            type="button"
            class="flex items-center justify-between w-full py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
            data-accordion-target="#accordion-flush-body-1"
            aria-expanded="true"
            aria-controls="accordion-flush-body-1"
          >
            <h1 class="text-lg">
              <span class="font-semibold">{{ $t("Activities") }} </span>
            </h1>
            <svg
              data-accordion-icon
              class="w-6 h-6 rotate-180 shrink-0"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"
              ></path>
            </svg>
          </button>
        </h2>
        <div
          id="accordion-flush-body-1"
          class="hidden"
          aria-labelledby="accordion-flush-heading-1"
        >
          <div class="px-4 py-1" :key="log.id" v-for="log in form.logs">
            <div>
              {{ log.user_name }}
              <span class="ms-5 text-sm text-gray-400"
                >{{ formatDateTime(log.created_at)
                }}<span class="ms-7 text-sm text-gray-400"
                  >({{ $t(log.action_name) }})</span
                ></span
              >
            </div>
            <div
              v-if="log.note"
              class="p-4 rounded-lg bg-green-50 dark:bg-gray-700"
            >
              {{ log.note }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <hr />
  <section>
    <div
      ref="test_ref"
      class="p-4 sm:p-6 bg-white dark:bg-gray-800 dark:text-white"
    >
      <div>
        <h1 class="text-lg mb-5">
          <span class="font-semibold">{{ $t("Send Comment") }} </span>
        </h1>
      </div>
      <QuillEditor
        contentType="text"
        v-model:content="form.solution"
        placeholder="solution"
        rows="3"
        theme="snow"
      />

      <!-- <Textaera
        rows="3"
        :required="form.problem_status == 2"
        id="problem_message"
        placeholder="solution"
        v-model="form.solution"
      /> -->

      <div class="mt-5 text-center flex gap-3 justify-center">
        <button
          @click="
            () => {
              form.status = FormStatues[0].id;
              dialog2 = true;
            }
          "
          :disabled="form.status == 4"
          v-if="form.status != 4"
          :class="
            twMerge(
              'bg-transparent text-green-700 hover:bg-green-800 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded',
              FormStatues[0].id === form.status ? 'text-white bg-green-700' : ''
            )
          "
        >
          {{ FormStatues[0].name }}
        </button>
        <button
          @click="
            () => {
              form.status = FormStatues[3].id;
              dialog2 = true;
            }
          "
          :disabled="form.status == 4"
          v-if="form.status != 4"
          :class="
            twMerge(
              'bg-transparent text-red-700 hover:bg-red-800 font-semibold hover:text-white py-2 px-4 border border-red-500 hover:border-transparent rounded',
              FormStatues[3].id === form.status ? 'text-white bg-red-700' : ''
            )
          "
        >
          {{ FormStatues[3].name }}
        </button>
        <button
          @click="
            () => {
              form.status = FormStatues[2].id;
              dialog2 = true;
            }
          "
          :disabled="form.status == 4"
          v-if="form.status != 4"
          :class="
            twMerge(
              'bg-transparent text-yellow-400 hover:bg-yellow-500 font-semibold hover:text-white py-2 px-4 border border-yellow-500 hover:border-transparent rounded',
              FormStatues[2].id === form.status
                ? 'text-white bg-yellow-400'
                : ''
            )
          "
        >
          {{ FormStatues[2].name }}
        </button>
        <button
          @click="
            () => {
              form.status = FormStatues[1].id;
              dialog2 = true;
            }
          "
          :disabled="form.status == 4"
          v-if="form.status != 4"
          :class="
            twMerge(
              'bg-transparent text-purple-700 hover:bg-purple-800 font-semibold hover:text-white py-2 px-4 border border-purple-500 hover:border-transparent rounded',
              FormStatues[1].id === form.status
                ? 'text-white bg-purple-700'
                : ''
            )
          "
        >
          {{ FormStatues[1].name }}
        </button>
      </div>
    </div>
  </section>
</template>

<style></style>
