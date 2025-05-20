<script setup lang="ts">
import {ref} from 'vue'
import {useRoute, useRouter} from 'vue-router';
import {GroupApiService} from "@/services/Api/GroupApiService.ts";
import SaveButton from "@/components/Button/SaveButton.vue";

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const saving = ref(false);
const error = ref('');
const name = ref('');


const groupService = new GroupApiService();


const createGroup = async () => {
    const newGroup = await groupService.create(name.value);
    await router.push({name: 'Group', params: {id: newGroup.id}});
};

</script>
<template>
  <div v-if="error">{{ error }}</div>
  <div v-else>
    <div>
      <input v-model="name" type="text">
      <SaveButton :on-save="createGroup" label="create"></SaveButton>
    </div>
  </div>
</template>
