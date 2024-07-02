<template>
    <div v-if="property" class="px-2">
      <!-- Navigation Section -->
      <Header />

      <!-- Property Details Section -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="flex flex-col lg:flex-row">
          <!-- Image Carousel -->
          <div class="w-full lg:w-2/3">
            <div class="carousel w-full">
              <div v-for="(image, index) in property.images" :key="index" :id="'slide' + (index + 1)" class="carousel-item relative w-full">
                <img :src="image.image_url" class="w-full h-64 object-cover" />
                <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                  <a :href="'#slide' + (index === 0 ? property.images.length : index)" class="btn btn-circle bg-gray-800 text-white">❮</a>
                  <a :href="'#slide' + (index + 2 > property.images.length ? 1 : index + 2)" class="btn btn-circle bg-gray-800 text-white">❯</a>
                </div>
              </div>
            </div>
            <!-- Thumbnail Previews -->
            <div class="flex space-x-2 mt-4">
              <div v-for="(image, index) in property.images" :key="'thumb-' + index" class="w-1/4">
                <a :href="'#slide' + (index + 1)">
                  <img :src="image.image_url" class="w-full h-20 object-cover cursor-pointer border-2 border-gray-300 rounded-lg hover:border-gray-600" />
                </a>
              </div>
            </div>
          </div>

          <!-- Property Details -->
          <div class="w-full lg:w-1/3 p-6 bg-gray-100">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">{{ property.name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-gray-600">

              <div class="flex items-center space-x-2">
                <i class="fas fa-city text-gray-800"></i>
                <p><span class="font-medium text-gray-800">City:</span> {{ property.city }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-home text-gray-800"></i>
                <p><span class="font-medium text-gray-800">State:</span> {{ property.state }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-flag text-gray-800"></i>
                <p><span class="font-medium text-gray-800">Country:</span> {{ property.country }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-dollar-sign text-gray-800"></i>
                <p><span class="font-medium text-gray-800">Price:</span> {{ property.price }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-bed text-gray-800"></i>
                <p><span class="font-medium text-gray-800">Bed:</span> {{ property.bedrooms }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-bath text-gray-800"></i>
                <p><span class="font-medium text-gray-800">Bath:</span> {{ property.bathrooms }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-code-branch text-gray-800"></i>
                <p><span class="font-medium text-gray-800">Type:</span> {{ property.type }}</p>
              </div>
              <div class="flex items-center space-x-2">
                <i class="fas fa-thumbtack text-gray-800"></i>
                <p><span class="font-medium text-gray-800">Pincode:</span> {{ property.pincode }}</p>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <i class="fas fa-map-marker-alt text-gray-800"></i>
              <p><span class="font-medium text-gray-800">Address:</span> {{ property.address }}</p>
            </div>
            <p class="text-gray-600 mb-4"><span class="font-medium text-gray-800">Description:</span> {{ property.description }}</p>
            <p class="text-gray-600"><span class="font-medium text-gray-800">Created At:</span> <div class="badge badge-primary">{{ new Date(property.created_at).toLocaleDateString() }}</div></p>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { useRoute } from 'vue-router';
  import Header from '../frontpartials/Header.vue';

  const route = useRoute();
  const property = ref(null);

  async function fetchPropertyDetails(id) {
    try {
      const response = await axios.get(`/api/properties/${id}`);
      property.value = response.data;
    } catch (error) {
      console.error('Error fetching property details:', error);
    }
  }

  onMounted(() => {
    const propertyId = route.params.id;
    fetchPropertyDetails(propertyId);
  });
  </script>

  <style scoped>
  /* Carousel button styles */
  .btn-circle {
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 0.5rem;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  .btn-circle:hover {
    background-color: rgba(0, 0, 0, 0.8);
  }

  /* Custom container styles */
  .container {
    max-width: 1200px;
  }

  /* Typography improvements */
  h2 {
    font-size: 2.25rem; /* 36px */
    line-height: 2.5rem; /* 40px */
  }
  .font-medium {
    font-weight: 500;
  }
  .text-gray-600 {
    color: #718096;
  }
  .text-gray-800 {
    color: #2D3748;
  }

  /* Font Awesome icon sizes */
  .fas {
    font-size: 1.5rem;
  }
  </style>
