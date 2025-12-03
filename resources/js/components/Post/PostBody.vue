<script setup lang="ts">
const props = defineProps<{
  body: string
}>()
</script>

<template>
  <div class="prose dark:prose-invert max-w-none mb-6">
    <div v-html="props.body"></div>
  </div>
</template>
