<template>
    <!-- Loader -->
    <Loader v-if="isLoading" />

    <!-- Main Content -->
    <div v-if="!isLoading">
      <!-- Section 1 -->
      <Header />

      <!-- Search Bar -->
      <section class="bg-gray-50 py-12">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
          <h2 class="text-2xl font-extrabold text-gray-900 sm:text-3xl lg:text-4xl text-center mb-8">Latest Properties</h2>

          <!-- Search Input -->
          <div class="flex justify-center mb-8">
            <input v-model="searchQuery" @input="searchProperties" type="text" placeholder="Search properties..." class="px-4 py-2 border border-gray-300 rounded-lg w-full sm:w-1/2">
          </div>

          <!-- Filters and Badges -->
          <div class="flex items-center mb-8">
            <!-- Applied Filters Badges -->
            <div class="flex space-x-2 mb-4">
              <span v-if="priceRangeMin || priceRangeMax" class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                Price: {{ priceRangeMin }} - {{ priceRangeMax }}
                <button @click="clearPriceRange" class="ml-2 text-red-500">×</button>
              </span>
              <span v-if="propertyType" class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                Type: {{ propertyTypes.find(type => type.id === propertyType)?.type }}
                <button @click="clearPropertyType" class="ml-2 text-red-500">×</button>
              </span>
              <span v-if="startDate || endDate" class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                Date: {{ startDate }} - {{ endDate }}
                <button @click="clearDateRange" class="ml-2 text-red-500">×</button>
              </span>
            </div>

            <!-- Filter Icon -->
            <div class="flex-grow flex justify-end">
              <button @click="showFilterModal = true" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Filters
              </button>
            </div>
          </div>

          <!-- Properties Grid -->
          <div class="grid grid-cols-1 gap-12 lg:grid-cols-3 lg:gap-16">
            <div v-for="property in paginatedProperties" :key="property.id" class="bg-white shadow-lg rounded-lg overflow-hidden relative">
              <figure class="relative">
                <img v-if="property.images && property.images.length > 0" :src="property.images[0]?.image_url" :alt="property.images[0]?.title || 'Property Image'" class="w-full h-64 object-cover object-center">
                <div v-else class="w-full h-64 bg-gray-200 flex items-center justify-center">
                  <span class="text-gray-500">No Image Available</span>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 opacity-30"></div>
                <div class="absolute top-0 right-0 m-4 bg-blue-500 text-white text-sm font-semibold px-3 py-1 rounded">
                  {{ property.type }}
                </div>
              </figure>
              <div class="p-4">
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">{{ property.name }}</h3>
                <p class="text-gray-600 mb-4">
                  <span class="font-medium text-gray-800">State:</span> {{ property.state }}<br>
                  <span class="font-medium text-gray-800">Country:</span> {{ property.country }}
                </p>
                <p class="text-gray-600 mb-4 flex items-center space-x-4">
                  <i class="fas fa-dollar-sign text-gray-500"></i>
                  <span class="font-medium text-gray-800">Price:</span> {{ property.price }}<br>
                  <i class="fas fa-bed text-gray-500"></i>
                  <span class="font-medium text-gray-800">Bed:</span> {{ property.bedrooms }}<br>
                  <i class="fas fa-bath text-gray-500"></i>
                  <span class="font-medium text-gray-800">Bath:</span> {{ property.bathrooms }}
                </p>
                <div class="absolute bottom-0 left-0 m-4 text-gray-600 text-sm">
                  <div class="badge badge-primary">{{ new Date(property.created_at).toLocaleDateString() }}</div>
                </div>
                <div class="flex justify-end">
                  <router-link :to="{ name: 'PropertyDetails', params: { id: property.id } }" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Get Details
                  </router-link>
                </div>
              </div>
            </div>
          </div>

          <!-- Load More Button -->
          <div class="flex justify-center mt-8">
            <button v-if="hasMore" @click="loadMore" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
              Load More
            </button>
          </div>
        </div>
      </section>

      <!-- Filter Modal -->
      <div v-if="showFilterModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full">
          <h3 class="text-xl font-semibold mb-4">Filter Properties</h3>
          <form @submit.prevent="applyFilters">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Price Range</label>
              <input type="range" v-model="priceRangeMin" min="0" max="1000000" step="10000" class="w-full">
              <input type="range" v-model="priceRangeMax" min="0" max="1000000" step="10000" class="w-full mt-2">
              <div class="flex justify-between text-sm text-gray-600">
                <span>{{ priceRangeMin }}</span>
                <span>{{ priceRangeMax }}</span>
              </div>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Property Type</label>
              <select v-model="propertyType" class="px-4 py-2 border border-gray-300 rounded-md w-full">
                <option value="">All Types</option>
                <option v-for="type in propertyTypes" :key="type.id" :value="type.id">{{ type.type }}</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Date Range</label>
              <input type="date" v-model="startDate" class="px-4 py-2 border border-gray-300 rounded-md w-full mb-2">
              <input type="date" v-model="endDate" class="px-4 py-2 border border-gray-300 rounded-md w-full">
            </div>
            <div class="flex justify-end space-x-4">
              <button type="button" @click="showFilterModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">Cancel</button>
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Apply Filters</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  <script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Loader from './Loader.vue';
import Header from '../frontpartials/Header.vue';

const properties = ref([]);
const paginatedProperties = computed(() => {
  return properties.value.slice(0, page.value * itemsPerPage.value);
});
const propertyTypes = ref([]);
const isLoading = ref(true);
const showFilterModal = ref(false);
const priceRangeMin = ref(0);
const priceRangeMax = ref(1000000);
const propertyType = ref('');
const startDate = ref('');
const endDate = ref('');
const hasMore = ref(true);
const page = ref(1);
const itemsPerPage = ref(9);
const searchQuery = ref('');

async function fetchProperties() {
  try {
    const response = await axios.get('/api/properties/latest');
    properties.value = response.data;
    hasMore.value = response.data.length > page.value * itemsPerPage.value;
  } catch (error) {
    console.error(error);
  } finally {
    isLoading.value = false;
  }
}

async function fetchPropertyTypes() {
  try {
    const response = await axios.get('/api/type/property');
    propertyTypes.value = response.data;
  } catch (error) {
    console.error('Error fetching property types:', error);
  }
}

async function applyFilters() {
  try {
    const response = await axios.get('/api/properties/filter', {
      params: {
        minPrice: priceRangeMin.value,
        maxPrice: priceRangeMax.value,
        typeId: propertyType.value,
        startDate: startDate.value,
        endDate: endDate.value
      }
    });
    properties.value = response.data;
    hasMore.value = response.data.length > page.value * itemsPerPage.value;
    showFilterModal.value = false;
  } catch (error) {
    console.error(error);
  }
}

async function searchProperties() {
  try {
    const response = await axios.get('/api/properties/search', {
      params: {
        query: searchQuery.value
      }
    });
    properties.value = response.data;
    hasMore.value = response.data.length > page.value * itemsPerPage.value;
  } catch (error) {
    console.error('Error searching properties:', error);
  }
}

function loadMore() {
  page.value += 1;
  hasMore.value = properties.value.length > page.value * itemsPerPage.value;
}

function clearPriceRange() {
  priceRangeMin.value = 0;
  priceRangeMax.value = 1000000;
  applyFilters();
}

function clearPropertyType() {
  propertyType.value = '';
  applyFilters();
}

function clearDateRange() {
  startDate.value = '';
  endDate.value = '';
  applyFilters();
}

onMounted(async () => {
  await fetchPropertyTypes();
  await fetchProperties();
});
</script>
