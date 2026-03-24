import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './app/router';
import './app/styles/main.css';
import { useAuthStore } from '@/features/auth/model/auth.store';

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);

const authStore = useAuthStore();
void authStore.bootstrap();

app.mount('#app');
