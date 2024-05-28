import axios from "axios";
import authService from "./authService";

const requestService = axios.create({
  baseURL: "http://127.0.0.1/api",
  headers: {
    Accept: "application/json",
  },
});

requestService.interceptors.request.use((config) => {
  const token = authService.getToken();
  if (token) {
    config.headers["Authorization"] = `Bearer ${token}`;
  }
  return config;
});

export default requestService;
