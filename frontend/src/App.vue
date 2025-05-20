<script setup lang="ts">
import {RouterView} from 'vue-router'
</script>

<template>

  <h1>Test App</h1>

  <RouterLink :to="{ name: 'Index' }"> index</RouterLink>
  <RouterLink :to="{ name: 'Users' }"> users</RouterLink>
  <RouterLink :to="{ name: 'Groups' }"> groups</RouterLink>
  <RouterLink :to="{ name: 'CreateGroup' }"> create group</RouterLink>

  <hr>
  <RouterView/>
</template>
