import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';
import PublicLayout from '@/layouts/PublicLayout.vue';
import HomePage from '@/pages/home/HomePage.vue';
import LoginPage from '@/pages/auth/LoginPage.vue';
import CuratorLoginPage from '@/pages/auth/CuratorLoginPage.vue';
import RegisterApplicantPage from '@/pages/auth/RegisterApplicantPage.vue';
import RegisterEmployerPage from '@/pages/auth/RegisterEmployerPage.vue';
import ApplicantProfilePage from '@/pages/applicant/ApplicantProfilePage.vue';
import { useAuthStore } from '@/features/auth/model/auth.store';

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
      {
        path: 'applicant/profile',
        name: 'applicant-profile',
        component: ApplicantProfilePage,
        meta: {
          requiresAuth: true,
          roles: ['applicant'],
        },
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

router.beforeEach(async (to) => {
  const authStore = useAuthStore();

  if (authStore.token && !authStore.user) {
    try {
      await authStore.fetchMe();
    } catch {
      authStore.clearAuth();
    }
  }

  const requiresAuth = Boolean(to.meta.requiresAuth);
  const allowedRoles = Array.isArray(to.meta.roles) ? to.meta.roles : null;

  if (requiresAuth && !authStore.isAuthenticated) {
    return { name: 'login' };
  }

  if (allowedRoles && authStore.user && !allowedRoles.includes(authStore.user.role)) {
    return { name: 'home' };
  }

  return true;
});

export default router;
