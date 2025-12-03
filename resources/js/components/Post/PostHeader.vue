<script setup lang="ts">
import { Eye } from "lucide-vue-next"
import { Badge } from "@/components/ui/badge"
import { Post } from "@/types";

const props = defineProps<Post>()
</script>

<template>
  <h1 class="text-3xl font-bold mb-2">{{ props.title }}</h1>

  <div class="flex flex-wrap gap-2 my-3">
    <Badge v-for="tag in props.tags" :key="tag.id">
      {{ tag.name }}
    </Badge>
  </div>

  <div class="flex items-center text-sm text-muted-foreground gap-1 mb-4">
    <Eye class="h-4 w-4" />
    {{ props.views }} visualizações
  </div>
</template>
