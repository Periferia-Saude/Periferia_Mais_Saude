<template>
  <LayoutHeader>
    <p>Detalhes do local</p>
  </LayoutHeader>
    <LocationDetail />
</template>

<script setup lang="ts">
const route = useRoute();
const id = route.params.id;
</script>