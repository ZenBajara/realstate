<template>
    <div>
      <!-- Desktop Header -->
      <header class="w-full items-center bg-white py-2 px-6 hidden sm:flex">
        <div class="w-1/2"></div>
        <div class="relative w-1/2 flex justify-end" x-data="{ isOpen: false }">
          <!-- Avatar Button -->
          <button @click="isOpen = !isOpen" class="relative z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
            <img src="@assets/avatar.jpeg" alt="User Avatar" class="w-full h-full object-cover">
          </button>
          <!-- Overlay Button to Close Dropdown -->
          <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default" aria-hidden="true"></button>
          <!-- Dropdown Menu -->
          <div x-show="isOpen" @click.away="isOpen = false" x-transition class="absolute right-0 w-32 bg-white rounded-lg shadow-lg py-2 mt-16">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">Profile</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">Settings</a>
            <a href="#" @click.prevent="signOut" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900">Sign Out</a>
          </div>
        </div>
      </header>

      <!-- Mobile Header & Nav -->
      <header class="w-full bg-sidebar py-5 px-6 sm:hidden" x-data="{ isOpen: false }">
        <div class="flex items-center justify-between">
          <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
          <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
            <i x-show="!isOpen" class="fas fa-bars"></i>
            <i x-show="isOpen" class="fas fa-times"></i>
          </button>
        </div>

        <!-- Dropdown Nav -->
        <nav :class="isOpen ? 'flex' : 'hidden'" class="flex flex-col pt-4">
          <a href="index.html" class="flex items-center active-nav-link text-white py-2 pl-4 nav-item">
            <i class="fas fa-tachometer-alt mr-3"></i>
            Dashboard
          </a>
          <a href="blank.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
            <i class="fas fa-sticky-note mr-3"></i>
            Properties
          </a>
          <a href="#" @click.prevent="signOut" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
            <i class="fas fa-sign-out-alt mr-3"></i>
            Sign Out
          </a>
        </nav>
      </header>
    </div>
  </template>

  <script setup>
  import { useRouter } from 'vue-router';
  import { defineEmits, ref } from 'vue';

  const router = useRouter();
  const isOpen = ref(false);

  const signOut = () => {
    // Clear token and role from local storage
    localStorage.removeItem('token');
    localStorage.removeItem('role');

    // Redirect to login page
    router.push({ name: 'login' });
  };

  // Define any emits if needed
  const emit = defineEmits(['property-list', 'close']);
  </script>

  <style scoped>
  /* Add any additional styles if needed */
  </style>
