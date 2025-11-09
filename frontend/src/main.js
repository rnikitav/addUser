import { createApp } from 'vue';
import App from './App.vue';
import router from './router/index.js';
import store from "./store/index.js";
import "bootstrap/dist/css/bootstrap.min.css";
import { FontAwesomeIcon } from './plugins/font-awesome';
import { setAuthToken } from './services/api';

const savedUser = localStorage.getItem("user");
if (savedUser) {
  const userData = JSON.parse(savedUser);
  setAuthToken(userData);
}

createApp(App)
  .use(router)
  .use(store)
  .component("font-awesome-icon", FontAwesomeIcon)
  .mount("#app");
