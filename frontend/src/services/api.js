import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:44488/api/v1/', // порт Laravel
  withCredentials: true,
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json"
  }
})

api.interceptors.response.use(
  (response) => {
    if (response.data && response.data.data) {
      response.data = response.data.data; // flatten
    }
    return response;
  },
  (error) => Promise.reject(error)
);

// Функция для установки токена
export function setAuthToken(data) {
  let token = data?.token
  if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`
  } else {
    delete api.defaults.headers.common['Authorization']
  }
}

export default api
