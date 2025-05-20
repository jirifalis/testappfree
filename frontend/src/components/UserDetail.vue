<script setup lang="ts">
import {onMounted, ref} from 'vue'
import {useRoute} from 'vue-router';
import GroupSelector from "@/components/Selector/GroupSelector.vue";
import {UserApiService} from "@/services/Api/UserApiService.ts";


import type {User} from "@/types/User.ts";
import NameUpdater from "@/components/NameUpdater.vue";


const route = useRoute();
const user_id = parseInt(route.params.id.toString());

const user = ref<User>();
const loading = ref(true);
const error = ref('');


const userService = new UserApiService()

const fetchUser = async () => {

  loading.value = true;
  try {
    user.value = await userService.getById(user_id);
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Unknown error occurred';
  } finally {
    loading.value = false;
  }
};


onMounted(() => {
  fetchUser();
});
</script>
<template>
  <div v-if="loading">Loading...</div>
  <div v-else-if="error">{{ error }}</div>
  <div v-else>
    <div v-if="user">
      <NameUpdater
          :id="user_id"
          :name="user.name"
          :service="userService"></NameUpdater>


      <h2>Select group:</h2>
      <GroupSelector :user="user"></GroupSelector>

    </div>
  </div>
</template>
