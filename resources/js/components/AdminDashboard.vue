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
              <div  class="bg-white overflow-auto relative">
                <!-- Loader -->
                <div v-if="loading" class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-50 z-10">
                  <i class="fas fa-spinner fa-spin text-3xl text-gray-500"></i>
                </div>
                <div v-else>
                    <div class="flex justify-between items-center mb-3 mt-3">
                        <p class="text-xl pb-3 flex items-center"></p>
                        <input
                        type="text"
                        v-model="searchTerm"
                        placeholder="Search properties..."
                        class=" font-bold py-2 px-4 rounded"
                        />
                    </div>
                    <!-- Table -->
                    <table  class="min-w-full bg-white">
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
                        <tr
                        v-for="(property, index) in paginatedProperties"
                        :key="property.id"
                        :class="{'bg-gray-200': index % 2 === 0}"
                        >
                        <td class="w-1/5 text-left py-3 px-4">{{ property.name }}</td>
                        <td class="w-1/5 text-left py-3 px-4">{{ property.address }}</td>
                        <td class="w-1/5 text-left py-3 px-4">{{ property.city }}</td>
                        <td class="w-1/5 text-left py-3 px-4">{{ property.state }}</td>
                        <td class="w-1/5 text-left py-3 px-4">{{ property.type }}</td>
                        <td class="w-1/5 text-left py-3 px-4">


                            <button
                            class="text-green-500 cursor-pointer hover:text-green-700 mr-3"
                            @click="openModal(property.id)"
                            >
                            <i class="fas fa-edit text-green-500 cursor-pointer hover:text-green-700 mr-3" ></i>
                            </button>
                            <button
                            class="text-red-500 cursor-pointer hover:text-red-700"
                            @click="deleteData(property.id)"
                            >
                            <i class="fas fa-trash-alt text-red-500 cursor-pointer hover:text-red-700"></i>
                            </button>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
              </div>
              <!-- Pagination -->
              <div class="flex justify-end mt-4">
                <button
                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2"
                  :disabled="currentPage === 1"
                  @click="currentPage--"
                >
                  Previous
                </button>
                <template v-for="page in Math.min(numPagesToShow, totalPages)">
                  <button
                    v-if="currentPage + page - 1 <= totalPages"
                    :key="page"
                    @click="currentPage = currentPage + page - 1"
                    :class="{'px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2': currentPage !== currentPage + page - 1, 'px-4 py-2 bg-blue-500 text-white rounded-md mr-2': currentPage === currentPage + page - 1}"
                  >
                    {{ currentPage + page - 1 }}
                  </button>
                </template>
                <button
                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md"
                  :disabled="currentPage * pageSize >= filteredProperties.length"
                  @click="currentPage++"
                >
                  Next
                </button>
              </div>
            </div>
          </main>

          <BottomFooter />
        </div>
      </div>
    </div>
  </template>
<script setup>
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';
import SideBar from '../partials/Sidebar.vue';
import TopHeader from '../partials/Header.vue';
import BottomFooter from '../partials/Footer.vue';

const properties = ref([]);
const loading = ref(true); // Add loading state
const searchTerm = ref('');
  let currentPage = ref(1);
  const pageSize = 10; // Number of items per page
  const numPagesToShow = 5; // Number of pagination buttons to show

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


const filteredProperties = computed(() => {
    return properties.value.filter(property => {
      return property.name.toLowerCase().includes(searchTerm.value.toLowerCase());
    });
  });

  const paginatedProperties = computed(() => {
    const startIndex = (currentPage.value - 1) * pageSize;
    return filteredProperties.value.slice(startIndex, startIndex + pageSize);
  });


  const totalPages = computed(() => {
    return Math.ceil(filteredProperties.value.length / pageSize);
  });
onMounted(async () => {
  await propertyList();
});
</script>
