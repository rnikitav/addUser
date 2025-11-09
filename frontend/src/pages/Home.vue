<template>
  <div class="d-flex flex-column align-items-center justify-content-center vh-100 bg-light">
    <h1 class="mb-4">Добро пожаловать!</h1>
    <div v-if="!currentUser">
      <p class="text-secondary mb-4">Войдите, чтобы продолжить</p>


      <RouterLink to="/login" class="btn btn-primary btn-lg">
        Перейти к логину
      </RouterLink>
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
}
</script>
