<template>
    <section class="w-full px-8 text-gray-700 bg-white shadow-md mb-8">
      <div class="container flex flex-col flex-wrap items-center justify-between py-3 mx-auto md:flex-row max-w-7xl">
        <div class="relative flex flex-col md:flex-row">
          <router-link to="/" class="flex items-center mb-5 font-medium text-gray-900 lg:w-auto lg:items-center lg:justify-center md:mb-0">
            <img src="@assets/realstate_logo.png" class="w-24 h-16" alt="Real Estate Logo">
          </router-link>
          <nav class="flex flex-wrap items-center mb-5 text-base md:mb-0 md:pl-8 md:ml-8 md:border-l md:border-gray-200">
            <router-link to="/" class="mr-5 font-medium leading-6 text-gray-600 hover:text-gray-900">Home</router-link>
          </nav>
        </div>
        <div class="inline-flex items-center ml-5 space-x-6 lg:justify-end">
          <!-- Conditional rendering based on user authentication status -->
          <template v-if="isLoggedIn">
            <a @click="signOut" href="#" class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
              Sign Out
            </a>
          </template>
          <template v-else>
            <router-link to="/login" class="inline-flex items-center justify-center px-4 py-2 text-base font-medium leading-6 text-white whitespace-no-wrap bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
              Sign in
            </router-link>
          </template>
        </div>
      </div>
    </section>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRoute } from 'vue-router';

  // User authentication and details
  const isLoggedIn = ref(false);
  const userProfileImage = ref('');
  const userName = ref('');

  const route = useRoute();

  function checkLocalStorage() {
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');
    if(token && role)
    {
        isLoggedIn.value = true;
    }else{
        isLoggedIn.value = false;
    }
    return token && role;
  }



  function signOut() {
  localStorage.removeItem('token');
  localStorage.removeItem('role');
  isLoggedIn.value = false;
  userProfileImage.value = '';
  userName.value = '';
  router.push('/login');
}

  // Check user authentication on component mount
  onMounted(() => {
    checkLocalStorage();
  });
  </script>

  <style scoped>
  /* Styles specific to the header component */
  </style>
