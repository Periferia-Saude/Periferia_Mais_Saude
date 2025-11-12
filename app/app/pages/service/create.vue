<template>
  <LayoutHeader>
      <p class="tw-text-center">
      {{ isEditing ? "Editar Serviço" : "Novo Serviço" }}
      </p>

      <!-- Popup de ajuda -->
      <Popup :isVisible="showPopup" @close="showPopup = false">
        <div>
          <h2 class="tw-text-lg tw-font-bold tw-text-center tw-mb-2">Como preencher os campos?</h2>
          <ol class="tw-list-decimal tw-list-inside tw-space-y-2">
            <li><strong>Encontre o local:</strong> Selecione “CEP” ou “Rua”, ou marque o local manualmente no mapa.</li>
            <li><strong>Foto:</strong> Adicione a imagem do local desejado.</li>
            <li><strong>Próximo:</strong> Clique em “Próximo” para continuar o cadastro.</li>
          </ol>
        </div>
      </Popup>


      
    </LayoutHeader>
    <ServiceForm />

</template>

<script setup lang="ts">
definePageMeta({
  middleware: ["auth"],
});
</script>