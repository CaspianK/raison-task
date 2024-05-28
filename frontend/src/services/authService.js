import requestService from "./requestService";
import { ref } from "vue";

const token = ref(localStorage.getItem("token"));

const authService = {
  async login(email, password) {
    const response = await requestService.post("/login", { email, password });

    if (response.data.token) {
      localStorage.setItem("token", response.data.token);
      token.value = response.data.token;
    }

    return response.data;
  },
  logout() {
    localStorage.removeItem("token");
    token.value = null;
  },
  getToken() {
    decodeURI(token.value);
    return token.value;
  },
};

export default authService;
