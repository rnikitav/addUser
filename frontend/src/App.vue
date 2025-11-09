<template>
  <div id="app">
    <nav class="navbar navbar-expand navbar-dark bg-dark">
      <div class="navbar-nav mr-auto">
        <ul class="d-flex">
          <li class="nav-item">
            <router-link to="/" class="nav-link">
              <font-awesome-icon icon="home" /> Home
            </router-link>
          </li>
          <li v-if="currentUser" class="nav-item">
            <a class="nav-link" @click.prevent="logOut">
              <font-awesome-icon icon="sign-out-alt" /> LogOut
            </a>
          </li>
        </ul>

      </div>

      <div v-if="!currentUser" class="navbar-nav ml-auto">
        <ul>
          <li class="nav-item">
            <router-link to="/login" class="nav-link">
              <font-awesome-icon icon="sign-in-alt" /> Login
            </router-link>
          </li>
        </ul>
      </div>

      <div v-if="currentUser" class="navbar-nav ml-auto">
        <ul class="d-flex">
          <li class="nav-item">
            <router-link to="/profile" class="nav-link">
              <font-awesome-icon icon="user" />
              {{ currentUser.login }}
            </router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/transactions">История транзакций</router-link>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container">
      <router-view />
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    logOut() {
      this.$store.dispatch('auth/logout');
      this.$router.push('/login');
    }
  }
};
</script>
<style scoped>
.nav-item {
  margin-left: 10px;
}
</style>
