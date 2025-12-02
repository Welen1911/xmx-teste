<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const props = defineProps<{
  filters: {
    search?: string
    tag?: string
    likes?: string
  },
  tags: string[]
}>()

const emit = defineEmits(['update'])

const form = useForm({
  search: props.filters.search ?? '',
  tag: props.filters.tag ?? '',
  likes: props.filters.likes ?? '',
})

function updateFilters() {
  emit('update', { ...form })
}
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <!-- 🔎 Busca -->
    <input
      v-model="form.search"
      @input="updateFilters"
      type="text"
      placeholder="Buscar por título..."
      class="w-full rounded-lg border px-3 py-2"
    />

    <!-- 🏷 Tag -->
    <select
      v-model="form.tag"
      @change="updateFilters"
      class="w-full rounded-lg border px-3 py-2"
    >
      <option value="">Todas as tags</option>
      <option v-for="tag in props.tags" :key="tag" :value="tag">
        {{ tag }}
      </option>
    </select>

    <!-- 👍 Likes -->
    <select
      v-model="form.likes"
      @change="updateFilters"
      class="w-full rounded-lg border px-3 py-2"
    >
      <option value="">Ordenar por Likes</option>
      <option value="asc">Menos → Mais curtidos</option>
      <option value="desc">Mais → Menos curtidos</option>
    </select>

  </div>
</template>
