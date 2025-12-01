<script setup lang="ts">
const props = defineProps<{
  user: {
    first_name: string;
    last_name: string;
    image?: string | null;
  };
}>();
</script>

<template>
  <div class="flex items-center gap-4 mb-6">
    <div class="h-14 w-14 rounded-full overflow-hidden border">
      <a :href="`/user/${user.id}`">
        <img
          v-if="user.image"
          :src="user.image"
          class="h-full w-full object-cover"
          alt="Foto do autor"
        />

        <div
          v-else
          class="h-full w-full flex items-center justify-center bg-muted text-muted-foreground"
        >
          {{ user.first_name[0] }}{{ user.last_name[0] }}
        </div>
      </a>
    </div>

    <div>
      <a :href="`/user/${user.id}`">
        <p class="text-lg font-semibold">{{ user.first_name }} {{ user.last_name }}</p>
        <p class="text-sm text-muted-foreground">Autor do post</p>
      </a>
    </div>
  </div>
</template>
