<template>
    <div class="flex items-center justify-center min-h-screen">
      <div v-if="status === 'success'" class="text-center">
        <div class="text-6xl text-green-500 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-32 h-32 mx-auto">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-700">Payment Successful</h1>
      </div>
  
      <div v-else-if="status === 'fail'" class="text-center">
        <div class="text-6xl text-red-500 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-32 h-32 mx-auto">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-700">Payment Failed</h1>
      </div>
    </div>
  </template>
  
  <script setup>
  // Props to determine the payment status
  defineProps({
    status: {
      type: String,
      required: true,
      validator: (value) => ['success', 'fail'].includes(value),
    },
  });
  </script>
  
  <style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
  }
  </style>
  