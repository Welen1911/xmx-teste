<script setup lang="ts">
import { User } from '@/types';

const props = defineProps<{
  user: User;
}>()
</script>

<template>
  <div class="flex items-center gap-4 rounded-xl border p-6 border-sidebar-border/70 dark:border-sidebar-border">

    <div class="h-20 w-20 rounded-full overflow-hidden border bg-muted">
      <img
        v-if="props.user.image"
        :src="props.user.image"
        class="h-full w-full object-cover"
      />

      <div
        v-else
        class="h-full w-full flex items-center justify-center text-xl text-muted-foreground"
      >
        {{ props.user.first_name[0] }}{{ props.user.last_name[0] }}
      </div>
    </div>

    <div>
      <p class="text-2xl font-semibold">
        {{ props.user.first_name }} {{ props.user.last_name }}
      </p>
      <p class="text-sm text-muted-foreground">{{ props.user.email }}</p>
    </div>

  </div>
</template>
