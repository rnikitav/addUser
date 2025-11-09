<template>
  <div class="container py-5" v-if="currentUser">
    <h2 class="mb-4 text-center">Привет, {{ currentUser.login }}!</h2>
    <h4 class="mb-4 text-center">Email - {{ currentUser.email }}!</h4>

    <!-- Баланс -->
    <div class="card text-center mb-4 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Текущий баланс</h5>
        <p class="display-4 mb-0">{{ balance }} ₽</p>
      </div>
    </div>

    <!-- Последние операции -->
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Последние операции</h5>
      </div>
      <ul class="list-group list-group-flush">
        <li
            v-for="tx in transactions"
            :key="tx.id"
            class="list-group-item d-flex justify-content-between align-items-center"
        >
          <div>
            <strong class="me-1">{{ tx.type === 'credit' ? '⬆️' : '⬇️' }} {{ tx.type_translate }}</strong> #{{ tx.id }}
            <br>
            <small class="text-muted">{{ formatDate(tx.created_at) }}</small>
            <br>
            <em v-if="tx.description" class="text-muted">{{ tx.description }}</em>
          </div>
          <span :class="{'text-success': tx.type === 'credit', 'text-danger': tx.type === 'debit'}">
      {{ Number(tx.amount).toFixed(2) }} ₽
    </span>
        </li>

        <li v-if="transactions.length === 0" class="list-group-item text-center text-muted">
          Нет операций
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import api from "../services/api.js";

export default {
  name: 'Profile',
  data() {
    return {
      balance: 0,
      transactions: [],
      REFRESH_INTERVAL: 10000,
      intervalId: null
    }
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    }
  },
  methods: {
    async fetchProfile() {
      if (!this.currentUser) return;

      try {
        const response = await api.get("/me/balance-transactions");

        const newTransactions = response.data;

        if (newTransactions){
          const lastCurrentId = this.transactions[0]?.id;
          const lastNewId = newTransactions[0]?.id;

          // Обновляем только если есть новые транзакции
          if (lastCurrentId !== lastNewId) {
            this.transactions = newTransactions;
            this.balance = newTransactions[0]?.balance_after
          }
        }
      } catch (error) {
        console.error('Ошибка при получении данных профиля:', error)
      }  finally {
        this.scheduleNextFetch();
      }
    },
    scheduleNextFetch() {
      this.timeoutId = setTimeout(() => {
        this.fetchProfile();
      }, this.REFRESH_INTERVAL);
    },

    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString();
    }
  },
  mounted() {
    if (!this.currentUser) {
      this.$router.push('/login')
      return
    }
    this.balance = this.currentUser.balance;
    this.transactions = this.currentUser.balanceTransactions;

    this.scheduleNextFetch();
  },
  beforeUnmount() {
    if (this.timeoutId) {
      clearTimeout(this.timeoutId);
    }
  }
}
</script>

<style scoped>
.card {
  border-radius: 12px;
}

.card-header {
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
}

.display-4 {
  font-weight: 600;
}
</style>
