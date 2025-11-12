<template>
    <LayoutHeader>
    <p>Editar do local</p>
  </LayoutHeader>
  
  <LocationEdit />
   
</template>

<script setup lang="ts">
const route = useRoute();
const id = route.params.id;

definePageMeta({
  middleware: ["auth"],
});
</script>