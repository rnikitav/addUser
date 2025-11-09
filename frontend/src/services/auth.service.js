import api, {setAuthToken} from "../services/api.js";

class AuthService {
  async login(user) {
    const response = await api.post("/login", {
      login: user.login,
      password: user.password
    });

    if (response.data.token) {
      localStorage.setItem("user", JSON.stringify(response.data));
      setAuthToken(response.data);
    }

    return response.data;
  }

  logout() {
    setAuthToken(null);
    localStorage.removeItem('user');
  }
}

export default new AuthService();
