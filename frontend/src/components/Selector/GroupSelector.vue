<script setup lang="ts">
import {defineProps, onMounted, ref} from 'vue'
import {GroupApiService} from "@/services/Api/GroupApiService.ts";
import {UserApiService} from "@/services/Api/UserApiService.ts";
import type {User} from "@/types/User.ts";
import type {Group} from "@/types/Group.ts";
import SaveButton from "@/components/Button/SaveButton.vue";

const props = defineProps<{
  user: User
}>()

const groups = ref<Group[]>([])
const loading = ref(true)
const error = ref('')

const groupService = new GroupApiService()
const userService = new UserApiService()

const fetchGroups = async () => {
  try {
    const response = await groupService.getList()
    groups.value = response.map(group => ({
      ...group,
      selected: props.user.groups.some(p => p.id === group.id)
    }))
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Unknown error occurred'
  } finally {
    loading.value = false
  }
}

const savePermissions = async () => {
  const user_id = props.user.id;
  const selectedGroups = groups.value.filter(r => r.selected).map(r => r.id)
  await userService.setGroups(user_id, selectedGroups)
}
const setGroupActive = (id: number, active: boolean) => {
  const g = groups.value.find(r => r.id === id)
  if (g) {
    g.selected = active
  }
}
onMounted(() => {
  fetchGroups()
})
</script>

<template>
  <div class="group-selector">
    <div v-if="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else class="resource-list">
      <div v-for="resource in groups" :key="resource.id" class="resource-item">

        <button @click="setGroupActive(resource.id, true)"
                :class="{active: resource.selected, inactive: !resource.selected}">Included
        </button>
        <button @click="setGroupActive(resource.id, false)"
                :class="{active: !resource.selected, inactive: resource.selected}">Excluded
        </button>
        <span class="group-name">{{ resource.name }}</span>

      </div>

      <SaveButton :on-save="savePermissions"></SaveButton>
    </div>
  </div>
</template>

<style scoped>

.group-name {
  padding-left: 20px;
}

.resource-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.resource-item {
  display: flex;
  align-items: center;
}

.error {
  color: red;
}

button {
}

.active {
  background-color: #4CAF50;
  color: white;
}

.inactive {
  background-color: #5c5c5c;
  color: white;
}

</style>
