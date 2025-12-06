<template>
  <Head :title="entity.home_title && entity.home_title[i18n_locale] ? entity.home_title[i18n_locale] : entity.name" />
  
  <div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative min-h-screen py-8 sm:py-16 lg:py-32 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-900 via-green-800 to-green-700 overflow-hidden" style="background-color: #4a5d23;">
      <!-- Background Overlay -->
      <div class="absolute inset-0 bg-black/20"></div>
      
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent transform -skew-y-6"></div>
      </div>
      
      <div class="relative max-w-7xl mx-auto text-center flex flex-col justify-center min-h-screen">
        <!-- Organization Logo -->
        <div class="mb-6 sm:mb-8">
          <ApplicationLogo class="mx-auto mb-4 " />
        </div>
        
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-bold text-white mb-4 sm:mb-6 leading-tight px-2">
          {{ entity.home_title && entity.home_title[i18n_locale] ? entity.home_title[i18n_locale] : $t('Urgent Relief for Displaced Families') }}
        </h1>
        <p v-if="entity.home_description && entity.home_description[i18n_locale]" 
           class="text-base sm:text-lg lg:text-xl text-gray-200 mb-8 sm:mb-12 max-w-4xl mx-auto leading-relaxed px-4">
          {{ entity.home_description[i18n_locale] }}
        </p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center mb-8 sm:mb-16 px-4">
          <a :href="route('donating-form', entity.donating_form_path)" 
             class="inline-flex items-center justify-center rtl:pr-6 ltr:pl-6 sm:px-8 lg:px-10 py-4 sm:py-5 bg-gradient-to-r from-teal-500 to-teal-600 text-white font-bold text-base sm:text-lg rounded-xl hover:from-teal-600 hover:to-teal-700 transform hover:scale-105 hover:shadow-2xl transition-all duration-300 shadow-xl" style="background: linear-gradient(135deg, #14b8a6, #0d9488);">
            <f-icon class="text-white text-lg sm:text-xl ltr:mr-2 rtl:ml-2 sm:mr-3" icon="heart"></f-icon>
            {{ $t('Donate Now') }}
          </a>
          
          <a v-if="entity.whatsapp_number" 
             :href="`https://wa.me/${entity.whatsapp_number}`" 
             target="_blank"
             class="inline-flex items-center justify-center rtl:pr-6 ltr:pl-6 sm:px-8 lg:px-10 py-4 sm:py-5 text-white font-bold text-base sm:text-lg rounded-xl transform hover:scale-105 hover:shadow-2xl transition-all duration-300 shadow-xl" style="background-color: #4a5d23; border: 2px solid #14b8a6;">
            <f-icon class="text-white text-lg sm:text-xl ltr:mr-2 rtl:ml-2 sm:mr-3" icon="phone"></f-icon>
            {{ $t('Contact Us') }}
          </a>
        </div>
      </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-12 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-center mb-8 sm:mb-12 lg:mb-16 px-4" style="color: #4a5d23;">
          {{ $t('Our Impact') }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-10">
          <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-lg border-2" style="border-color: #14b8a6;">
            <AnimatedCounter
              icon="circle-check"
              icon_color="#14b8a6"
              :name="$t('Completed Projects')"
              :count="entity.completed_projects_count || 2847"
            />
          </div>
          <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-lg border-2" style="border-color: #14b8a6;">
            <AnimatedCounter
              icon="handshake"
              icon_color="#14b8a6"
              :name="$t('Active Projects')"
              :count="entity.number_of_public_proposal || 156"
            />
          </div>
          <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-lg border-2 sm:col-span-2 lg:col-span-1" style="border-color: #14b8a6;">
            <AnimatedCounter
              icon="user"
              icon_color="#14b8a6"
              :name="$t('Total Donations')"
              :count="entity.donations_count || 18470"
            />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import HomeLayout from '@/Layouts/HomeLayout.vue'
import AnimatedCounter from '@/Components/AnimatedCounter.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'

defineOptions({ layout: HomeLayout })

const { locale } = useI18n()
const i18n_locale = computed(() => locale.value)

const props = defineProps({
  entity: Object
})
</script>