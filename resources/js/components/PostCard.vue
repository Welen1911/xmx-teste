<script setup lang="ts">
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Link } from "@inertiajs/vue3";
import { ThumbsUp, ThumbsDown, MessageCircle } from "lucide-vue-next";
import type { Post } from "@/types";
import { computed } from "vue";

const props = defineProps<{ post: Post }>();

</script>

<template>
  <Card
    class="overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
  >
    <CardHeader>
      <CardTitle class="text-lg font-semibold">
        <Link :href="`/posts/${post.id}`" class="hover:underline">
          {{ post.title }}
        </Link>
      </CardTitle>

      <!-- Tags -->
      <div v-if="post.tags && post.tags.length" class="flex flex-wrap gap-2 mt-2">
        <span
          v-for="tag in post.tags"
          :key="tag.id"
          class="px-2 py-1 text-xs rounded-full bg-muted text-muted-foreground"
        >
          {{ tag.name }}
        </span>
      </div>
    </CardHeader>

    <CardContent>

      <!-- Botões de interação -->
      <div class="flex items-center gap-4">
        <button class="flex items-center gap-1 text-sm hover:text-primary">
          <ThumbsUp class="w-4 h-4" />
          {{ post.likes ?? 0 }}
        </button>

        <button class="flex items-center gap-1 text-sm hover:text-primary">
          <ThumbsDown class="w-4 h-4" />
          {{ post.dislikes ?? 0 }}
        </button>

        <button class="flex items-center gap-1 text-sm hover:text-primary">
          <MessageCircle class="w-4 h-4" />
          {{ post.comments_count ?? 0 }}
        </button>
      </div>
    </CardContent>
  </Card>
</template>
