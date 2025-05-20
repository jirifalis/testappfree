<script setup lang="ts">
import {onMounted, ref} from 'vue'
import TableViewComponent from "@/components/TableViewComponent.vue";
import {UserApiService} from "@/services/Api/UserApiService.ts";
import type {User} from "@/types/User.ts";

const items = ref<User[]>([]);
const loading = ref(true);
const error = ref('');

const fetchItems = async () => {
  const userService = new UserApiService();
  try {
    items.value = await userService.getList();
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Unknown error occurred';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchItems();
});

</script>
<template>
  <TableViewComponent :items="items" :loading="loading" :error="error" detail-route="User"></TableViewComponent>
</template>
