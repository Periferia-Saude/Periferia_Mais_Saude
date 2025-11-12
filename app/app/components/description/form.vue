<template>
  <Form @submit.prevent="submit">
    <div class="flex justify-center w-full">
      <ProgressBar :steps="steps" :currentStep="currentStep"/>
    </div>


    <Input 
    label="Nome" 
    v-model="form.name" 
    name="name" 
    type="text" 
    class="tw-mx-6 tw-mb-4"
    placeholder="Unidade..."
    />
    <Input 
    label="Contato" 
    v-model="form.contact" 
    name="contact" 
    type="text" 
    class="tw-mx-6 tw-mb-4" 
    v-mask="'(##) # ####-####'"
    placeholder="(00) 0 0000-0000"
    />

    <Button class="tw-block tw-mx-auto tw-mt-12" type="submit"
    >
    Próximo
    <img 
    src="/assets/img/arrow_right.svg"
    height="20"
    width="20"
    />
    </Button>
  </Form>
</template>

<script setup lang="ts">
import DescriptionService from "~/services/DescriptionService";

const steps = ['Local', 'Descrição', 'Endereço', 'Serviços']; 
const currentStep = 2;

const route = useRoute()
const locationId = route.params.id

const form = ref({ name: '', contact: '' })

async function submit() {
  const id = Number(locationId)
  if (!Number.isInteger(id) || id <= 0) {
    console.error('ID do location inválido ao criar descrição:', locationId)
    return
  }

  const res = await DescriptionService.create(id, form.value)
  console.log('Resposta da API:', res)

    const descriptionId = res?.id ?? res?.location_id ?? res?.description_id
  if (descriptionId) {
      navigateTo(`/location/${id}/description/${descriptionId}/address/create`)
  } else {
    console.warn('Descrição criada, mas nenhum id retornado:', res)
  }
}
</script>
