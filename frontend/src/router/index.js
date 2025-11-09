import { createRouter, createWebHistory } from 'vue-router';

import Home from '../pages/Home.vue';
import Login from '../pages/Login.vue';
//lazy-load
const Profile = () => import("../pages/Profile.vue");
import TransactionsHistory from "../pages/TransactionsHistory.vue";


const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/login', name: 'login', component: Login },
  { path: '/profile', name: 'profile', component: Profile},
  { path: '/transactions', name: 'transactions', component: TransactionsHistory }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
