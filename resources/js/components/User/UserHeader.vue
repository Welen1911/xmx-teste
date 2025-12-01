<script setup lang="ts">
const props = defineProps<{
  user: {
    first_name: string
    last_name: string
    email: string
    image: string | null
  }
}>()
</script>

<template>
  <div class="flex items-center gap-4 rounded-xl border p-6 border-sidebar-border/70 dark:border-sidebar-border">

    <div class="h-20 w-20 rounded-full overflow-hidden border bg-muted">
      <img
        v-if="user.image"
        :src="user.image"
        class="h-full w-full object-cover"
      />

      <div
        v-else
        class="h-full w-full flex items-center justify-center text-xl text-muted-foreground"
      >
        {{ user.first_name[0] }}{{ user.last_name[0] }}
      </div>
    </div>

    <div>
      <p class="text-2xl font-semibold">
        {{ user.first_name }} {{ user.last_name }}
      </p>
      <p class="text-sm text-muted-foreground">{{ user.email }}</p>
    </div>

  </div>
</template>
