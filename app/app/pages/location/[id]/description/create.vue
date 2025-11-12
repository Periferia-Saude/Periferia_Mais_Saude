<template>
  <LayoutHeader>
      <p class="tw-text-center">
      <!-- {{ isEditing ? "Editar local" : "Adicionar Local" }} -->
      Adicionar Local
      </p>
      <div class="tw-ml-auto">
        <button
            @click="showPopup = true"
            type="button"
            class="tw-bg-blue-700 tw-text-white tw-rounded-full tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-font-bold tw-shadow-md hover:tw-bg-blue-500 tw-transition"
          >
            ?
      </button>
     </div>
    </LayoutHeader>

    <DescriptionForm />

    <!-- Popup de ajuda -->
    <Popup :isVisible="showPopup" @close="showPopup = false">
      <div>
        <h2 class="tw-text-lg tw-font-bold tw-text-center tw-mb-2">Como preencher os campos?</h2>
        <ol class="tw-list-decimal tw-list-inside tw-space-y-2">
          <li><strong>Nome:</strong> Coloque o nome detalhado do local.</li>
          <li><strong>Contato:</strong> Número telefonico do local com DDD, apenas o número.</li>
          <li><strong>Próximo:</strong> Clique em “Próximo” para continuar o cadastro.</li>
        </ol>
      </div>
    </Popup>

</template>

<script setup lang="ts">

definePageMeta({
  middleware: ["auth"],
});

const showPopup = ref(false);
</script>