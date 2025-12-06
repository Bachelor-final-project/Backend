<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Textaerea from "@/Components/Textaera.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import TopRightLayout from "@/Layouts/TopRightLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  entity: Object,
  supervisors: Array,
  countries: Array,
});

const form = useForm({
  name: props.entity.name || "",
  supervisor_id: props.entity.supervisor_id || "",
  donating_form_path: props.entity.donating_form_path || "",
  country_id: props.entity.country_id || "",
  home_title: props.entity.home_title || { ar: "", en: "" },
  home_description: props.entity.home_description || { ar: "", en: "" },
  whatsapp_number: props.entity.whatsapp_number || "",
  initial_completed_projects: props.entity.initial_completed_projects || 0,
});

const submit = () => {
  form.put(route("entity.update", props.entity.id), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>

<template>
  <Head :title="$t('Edit Entity')" />
  <TopRightLayout>
    <section>
      <header>
        <h2 class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ $t("update entity") }}
        </h2>
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-3 gap-4">
        <div>
          <InputLabel for="name" value="name" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>
        <div>
          <InputLabel for="donating_form_path" value="donating_form_path" />
          <TextInput
            id="donating_form_path"
            type="text"
            class="mt-1 block w-full"
            v-model="form.donating_form_path"
            required
            autocomplete="donating_form_path"
          />
          <InputError class="mt-2" :message="form.errors.donating_form_path" />
        </div>
        <div>
          <InputLabel for="supervisor_id" value="entity supervisor" />
          <SelectInput
            :options="supervisors"
            :item_name="`name`"
            id="supervisor_id"
            v-model="form.supervisor_id"
            class="mt-1 block w-full"
            required
          />
          <InputError :message="form.errors.supervisor_id" class="mt-2" />
        </div>
        <div>
          <InputLabel for="country_id" value="Country" />
          <SelectInput
            :options="countries"
            :item_name="`name`"
            id="country_id"
            v-model="form.country_id"
            class="mt-1 block w-full"
          />
          <InputError :message="form.errors.country_id" class="mt-2" />
        </div>
        <div>
          <InputLabel for="whatsapp_number" value="WhatsApp Number" />
          <TextInput
            id="whatsapp_number"
            type="text"
            class="mt-1 block w-full"
            v-model="form.whatsapp_number"
            autocomplete="whatsapp_number"
          />
          <InputError class="mt-2" :message="form.errors.whatsapp_number" />
        </div>
        <div>
          <InputLabel for="initial_completed_projects" value="Initial Completed Projects" />
          <TextInput
            id="initial_completed_projects"
            type="number"
            min="0"
            class="mt-1 block w-full"
            v-model="form.initial_completed_projects"
            autocomplete="initial_completed_projects"
          />
          <InputError class="mt-2" :message="form.errors.initial_completed_projects" />
        </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
        <div>
          <InputLabel for="home_title_ar" value="Home Title (Arabic)" />
          <TextInput
            id="home_title_ar"
            type="text"
            class="mt-1 block w-full"
            v-model="form.home_title.ar"
            autocomplete="home_title_ar"
          />
          <InputError class="mt-2" :message="form.errors['home_title.ar']" />
        </div>
        <div>
          <InputLabel for="home_title_en" value="Home Title (English)" />
          <TextInput
            id="home_title_en"
            type="text"
            class="mt-1 block w-full"
            v-model="form.home_title.en"
            autocomplete="home_title_en"
          />
          <InputError class="mt-2" :message="form.errors['home_title.en']" />
        </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
        <div>
          <InputLabel for="home_description_ar" value="Home Description (Arabic)" />
          <Textaerea
            id="home_description_ar"
            class="mt-1 block w-full"
            v-model="form.home_description.ar"
            rows="4"
          />
          <InputError class="mt-2" :message="form.errors['home_description.ar']" />
        </div>
        <div>
          <InputLabel for="home_description_en" value="Home Description (English)" />
          <Textaerea
            id="home_description_en"
            class="mt-1 block w-full"
            v-model="form.home_description.en"
            rows="4"
          />
          <InputError class="mt-2" :message="form.errors['home_description.en']" />
        </div>
        </div>

        <div class="flex items-center gap-4">
          <PrimaryButton :disabled="form.processing">{{
            $t("Save")
          }}</PrimaryButton>

          <Transition
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
            class="transition ease-in-out"
          >
            <p
              v-if="form.recentlySuccessful"
              class="text-sm text-gray-600 dark:text-gray-400"
            >
              Saved.
            </p>
          </Transition>
        </div>
      </form>
    </section>
  </TopRightLayout>
</template>