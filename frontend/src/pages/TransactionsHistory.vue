<template>
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">История операций</h5>
      <input
          v-model="search"
          type="text"
          class="form-control w-50"
          placeholder="Поиск по описанию"
      />
    </div>
    <div class="card-body p-0">
      <table class="table table-hover mb-0">
        <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Тип</th>
          <th>Сумма</th>
          <th>Описание</th>
          <th @click="toggleSort()" style="cursor: pointer">
            Дата
            <span v-if="sortDesc">🔽</span>
            <span v-else>🔼</span>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="tx in filteredAndSortedTransactions"
            :key="tx.id"
        >
          <td>{{ tx.id }}</td>
          <td>
              <span
                  :class="{
                  'badge bg-success': tx.type === 'credit',
                  'badge bg-danger': tx.type === 'debit'
                }"
              >
                {{ tx.type_translate || tx.type }}
              </span>
          </td>
          <td>{{ formatAmount(tx.amount) }} ₽</td>
          <td>{{ tx.description || 'Без описания' }}</td>
          <td>{{ formatDate(tx.created_at) }}</td>
        </tr>

        <tr v-if="filteredAndSortedTransactions.length === 0">
          <td colspan="5" class="text-center text-muted">Нет операций</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script>
import api from "../services/api.js";

export default {
  data() {
    return {
      transactions: [],
      search: "",
      sortDesc: true, // по умолчанию сортировка по убыванию даты
    };
  },
  computed: {
    filteredAndSortedTransactions() {
      let result = this.transactions;

      // фильтр по описанию
      if (this.search.trim() !== "") {
        const searchLower = this.search.toLowerCase();
        result = result.filter(tx =>
            (tx.description || "").toLowerCase().includes(searchLower)
        );
      }

      // сортировка по дате
      return result.sort((a, b) => {
        const dateA = new Date(a.created_at);
        const dateB = new Date(b.created_at);
        return this.sortDesc ? dateB - dateA : dateA - dateB;
      });
    },
  },
  methods: {
    async fetchTransactions() {
      try {
        const response = await api.get("/me/balance-transactions?per_page=30");
        this.transactions = response.data;
      } catch (err) {
        console.error("Ошибка при получении транзакций:", err);
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString();
    },
    formatAmount(amount) {
      return parseFloat(amount).toFixed(2);
    },
    toggleSort() {
      this.sortDesc = !this.sortDesc;
    },
  },
  mounted() {
    this.fetchTransactions();
  },
};
</script>

<style scoped>
th {
  user-select: none;
}
</style>
