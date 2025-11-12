<template>
  <div v-if="location">
    <!-- Botões apenas no modo visualização -->
    <div v-if="isAdmin" class="tw-flex tw-justify-end tw-gap-4 tw-mt-6">
      <SecondaryButton
        @click="editLocation(location.id)"
        class="tw-bg-yellow-500 tw-text-white"
      >
        Editar
      </SecondaryButton>

      <SecondaryButton @click="confirmDelete(location.id)" class="tw-bg-red-500 tw-text-white">
        Excluir
      </SecondaryButton>
    </div>

      <!-- Foto -->
      <div v-if="location.photo" class="tw-flex tw-justify-center" style=" display: grid; grid-auto-rows: auto">
        <NuxtImg
          :src="location.photo.startsWith('http') 
          ? location.photo 
          : `http://localhost:8000/storage/${location.photo}`"
          alt="Foto do local"
          class="location-image tw-shadow-md tw-mb-4"
        />
        <div>
          <h1 class="tw-text-center">
        {{ location.description?.name }}
      </h1>
        </div>
      </div>
      <AppContentCard class="tw-mb-32">
           <!-- Endereço -->
      <div v-if="location.description?.address">
        <div class="title-container">
          <div class="title-icon tw-rounded-full">
            <img
            src="/assets/img/location.svg"
            width="35"
            height="35"
          />
          </div>
          <h2>Endereço</h2>
        </div>
        <p>
          {{ location.description.address.street }}, nº {{ location.description.address.number }},
          {{ location.description.address.district }} - CEP: {{ location.description.address.cep }}
        </p>
        <p>Ponto de referência: {{ location.description.address.reference }}</p>
      </div> 

        <Separator class="tw-my-4"/>

        <!-- Descrição -->
      <div>
        <div class="title-container">
          <div class="title-icon tw-rounded-full">
            <img
            src="/assets/img/telephone.svg"
            width="35"
            height="35"
          />
          </div>
        <h2>Informações para Contato</h2>
        </div>
        <p>Fone:  {{ location.description?.contact }}</p>
      </div>

      <Separator class="tw-my-4"/>

      <!-- Serviços -->
      <div v-if="location.services?.length">
        <div class="title-container">
          <div class="title-icon tw-rounded-full">
            <img
            src="/assets/img/service.svg"
            width="35"
            height="35"
          />
          </div>
          <h2 class="tw-font-semibold tw-mt-4">Serviços disponíveis</h2>
        </div>
        <ul class="tw-list-disc tw-list-inside">
          <li v-for="service in location.services" :key="service.id">{{ service.name }}</li>
        </ul>
      </div>

      <!-- Campanhas -->
      <!-- <div v-if="location.campaigns?.length">
        <h3 class="tw-font-semibold tw-mt-4">Campanhas</h3>
        <ul class="tw-list-disc tw-list-inside">
          <li v-for="camp in location.campaigns" :key="camp.id">
            <strong>{{ camp.name }}</strong> — {{ camp.description }}
            <br />
            <small>Início: {{ camp.start_time }} | Fim: {{ camp.end_time }}</small>
          </li>
        </ul>
      </div> -->
    </AppContentCard>
  </div>
    
    <div v-else class="tw-text-center tw-text-gray-600">
      Carregando informações...
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router';
import LocationService from '~/services/LocationService';
const { $userStore } = useNuxtApp();

const props = defineProps<{
  id: number;
}>();

const isAdmin = computed(() => $userStore.is_admin);
const route = useRoute();
const location = ref<any>(null);


onMounted(async () => {
  location.value = await LocationService.getLocationDetails(Number(props.id || route.params.id));
});

const editLocation = async (id: number) => {
      await LocationService.updateLocation(id);
      navigateTo(`/location/${id}/edit`);
  };

const confirmDelete = async (id: number) => {
  if (confirm('Tem certeza que deseja excluir este local?')) {
    try {
      await LocationService.deleteLocation(id);
      navigateTo('/location/show');
    } catch (error) {
      console.error('Erro ao excluir local:', error);
      alert('Erro ao excluir local');
    }
  }
};

</script>

<style lang="scss" scoped>
.location-image {
  min-width: 100vw;
  max-width: 100vw;
  min-height: 30vh;
  max-height: 30vh;
}
.title-icon {
  background-color: #003087;
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.title-container {
  display: flex;
  flex-flow: row;
  gap: 1em;
  align-content: center;
  align-items: center;
}
.input {
  @apply tw-border tw-rounded tw-p-2 tw-w-full tw-mt-2;
}
</style>
