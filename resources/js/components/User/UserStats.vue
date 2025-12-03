<script setup lang="ts">
import { User } from "@/types";

const props = defineProps<{
  user: User;
}>();
</script>

<template>
  <div class="grid grid-cols-2 gap-4">
    <div class="rounded-xl border p-4 text-center dark:border-sidebar-border">
      <p class="text-2xl font-semibold">{{ props.user.posts_count }}</p>
      <p class="text-muted-foreground text-sm">Posts</p>
      <a :href="`/user/${props.user.id}/posts`" class="block"> Ver todos os posts </a>
    </div>

    <div class="rounded-xl border p-4 text-center dark:border-sidebar-border">
      <p class="text-2xl font-semibold">{{ props.user.comments_count }}</p>
      <p class="text-muted-foreground text-sm">Comentários</p>
    </div>
  </div>
</template>
