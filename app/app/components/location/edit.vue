<template>
  <div v-if="editable" class="tw-py-6">
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-6">
      <SecondaryButton @click="saveChanges" class="tw-bg-green-600 tw-text-white">
        Salvar Alterações
      </SecondaryButton>
    </div>

    <!-- Foto -->
    <div class="tw-flex tw-justify-center tw-mb-6" style="display: grid; grid-auto-rows: auto">
      <NuxtImg
        v-if="editable.photo"
        :src="editable.photo.startsWith('http') ? editable.photo : `http://localhost:8000/storage/${editable.photo}`"
        alt="Foto do local"
        class="location-image tw-shadow-md tw-mb-4"
      />
      <input type="file" @change="onPhotoChange" class="tw-mx-auto tw-mt-2" />
      <input
        v-model="editable.description.name"
        class="input tw-text-center"
        placeholder="Nome do local"
      />
    </div>

    <AppContentCard class="tw-mb-32">
      <!-- Endereço -->
      <div>
        <div class="title-container">
          <div class="title-icon tw-rounded-full">
            <img src="/assets/img/location.svg" width="35" height="35" />
          </div>
          <h2>Endereço</h2>
        </div>

        <div class="tw-grid tw-gap-2 tw-mt-2">
          <input v-model="editable.description.address.street" placeholder="Rua" class="input" />
          <input v-model="editable.description.address.number" placeholder="Número" class="input" />
          <input v-model="editable.description.address.district" placeholder="Bairro" class="input" />
          <input v-model="editable.description.address.cep" placeholder="CEP" class="input" />
          <input v-model="editable.description.address.reference" placeholder="Referência" class="input" />
        </div>
      </div>

      <Separator class="tw-my-4" />

      <!-- Contato -->
      <div>
        <div class="title-container">
          <div class="title-icon tw-rounded-full">
            <img src="/assets/img/telephone.svg" width="35" height="35" />
          </div>
          <h2>Informações para Contato</h2>
        </div>
        <input v-model="editable.description.contact" placeholder="Contato" class="input tw-mt-2" />
      </div>

      <Separator class="tw-my-4" />

      <!-- Serviços -->
      <div>
        <div class="title-container">
          <div class="title-icon tw-rounded-full">
            <img src="/assets/img/service.svg" width="35" height="35" />
          </div>
          <h2>Serviços disponíveis</h2>
        </div>

        <div v-for="(service, index) in editable.services" :key="index" class="tw-mb-2">
          <input v-model="service.name" class="input" />
        </div>
      </div>
    </AppContentCard>
  </div>

  <div v-else class="tw-text-center tw-text-gray-600 tw-mt-10">Carregando dados...</div>
</template>

<script setup lang="ts">
import LocationService from '~/services/LocationService';
import { useRoute } from 'vue-router';

const route = useRoute();
const id = Number(route.params.id);
const editable = ref<any>(null);

onMounted(async () => {
  const locationData = await LocationService.getLocationDetails(id);
  editable.value = JSON.parse(JSON.stringify(locationData));
});

const onPhotoChange = (e: Event) => {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file) editable.value.photo = file;
};

const saveChanges = async () => {
  try {
    const formData = new FormData();
    for (const key in editable.value) {
      if (key === 'photo' && editable.value.photo instanceof File) {
        formData.append('photo', editable.value.photo);
      } else {
        formData.append(key, JSON.stringify(editable.value[key]));
      }
    }

    await LocationService.updateLocation(id);
    alert('Alterações salvas com sucesso!');
    navigateTo(`/location/${id}`); 
  } catch (error) {
    console.error('Erro ao salvar alterações:', error);
    alert('Erro ao salvar');
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
