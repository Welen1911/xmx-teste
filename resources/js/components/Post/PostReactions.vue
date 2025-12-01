<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { ThumbsUp, ThumbsDown } from "lucide-vue-next"

const props = defineProps<{
  likes: number
  dislikes: number
}>()
</script>

<template>
  <div class="flex items-center gap-4 mt-4">
    <Button variant="outline" class="flex items-center gap-2">
      <ThumbsUp class="h-4 w-4" />
      {{ likes }}
    </Button>

    <Button variant="outline" class="flex items-center gap-2">
      <ThumbsDown class="h-4 w-4" />
      {{ dislikes }}
    </Button>
  </div>
</template>
