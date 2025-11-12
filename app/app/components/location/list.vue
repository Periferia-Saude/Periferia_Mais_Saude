<template>
  <Form>    
     <Button
       v-if="$userStore.is_admin"
        @click="router.push('/location/create')"
        class="tw-mx-auto"
        >
            Adicionar Local
     </Button>

      <ul v-if="locations.length" class="tw-space-y-2">
      <li
        v-for="location in locations"
        :key="location.id"
        class="tw-bg-white tw-rounded-lg tw-shadow tw-p-4 tw-mb-3 tw-cursor-pointer hover:tw-bg-gray-100"
        @click="goToLocation(location.id)"
      >
        <div class="tw-flex tw-items-center">
          <img
            v-if="location.photo"
            :src="`http://127.0.0.1:8000/storage/${location.photo}`"
            alt="Foto"
            class="tw-w-16 tw-h-16 tw-rounded-lg tw-mr-4"
          />
          <span>{{ location.description?.name || 'Sem nome' }}</span>
        </div>
      </li>
    </ul>

      <p v-else class="tw-text-center tw-text-gray-500 tw-italic">
        Nenhum local cadastrado ainda.
      </p>
  </Form>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import LocationService from '~/services/LocationService';
import { useRouter } from 'vue-router'

const router = useRouter();

const { $userStore } = useNuxtApp();

interface Location {
  id: number,
    photo: string | null,
    description: {
      name: string
    } | null
}

const locations = ref<Location[]>([])

onMounted(async () => {
  try {
    const res = await LocationService.getAll()
    locations.value = res || []
  } catch (e) {
    console.error('Erro ao buscar locais:', e)
  }
})

const goToLocation = (id: number) => {
  router.push(`/location/${id}`) // redireciona para sua página individual já existente
}

</script>

<style scoped lang="scss">
ul {
  padding: 0;
}
li {
  list-style: none;
}
</style>
