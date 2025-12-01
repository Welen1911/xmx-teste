<script setup lang="ts">
import { ThumbsUp } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';

const props = defineProps<{
  comments: {
    id: number;
    body: string;
    likes: number;
    user: {
      first_name: string;
    };
  }[];
}>();
</script>

<template>
  <div
    class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6 mt-6"
  >
    <h2 class="text-xl font-semibold mb-4">Comentários ({{ comments.length }})</h2>

    <div class="space-y-4">
      <div
        v-for="comment in comments"
        :key="comment.id"
        class="rounded-lg border p-4 border-muted"
      >
        <p class="font-medium">
          {{ comment.user.first_name }}
        </p>

        <p class="text-sm text-muted-foreground leading-relaxed">
          {{ comment.body }}
        </p>

        <p class="text-xs text-muted-foreground mt-1">
          <Button variant="outline" class="flex items-center gap-2">
            <ThumbsUp class="h-4 w-4" />
            {{ comment.likes }}
          </Button>
        </p>
      </div>
    </div>
  </div>
</template>
