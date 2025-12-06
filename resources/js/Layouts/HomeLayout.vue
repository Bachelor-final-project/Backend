<template>
  <div class="min-h-screen">
    <main>
      <slot />
    </main>
    
    <!-- Footer -->
    <footer class="relative py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-green-900 via-green-800 to-green-700 overflow-hidden" style="background-color: #4a5d23;">
      <div class="absolute inset-0 bg-black/20"></div>
      <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent transform -skew-y-6"></div>
      </div>
      <div class="relative max-w-7xl mx-auto">
        <div class="flex justify-between items-center">
          <p class="text-white text-sm">
            جميع الحقوق محفوظة لدى جمعية إغاثة غزة 2025<br>
            All Rights Reserved - Gaza Relief Association 2025
          </p>
          <a :href="route('change-language', locale === 'ar' ? 'en' : 'ar')" class="px-4 py-2 text-sm text-white border border-teal-400 rounded-md hover:bg-teal-400 hover:text-green-900 transition-colors">
            {{ locale === 'ar' ? 'EN' : 'العربية' }}
          </a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import { router } from '@inertiajs/vue3'

const { locale } = useI18n()

const toggleLanguage = () => {
  const newLocale = locale.value === 'ar' ? 'en' : 'ar'
  // changeLanguage(newLocale)
  route('change-language', newLocale)
  // router.get(route('change-language', newLocale))
}
</script>