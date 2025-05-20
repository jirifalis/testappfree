<script setup lang="ts">
import {onMounted, ref} from 'vue'
import {useRoute} from 'vue-router';
import PermissionSelector from "@/components/Selector/PermissionSelector.vue";
import {GroupApiService} from "@/services/Api/GroupApiService.ts";
import type {Group} from "@/types/Group.ts";
import NameUpdater from "@/components/NameUpdater.vue";

const route = useRoute();
const group_id = parseInt(route.params.id.toString());

const group = ref<Group>();
const loading = ref(true);
const error = ref('');


const groupService = new GroupApiService();

const fetchGroup = async () => {
  try {
    group.value = await groupService.getById(group_id);
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Unknown error occurred';
  } finally {
    loading.value = false;
  }
};


onMounted(() => {
  fetchGroup();
});
</script>
<template>
  <div v-if="loading">Loading...</div>
  <div v-else-if="error">{{ error }}</div>
  <div v-else>
    <div v-if="group">
      <NameUpdater
        :id="group_id"
        :name="group.name"
        :service="groupService"></NameUpdater>

      <h2>Group permissions</h2>
      <PermissionSelector :group="group"></PermissionSelector>

    </div>

  </div>
</template>
