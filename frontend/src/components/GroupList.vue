<script setup lang="ts">
import {onMounted, ref} from 'vue'
import TableViewComponent from "@/components/TableViewComponent.vue";
import {GroupApiService} from "@/services/Api/GroupApiService.ts";
import type {Group} from "@/types/Group.ts";
const items = ref<Group[]>([]);
const loading = ref(true);
const error = ref('');

const fetchUsers = async () => {
  const groupService = new GroupApiService();
  try {
    items.value = await groupService.getList()
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Unknown error occurred';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchUsers();
});
</script>
<template>
  <TableViewComponent :items="items" :loading="loading" :error="error" detail-route="Group"></TableViewComponent>
</template>
