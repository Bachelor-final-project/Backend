<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  icon: String,
  icon_color: String,
  name: String,
  count: Number,
  prefix: { type: String, default: '' },
  postfix: { type: String, default: '' }
})

const animatedCount = ref(0)

onMounted(() => {
  const duration = 2000
  const steps = 60
  const increment = props.count / steps
  const stepDuration = duration / steps
  
  let current = 0
  const timer = setInterval(() => {
    current += increment
    if (current >= props.count) {
      animatedCount.value = props.count
      clearInterval(timer)
    } else {
      animatedCount.value = Math.floor(current)
    }
  }, stepDuration)
})
</script>

<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700">
    <div class="flex justify-center mb-6">
      <div class="p-6 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-full shadow-inner">
        <f-icon :icon="icon" class="text-5xl" :style="{ color: icon_color || '#6366F1' }"></f-icon>
        <!-- <f-icon class="text-white text-4xl" :icon="icon"></f-icon> -->
      </div>
    </div>
    <div class="text-5xl md:text-6xl font-black text-gray-900 dark:text-white mb-3 tracking-tight">
      {{ prefix }}{{ animatedCount }}{{ postfix }}
    </div>
    <h3 class="text-lg font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wide">{{ name }}</h3>
  </div>
</template>