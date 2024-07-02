<template>
    <section class="bg-gray-50 dark:bg-gray-900 h-screen flex items-center justify-center">
      <div class="w-full max-w-md bg-white rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
          <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
            Bajara
          </a>
          <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            Sign in to your account
          </h1>
          <form @submit.prevent="handleLogin" class="space-y-4 md:space-y-6">
            <div v-if="errorMessage" class="text-red-500 text-sm">
              {{ errorMessage }}
            </div>
            <div>
              <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
              <input type="email" id="email" v-model="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required>
            </div>
            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
              <input type="password" id="password" v-model="password" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="••••••••" required>
            </div>
            <div class="flex items-center justify-between">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input id="remember" aria-describedby="remember" type="checkbox" v-model="remember" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800">
                </div>
                <div class="ml-3 text-sm">
                  <label for="remember" class="text-gray-500 dark:text-gray-300">Remember me</label>
                </div>
              </div>
              <a href="#" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">Forgot password?</a>
            </div>
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
              Login
            </button>
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">
              Don’t have an account yet? <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Sign up</a>
            </p>
          </form>
        </div>
      </div>
    </section>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import axios from 'axios';

  const email = ref('');
  const password = ref('');
  const remember = ref(false);  // Added remember me checkbox binding
  const errorMessage = ref('');
  const router = useRouter();

  async function handleLogin() {
      errorMessage.value = '';  // Clear previous error message
      try {
          const response = await axios.post('/api/login', {
              email: email.value,
              password: password.value,
          });
          const { token, role } = response.data;
          // Store the token and role in local storage
          localStorage.setItem('token', token);
          localStorage.setItem('role', role);
          console.log('Logged in',role);
          // Redirect based on role
          if (role === 'admin') {
              router.push({ name: 'admin.dashboard' });
          } else if (role === 'user') {
              router.push({ name: 'home' });
          } else {
              console.error('Unknown role');
          }
      } catch (error) {
          console.error(error);
          errorMessage.value = 'Invalid email or password. Please try again.';
      }
  }
  </script>
