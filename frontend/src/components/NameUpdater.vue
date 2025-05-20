<script setup lang="ts">
import {ref} from 'vue'
import SaveButton from "@/components/Button/SaveButton.vue";

const props = defineProps<{
  id: number
  name: string
  service: any //todo: fix this
}>()

const name = ref(props.name);

const updateName = async () => {
  await props.service.updateName(props.id, name.value);
};

</script>
<template>
  <input v-model="name" type="text">
  <SaveButton :on-save="updateName"></SaveButton>
</template>
