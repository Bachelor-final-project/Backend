<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { useForm } from "@inertiajs/vue3";
import TopRightLayout from "@/Layouts/TopRightLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  stakeholder_types: Array,
});

const form = useForm({
  name: "",
  national_id: "",
  phone: "",
  type: "",
});

const submit = () => {
  form.post(route("warehouse_stakeholder.store"), {
    onFinish: () => {
      form.defaults();
    },
  });
};
</script>

<template>
  <Head :title="$t('Add Warehouse Stakeholder')" />
  <TopRightLayout>
    <section>
      <header>
        <h2 class="capitalize text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ $t("add new warehouse stakeholder") }}
        </h2>
      </header>

      <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>
          <InputLabel for="name" value="Name" />
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
          <InputLabel for="national_id" value="National ID" />
          <TextInput
            id="national_id"
            type="text"
            class="mt-1 block w-full"
            v-model="form.national_id"
            autocomplete="national_id"
          />
          <InputError class="mt-2" :message="form.errors.national_id" />
        </div>

        <div>
          <InputLabel for="phone" value="Phone" />
          <TextInput
            id="phone"
            type="text"
            class="mt-1 block w-full"
            v-model="form.phone"
            autocomplete="phone"
          />
          <InputError class="mt-2" :message="form.errors.phone" />
        </div>

        <div>
          <InputLabel for="type" value="Type" />
          <SelectInput
            :options="stakeholder_types"
            :item_name="`name`"
            id="type"
            v-model="form.type"
            class="mt-1 block w-full"
            required
            autocomplete="new-password"
          />
          <InputError :message="form.errors.type" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
          <PrimaryButton :disabled="form.processing">
            {{ $t("Save") }}
          </PrimaryButton>

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