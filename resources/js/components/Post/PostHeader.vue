<script setup lang="ts">
import { Eye } from "lucide-vue-next"
import { Badge } from "@/components/ui/badge"

const props = defineProps<{
  title: string
  tags: { id: number; name: string }[]
  views: number
}>()
</script>

<template>
  <h1 class="text-3xl font-bold mb-2">{{ title }}</h1>

  <div class="flex flex-wrap gap-2 my-3">
    <Badge v-for="tag in tags" :key="tag.id">
      {{ tag.name }}
    </Badge>
  </div>

  <div class="flex items-center text-sm text-muted-foreground gap-1 mb-4">
    <Eye class="h-4 w-4" />
    {{ views }} visualizações
  </div>
</template>
