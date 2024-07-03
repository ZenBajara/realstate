<template>
    <div v-if="show" class="modal-wrapper flex content-center items-center animated fadeIn faster">
      <div class="fixed inset-0 w-full h-full flex justify-center items-center bg-black bg-opacity-50 z-50">
        <div id="modal" class="w-10/12 h-5/6 rounded-xl bg-white p-4 overflow-auto">
          <form @submit.prevent="submitForm">
            <h2 class="text-2xl font-bold mb-4">{{ isEditMode ? 'Edit Property' : 'Add Property' }}</h2>
            <!-- Other form fields -->
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                  Name
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="name" type="text" v-model="property.name">
              </div>
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="address">
                  Address
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="address" type="text" v-model="property.address">
              </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="city">
                  City
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="city" type="text" v-model="property.city">
              </div>
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="pincode">
                  Pincode
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="pincode" type="text" v-model="property.pincode">
              </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="state">
                  State
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="state" type="text" v-model="property.state">
              </div>
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="country">
                  Country
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="country" type="text" v-model="property.country">
              </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="type">
                  Type
                  <button type="button" class="ml-2 text-blue-500 hover:text-blue-700" @click="addNewType">
                    <i class="fas fa-plus"></i>
                  </button>
                </label>
                <select class="select select-bordered w-full bg-gray-200" v-model="property.property_type_id">
                  <option v-for="type in propertyTypes" :key="type.id" :value="type.id">{{ type.type }}</option>
                </select>
              </div>
              <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="num_bathrooms">
                  Number of Bathrooms
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="num_bathrooms" type="number" v-model="property.num_bathrooms">
              </div>
              <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="num_bedrooms">
                  Number of Bedrooms
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="num_bedrooms" type="number" v-model.number="property.num_bedrooms">
              </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="price">
                  Price
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="price" type="number" step="0.01" v-model.number="property.price">
              </div>
              <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 flex items-end">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_featured">
                  Is Featured
                </label>

                <input
                  id="is_featured"
                  type="checkbox"
                  class="toggle toggle-primary ml-2"
                  v-model="property.is_featured"
                />
              </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
              <div class="w-full px-3 mb-6">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="images">
                  Images
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" id="images" type="file" multiple @change="handleFileUpload">
              </div>
            </div>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">{{ isEditMode ? 'Update Property' : 'Add Property' }}</button>
          </form>
        </div>
      </div>
      <button @click="close" class="absolute top-0 right-0 z-50 m-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">X</button>
    </div>
  </template>
  <script setup>
import { ref, defineProps, defineEmits, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const property = ref({
  name: '',
  address: '',
  city: '',
  state: '',
  country: '',
  pincode: '',
  property_type_id: '',
  num_bathrooms: 0,
  num_bedrooms: 0,
  price: 0,
  images: [],
  is_featured: false // Add this line
});

const propertyTypes = ref([]);
const isEditMode = ref(false);
const pointId = ref(null);
const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close', 'propertyList']);

async function fetchPropertyTypes() {
  try {
    const response = await axios.get('/api/type/property');
    propertyTypes.value = response.data;
  } catch (error) {
    console.error('Error fetching property types:', error);
  }
}

const loadPropertyData = async (id = null) => {
  if (id != null) {
    isEditMode.value = true;
    pointId.value = id;
    await getDataForEdit();
  }
};

async function submitForm() {
  let url = '';
  let method = 'POST';
  if (isEditMode.value) {
    url = `/api/properties/${pointId.value}`;
  } else {
    url = '/api/properties';
  }

  const formData = new FormData();
  formData.append('name', property.value.name);
  formData.append('address', property.value.address);
  formData.append('city', property.value.city);
  formData.append('state', property.value.state);
  formData.append('country', property.value.country);
  formData.append('pincode', property.value.pincode);
  formData.append('property_type_id', property.value.property_type_id);
  formData.append('num_bathrooms', property.value.num_bathrooms);
  formData.append('num_bedrooms', property.value.num_bedrooms);
  formData.append('price', property.value.price);
  formData.append('is_featured', property.value.is_featured ? '1' : '0'); // Add this line for is_featured

  for (const image of property.value.images) {
    formData.append('images[]', image);
  }

  try {
    const response = await axios({
      url,
      method,
      data: formData,
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    Swal.fire({
      icon: 'success',
      title: isEditMode.value ? 'Data updated Successfully...' : 'Data Inserted Successfully...',
      showConfirmButton: true
    });
    emit('propertyList');
    close();
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.response.data.message,
      showConfirmButton: true
    });
  }
}

async function getDataForEdit() {
  try {
    const response = await axios.get(`/api/properties/${pointId.value}`);
    const data = response.data;
    property.value.name = data.name;
    property.value.address = data.address;
    property.value.city = data.city;
    property.value.state = data.state;
    property.value.country = data.country;
    property.value.pincode = data.pincode;
    property.value.price = data.price;
    property.value.num_bedrooms = data.bedrooms;
    property.value.num_bathrooms = data.washrooms;
    property.value.property_type_id = data.type; // Assuming 'type' should be mapped to 'property_type_id'
    property.value.images = data.images.map(image => image.image_url); // Assuming images need URLs for display
    property.value.is_featured = data.is_featured == 1 ? true : false; // Add this line for is_featured
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.response.data.message,
      showConfirmButton: true
    });
  }
}

function handleFileUpload(event) {
  property.value.images = Array.from(event.target.files);
}

function addNewType() {
  Swal.fire({
    title: 'Enter new property type',
    input: 'text',
    inputLabel: 'Property Type',
    inputPlaceholder: 'Enter new property type',
    showCancelButton: true
  }).then(result => {
    if (result.isConfirmed && result.value) {
      propertyTypes.value.push(result.value);
    }
  });
}

function close() {
  isEditMode.value = false;
  resetValue();
  emit('close');
}

function resetValue() {
  property.value = {
    name: '',
    address: '',
    city: '',
    state: '',
    country: '',
    pincode: '',
    property_type_id: '',
    num_bathrooms: 0,
    num_bedrooms: 0,
    price: 0,
    images: [],
    is_featured: false // Add this line
  };
}

onMounted(() => {
  fetchPropertyTypes();
});

defineExpose({
  loadPropertyData
});
</script>
