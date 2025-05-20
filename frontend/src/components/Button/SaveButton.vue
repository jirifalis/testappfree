<script setup lang="ts">
import {ref} from 'vue'

const props = defineProps<{
  onSave: any, //todo: fix this
  label?: string;
}>()


const saving = ref(false);
const error = ref('');
const saved = ref(false);

const handleSave = async () => {
  try {
    error.value = '';
    saved.value = false;
    saving.value = true;
    await props.onSave();
    saved.value = true;
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Unknown error occurred';
  } finally {
    saving.value = false;
  }
};

</script>
<template>
  <button class="is-primary" @click="handleSave">{{ label || 'save' }}</button>
  <span v-if="saving" class="saving">⏳</span>
  <span v-if="saved && !error" class="saved fadeOut">👍</span>
  <span v-if="error" class="error">❌ {{ error }}</span>
</template>
<style scoped>
.error {
  color: red;
  margin-left: 10px;
}

.saving {
  color: green;
  margin-left: 10px;
}

.saved {
  color: green;
  margin-left: 10px;
}

.fadeOut {
  animation: fadeOut 1s forwards;
}

@keyframes fadeOut {
  0% {
    opacity: 1;
  }
  70% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}

</style>
