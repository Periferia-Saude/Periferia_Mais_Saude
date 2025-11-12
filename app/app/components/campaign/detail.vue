<template>
  <AppContentCard tw-mb-2>
    <div v-if="loading" class="tw-text-center tw-py-4">
      Carregando detalhes da campanha...
    </div>
    
    <div v-else-if="!campaign" class="tw-text-center tw-py-4">
      Campanha não encontrada.
    </div>

    <div v-else>
      <h1 class="tw-text-center tw-mb-8">{{ campaign.name }}</h1>
      <div class="tw-mb-4">
        <h2>Período:</h2>
        <p>
          {{ formatDate(campaign.startTime) }} até
          {{ formatDate(campaign.endTime) }}
        </p>
      </div>

      <div class="tw-mb-4">
        <h2>Descrição:</h2>
        <p class="tw-whitespace-pre-line">{{ campaign.description }}</p>
      </div>
      <!--
        <div class="tw-mb-4">
        <h3 class="tw-text-lg tw-font-semibold">Locais</h3>
        <ul class="tw-pl-4">
          <li
            v-for="location in campaign.locations"
            :key="location.id"
            class="tw-mb-2"
          >
            <strong>{{
              location.description
                ? location.description.name
                : "Local sem nome"
            }}</strong>
            <p v-if="location.description">
              {{ location.description.contact }}
            </p>
          </li>
        </ul>
      </div>
      -->

      <div v-if="isAdmin" class="tw-flex tw-justify-end tw-gap-4 tw-mt-6">
        <SecondaryButton
          @click="() => { if (campaign) router.push(`/campaigns/${campaign.id}/edit`) }"
          class="tw-bg-yellow-500 tw-text-white"
        >
          Editar
        </SecondaryButton>

        <SecondaryButton @click="confirmDelete" class="tw-bg-red-500 tw-text-white">
          Excluir
        </SecondaryButton>
      </div>
    </div>
  </AppContentCard>
</template>

<script setup lang="ts">
import { CampaignService } from "~/services/";
import { useUserStore } from "~/stores";
import type { Campaign } from "~/types/";
import { formatDate } from "~/utils/";

const props = defineProps<{
  id: number;
}>();

const userStore = useUserStore();
const router = useRouter();
const campaign = ref<Campaign | null>(null);
const loading = ref(true);

const isAdmin = userStore.is_admin;

// verificar se esta logado
const isLoggedIn = userStore.isLoggedIn;


onMounted(async () => {
  try {
    campaign.value = await CampaignService.getCampaignById(props.id);
    // debug: log auth state
    console.log('userStore.data', userStore.data);
    console.log('userStore.isLoggedIn', userStore.isLoggedIn);
  } catch (error) {
    console.error(`Erro ao carregar campanha ${props.id}:`, error);
  } finally {
    loading.value = false;
  }
});

const confirmDelete = async () => {
  if (confirm("Tem certeza que deseja excluir esta campanha?")) {
    try {
  await CampaignService.deleteCampaign(props.id);
  router.push("/campaigns");
    } catch (error) {
      console.error("Erro ao excluir campanha:", error);
      alert("Erro ao excluir campanha");
    }
  }
};
</script>
