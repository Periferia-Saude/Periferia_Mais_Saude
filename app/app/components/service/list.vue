<template>
  <Form>    
        <Button
          @click="router.push('/service/create')"
          class="tw-mx-auto"
          >
          Adicionar novo serviço
        </Button>

        <h2>Serviços Cadastrados</h2>

      <ul v-if="services.length" class="tw-space-y-2">
        <li
          v-for="service in services"
          :key="service.id"
          class="tw-bg-white tw-border tw-rounded-xl tw-p-3 tw-shadow-sm hover:tw-bg-blue-50 tw-transition"
        >
          {{ service.name }}

        <!-- <Button @click="confirmDelete(service.id)" class="tw-bg-red-500 tw-text-white">
          Excluir
        </Button> -->
  
        </li>
      </ul>

      <p v-else class="tw-text-center tw-text-gray-500 tw-italic">
        Nenhum serviço cadastrado ainda.
      </p>
  </Form>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import ServiceService from '~/services/service.service';
import { useRoute, useRouter } from 'vue-router'

const router = useRouter();
const route = useRoute();
const id = route.params.id

const services = ref<Service[]>([])

interface Service {
  id: number
  name: string
}

onMounted(async () => {
  try {
    const res = await ServiceService.getAll()
    services.value = res || []
  } catch (e) {
    console.error('Erro ao buscar serviços:', e)
  }
})

const confirmDelete = async (id: number) => { 
  if (confirm("Tem certeza que deseja excluir este Serviço?")) {
    try {
      await ServiceService.deleteService(id);
      navigateTo("/service/show");
    } catch (error) {
      console.error("Erro ao excluir serviço:", error);
      alert("Erro ao excluir serviço");
  }
}
};

</script>

<style scoped lang="scss">
ul {
  padding: 0;
}
li {
  list-style: none;
}
</style>
