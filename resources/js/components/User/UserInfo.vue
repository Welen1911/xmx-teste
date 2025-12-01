<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  user: {
    external_id: string
    phone: string | null
    birth_date: string | null
    address: string | null
  }
}>()

const user = props.user

const formattedBirthDate = computed(() => {
  if (!user.birth_date) return null
  try {
    const d = new Date(user.birth_date)
    if (isNaN(d.getTime())) return null
    return d.toLocaleDateString('pt-BR')
  } catch {
    return null
  }
})

function tryParseJsonMaybeString(value: any) {
  if (!value) return null

  if (typeof value === 'object') return value

  if (typeof value === 'string') {
    const trimmed = value.trim()

    if ((trimmed.startsWith('{') && trimmed.endsWith('}')) ||
        (trimmed.startsWith('[') && trimmed.endsWith(']'))) {
      try {
        return JSON.parse(trimmed)
      } catch {
        try {
          const replaced = trimmed.replace(/'/g, '"')
          return JSON.parse(replaced)
        } catch {
          return null
        }
      }
    }
  }

  return null
}

const formattedAddress = computed(() => {
  if (!user.address) return null

  const addrObj = tryParseJsonMaybeString(user.address)

  if (!addrObj) {
    return user.address
  }

  const street = addrObj.address ?? addrObj.street ?? ''
  const city = addrObj.city ?? ''
  const state = addrObj.stateCode ?? addrObj.state ?? ''
  const postal = addrObj.postalCode ?? addrObj.zip ?? ''
  const country = addrObj.country ?? ''

  const parts: string[] = []
  if (street) parts.push(street)
  const cityState = [city, state].filter(Boolean).join(' - ')
  if (cityState) parts.push(cityState)
  if (postal) parts.push(postal)
  if (country) parts.push(country)

  let out = parts.join(', ')

  const coords = addrObj.coordinates ?? addrObj.location ?? null
  if (coords && (coords.lat || coords.lng)) {
    const lat = coords.lat ?? coords.latitude ?? null
    const lng = coords.lng ?? coords.longitude ?? coords.lng ?? null
    if (lat !== null && lng !== null) {
      out += ` (lat: ${lat}, lng: ${lng})`
    }
  }

  return out || user.address
})
</script>

<template>
  <div class="rounded-xl border p-6 dark:border-sidebar-border">
    <h2 class="text-xl font-semibold mb-4">Informações do Autor</h2>

    <div class="grid md:grid-cols-2 gap-4">

      <div>
        <p class="text-sm text-muted-foreground">ID Externo</p>
        <p class="font-medium">{{ user.external_id }}</p>
      </div>

      <div v-if="user.phone">
        <p class="text-sm text-muted-foreground">Telefone</p>
        <p class="font-medium">{{ user.phone }}</p>
      </div>

      <div v-if="formattedBirthDate">
        <p class="text-sm text-muted-foreground">Data de Nascimento</p>
        <p class="font-medium">{{ formattedBirthDate }}</p>
      </div>

      <div v-if="formattedAddress">
        <p class="text-sm text-muted-foreground">Endereço</p>
        <p class="font-medium break-words">{{ formattedAddress }}</p>
      </div>

    </div>
  </div>
</template>
