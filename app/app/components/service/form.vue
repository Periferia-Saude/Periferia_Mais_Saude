<template>
<Form @submit.prevent="submit">
  <p>Cadastre um novo serviço oferecido por uma das Unidades SUS Cadastradas!</p>

    <Input label="Nome do serviço:" v-model="form.name" name="name" type="text" placeholder="Digite o nome do serviço" class="tw-mx-6 tw-mb-4" />

    <Button class="tw-block tw-mx-auto tw-mt-8" type="submit"> {{ isEditing ? "Atualizar" : "Cadastrar" }}</Button>

    

</Form>
</template>

<script setup lang="ts">
import ServiceService from '~/services/service.service' 
import { toast } from 'vue3-toastify'

const props = defineProps({
  serviceId: {
    type: Number,
    required: false,
  },
});

const isEditing = computed(() => !!props.serviceId);

const form = ref({
  name: '',
})


if (isEditing.value) {
  try {
    const service = await ServiceService.getServiceById(
      Number(props.serviceId)
    );
    if (service) {
      form.value.name = service.name;
    }
  } catch (error) {
    console.error("Erro ao carregar serviço para edição:", error);
  }
}

async function submit() {
  if (!form.value.name) {
    console.warn('Nome do serviço é obrigatório')
    return
  }

  try {
    const res = await ServiceService.create(form.value)
    if (res) {
      toast.success("Serviço cadastrado com sucesso!")
      // navigateTo("/service/show")
       setTimeout(() => {
        navigateTo("/service/show")
      }, 2000)
    }
  } catch (e) {
    toast.error("Erro ao cadastrar serviço!")
    // console.error("Erro ao cadastrar serviço:", e)
  }
}
</script>

<style scoped lang="scss">

</style>