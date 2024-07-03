<template>
    <div class="bg-gray-100 font-family-karla flex w-full">
      <SideBar />
      <div class="w-full flex flex-col h-screen overflow-y-hidden">
        <!-- Desktop Header -->
        <TopHeader />

        <div class="w-full overflow-x-hidden border-t flex flex-col">
          <main class="w-full flex-grow p-6">
            <div class="w-full mt-4">
              <div class="flex justify-between items-center mb-3">
                <p class="text-xl pb-3 flex items-center">
                  <i class="fas fa-list mr-3"></i> Property List
                </p>
                <button
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                  @click="openModal(0)"
                >
                  Create
                </button>
              </div>
              <div class="flex justify-between items-center mb-3">
                <p class="text-xl pb-3 flex items-center"></p>
                <input
                  type="text"
                  v-model="searchTerm"
                  placeholder="Search properties..."
                  class=" font-bold py-2 px-4 rounded"
                />
              </div>

              <!-- Table -->
              <div class="bg-white overflow-auto">
                <table class="min-w-full bg-white">
                  <thead class="bg-gray-800 text-white">
                    <tr>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">
                        Name
                      </th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">
                        Address
                      </th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">
                        City
                      </th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">
                        State
                      </th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">
                        Type
                      </th>
                      <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">
                        Action
                      </th>
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

      <!-- Modal Component -->
      <PropertyModel
        :show="showModal"
        ref="propertyFormModal"
        @close="close"
        @property-list="propertyList"
      />
    </div>
  </template>




  <script setup>
  import { ref, onMounted, defineEmits, computed } from 'vue';
  import axios from 'axios';
  import SideBar from '../partials/Sidebar.vue';
  import TopHeader from '../partials/Header.vue';
  import BottomFooter from '../partials/Footer.vue';
  import PropertyModel from '../models/PropertyModel.vue';

  const showModal = ref(false);
  const propertyFormModal = ref(null);
  const properties = ref([]);
  const emit = defineEmits(['property-list', 'close']);
  const searchTerm = ref('');
  let currentPage = ref(1);
  const pageSize = 10; // Number of items per page
  const numPagesToShow = 5; // Number of pagination buttons to show

  async function propertyList() {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.get('/api/properties', {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
      properties.value = response.data;
    } catch (error) {
      console.error('Error fetching properties:', error.response ? error.response.data : error.message);
    }
  }

  async function deleteData(objId) {
  console.log(objId);
  const confirmed = confirm("Are you sure you want to delete this Property?");
  if (confirmed) {
    try {
      await axios.delete(`/api/properties/${objId}`);
      await propertyList(); // Wait for deletion to complete and then update the list
    } catch (error) {
      console.error('Error deleting property:', error.response ? error.response.data : error.message);
    }
  }
}

  function openModal(objId) {
    console.log('in');
    showModal.value = true;
    if (objId) {
      propertyFormModal.value.loadPropertyData(objId);
    }
  };

  function close() {
    showModal.value = false;
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

