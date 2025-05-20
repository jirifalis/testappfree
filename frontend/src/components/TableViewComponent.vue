<script setup lang="ts">
import type {User} from "@/types/User.ts";
import type {Group} from "@/types/Group.ts";

defineProps<{
  items: User[] | Group[];
  loading?: boolean;
  error?: string;
  detailRoute?: string;
}>();
</script>

<template>
  <div class="item-table">
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">{{ error }}</div>
    <table v-else>
      <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Info</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="item in items" :key="item.id">
        <td>{{ item.id }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.extra }}</td>
        <td>
          <RouterLink :to="{ name: detailRoute, params: {'id': item.id} }"> edit</RouterLink>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.item-table {
  width: 100%;
  margin: 1rem 0;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 0.5rem;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  font-weight: bold;
}
</style>
