<template>
    <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <!-- <div class="p-6">
            <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
            <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> New Report
            </button>
        </div> -->
        <nav class="text-white text-base font-semibold pt-3">
            <a :href="$router.resolve({ name: 'admin.dashboard' }).href"
                :class="{ 'active-nav-link': isActive('admin.dashboard'), 'flex items-center text-white py-4 pl-6 nav-item': true }">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a :href="$router.resolve({ name: 'admin.property' }).href"
                :class="{ 'active-nav-link': isActive('admin.property'), 'flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item': true }">
                <i class="fas fa-home mr-3"></i>
                Properties
            </a>
            <!-- Sign Out Button -->
            <div class="absolute bottom-0 w-full">
            <a href="#"  @click.prevent="signOut"  class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sign-out-alt mr-3"></i>
                Sign Out
            </a>
            </div>
            <!-- <a href="tables.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-table mr-3"></i>
                Tables
            </a>
            <a href="forms.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-align-left mr-3"></i>
                Forms
            </a>
            <a href="tabs.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-tablet-alt mr-3"></i>
                Tabbed Content
            </a>
            <a href="calendar.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-calendar mr-3"></i>
                Calendar
            </a> -->
        </nav>
        <!-- <a href="#" class="absolute w-full upgrade-btn bottom-0 active-nav-link text-white flex items-center justify-center py-4">
            <i class="fas fa-arrow-circle-up mr-3"></i>
            Upgrade to Pro!
        </a> -->
    </aside>
</template>
<script setup>
  import { useRouter } from 'vue-router';

  const router = useRouter();

  const isActive = (routeName) => {
    return router.currentRoute.value.name === routeName;
  };

  const signOut = () => {
    // Clear token and role from local storage
    localStorage.removeItem('token');
    localStorage.removeItem('role');

    // Redirect to login page
    router.push({ name: 'login' });
  };
</script>

