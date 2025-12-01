<script setup lang="ts">
const props = defineProps<{
  user: {
    posts_count: number;
    comments_count: number;
  };
}>();
</script>

<template>
  <div class="grid grid-cols-2 gap-4">
    <div class="rounded-xl border p-4 text-center dark:border-sidebar-border">
      <a :href="`/user/${user.id}/posts`" class="block">
        <p class="text-2xl font-semibold">{{ user.posts_count }}</p>
        <p class="text-muted-foreground text-sm">Posts</p>
      </a>
    </div>

    <div class="rounded-xl border p-4 text-center dark:border-sidebar-border">
      <p class="text-2xl font-semibold">{{ user.comments_count }}</p>
      <p class="text-muted-foreground text-sm">Comentários</p>
    </div>
  </div>
</template>
