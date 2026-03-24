import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import PublicLayout from '@/layouts/PublicLayout.vue';
import HomePage from '@/pages/home/HomePage.vue';
import LoginPage from '@/pages/auth/LoginPage.vue';
import CuratorLoginPage from '@/pages/auth/CuratorLoginPage.vue';
import RegisterApplicantPage from '@/pages/auth/RegisterApplicantPage.vue';
import RegisterEmployerPage from '@/pages/auth/RegisterEmployerPage.vue';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: HomePage,
      },
      {
        path: 'login',
        name: 'login',
        component: LoginPage,
      },
      {
        path: 'login/curator',
        name: 'curator-login',
        component: CuratorLoginPage,
      },
      {
        path: 'register/applicant',
        name: 'register-applicant',
        component: RegisterApplicantPage,
      },
      {
        path: 'register/employer',
        name: 'register-employer',
        component: RegisterEmployerPage,
      },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(): { top: number } {
    return { top: 0 };
  },
});

export default router;
