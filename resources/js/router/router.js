// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Login from '../auth/Login.vue';
import AdminDashboard from '../components/AdminDashboard.vue';
import Property from '../components/Property.vue';
import UserDashboard from '../components/UserDashboard.vue';
import ProjectDetails from '../components/ProjectDetails.vue';
import axios from 'axios';

const routes = [
  {
    path: '/',
    name: 'home',
    component: UserDashboard,
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/admin/dashboard',
    name: 'admin.dashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/admin/properties',
    name: 'admin.property',
    component: Property,
    meta: { requiresAuth: true, role: 'admin' },
  },
  {
    path: '/property/:id',
    name: 'PropertyDetails',
    component: ProjectDetails,
    meta: { requiresAuth: true, role: 'user' },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('token');
    if (!token) {
      return next({ name: 'login', query: { redirect: to.fullPath } });
    }
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    if (to.meta.role && to.meta.role !== localStorage.getItem('role')) {
      return next({ name: 'login' });
    }
    next();
  } else {
    next();
  }
});

export default router;
