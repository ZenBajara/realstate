<!-- Loader Component -->
<template>
    <div v-if="isLoading" class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-75">
      <div class="loader"></div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  const isLoading = ref(true);

  // Simulate loading completion
  setTimeout(() => {
    isLoading.value = false;
  }, 1000); // Adjust this timeout to match your loading needs
  </script>

  <style>
  .loader {
    border: 8px solid rgba(0, 0, 0, 0.1);
    border-left-color: #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
  </style>
