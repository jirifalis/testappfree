<script setup lang="ts">
import {ref, onMounted, defineProps} from 'vue'
import {ResourceApiService} from "@/services/Api/ResourceApiService.ts";
import {GroupApiService} from "@/services/Api/GroupApiService.ts";
import SaveButton from "@/components/Button/SaveButton.vue";

const props = defineProps<{
  group: {
    id: number;
    name: string;
    permissions: Array<{ resource: number }>;
  }
}>()

const resources = ref<Array<{ id: number; name: string; selected: boolean }>>([])
const loading = ref(true)
const saving = ref(false)
const error = ref('')

const resourcesService = new ResourceApiService();
const groupService = new GroupApiService();

const fetchResources = async () => {
  try {
    const response = await resourcesService.getList()
    resources.value = response.map(resource => ({
      ...resource,
      selected: props.group.permissions.some(p => p.resource === resource.id)
    }))
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Unknown error occurred'
  } finally {
    loading.value = false
  }
}

const handleSave = async () => {
    const group_id = props.group.id;
    const selectedResources = resources.value.filter(r => r.selected).map(r => r.id)
    await groupService.setResources(group_id, selectedResources)
}
const setResourceActive = (id: number, active: boolean) => {
  const resource = resources.value.find(r => r.id === id)
  if (resource) {
    resource.selected = active
  }
}

onMounted(() => {
  fetchResources()
})
</script>

<template>
  <div class="group-selector">
    <div v-if="loading">Loading...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else>
      <div v-for="resource in resources" :key="resource.id">

        <button @click="setResourceActive(resource.id, true)"
                :class="{perm:'perm', active: resource.selected, inactive: !resource.selected}">PERMISSION
        </button>
        <button @click="setResourceActive(resource.id, false)"
                :class="{rest:'rest',active: !resource.selected, inactive: resource.selected}">RESTRICTION
        </button>

        <span class="group-name">{{ resource.name }}</span>

      </div>

    </div>

    <SaveButton :on-save="handleSave"></SaveButton>
  </div>
</template>

<style scoped>


.group-name{
  padding-left: 20px;
}
.inactive {
  background-color: #838383;
  color: white;
}
.perm.active {
  background-color: #4CAF50;
  color: white;
}
.rest.active {
  background-color: #ea1616;
  color: white;
}
.error {
  color: red;
}
</style>
