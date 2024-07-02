<template>
    <div class="bg-gray-100 font-family-karla flex w-full">
      <SideBar />

      <div class="w-full flex flex-col h-screen overflow-y-hidden">
        <TopHeader />

        <div class="w-full overflow-x-hidden border-t flex flex-col">
          <main class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Dashboard</h1>

            <div class="w-full mt-12">
              <p class="text-xl pb-3 flex items-center">
                <i class="fas fa-list mr-3"></i> Latest Properties
              </p>
              <div class="bg-white overflow-auto relative">
                <!-- Loader -->
                <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-50 z-10">
                  <i class="fas fa-spinner fa-spin text-3xl text-gray-500"></i>
                </div>

                <!-- Table -->
                <table v-else class="min-w-full bg-white">
                  <thead class="bg-gray-800 text-white">
                    <tr>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Name</th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Address</th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">City</th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">State</th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Type</th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Action</th>
                    </tr>
                  </thead>
                  <tbody class="text-gray-700">
                    <tr v-for="(property, index) in properties" :key="property.id" :class="{'bg-gray-200': index % 2 === 0}">
                      <td class="w-1/5 text-left py-3 px-4">{{ property.name }}</td>
                      <td class="w-1/5 text-left py-3 px-4">{{ property.address }}</td>
                      <td class="w-1/5 text-left py-3 px-4">{{ property.city }}</td>
                      <td class="w-1/5 text-left py-3 px-4">{{ property.state }}</td>
                      <td class="w-1/5 text-left py-3 px-4">{{ property.type }}</td>
                      <td class="w-1/5 text-left py-3 px-4">
                        <i class="fas fa-edit text-green-500 cursor-pointer hover:text-green-700 mr-3" @click="openModal(property.id)"></i>
                        <i class="fas fa-trash-alt text-red-500 cursor-pointer hover:text-red-700" @click="deleteData(property.id)"></i>
                      </td>
                    </tr>
                    <tr v-if="properties.length === 0">
                      <td colspan="6" class="text-center py-4">No properties found.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </main>

          <BottomFooter />
        </div>
      </div>
    </div>
  </template>
<script setup>
import { onMounted, ref } from 'vue';
import axios from 'axios';
import SideBar from '../partials/Sidebar.vue';
import TopHeader from '../partials/Header.vue';
import BottomFooter from '../partials/Footer.vue';

const properties = ref([]);
const loading = ref(true); // Add loading state

async function propertyList() {
  try {
    const token = localStorage.getItem('token'); // Get token from local storage
    const response = await axios.get('/api/properties/latest', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    properties.value = response.data;
  } catch (error) {
    console.error('Error fetching properties:', error.response ? error.response.data : error.message);
  } finally {
    loading.value = false; // Set loading to false once the data is fetched
  }
}

onMounted(async () => {
  await propertyList();
});
</script>
