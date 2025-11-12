<template>
  <BackButton class="tw-m-8" cor="aliceblue"/>
  <Form @submit.prevent="submit">
    <Logo />

    <Input
      label="E-mail"
      type="email"
      v-model="user.email"
      name="user.email"
      placeholder="Digite seu e-mail..."
      class="tw-mb-5 tw-mx-6"
      :error="logErrors.email"
    >
      <ErrorMessage message-error="E-mail inválido" />
    </Input>

    <Input
      label="Senha"
      type="password"
      v-model="user.password"
      name="user.password"
      placeholder="Digite sua senha..."
      class="tw-mb-14 tw-mx-6"
      :error="logErrors.password"
    >
      <ErrorMessage message-error="Senha inválida" />
    </Input>

    <Button type="submit" class="tw-block tw-mx-auto tw-mt-5"> Login </Button>

    <div class="tw-text-center tw-mt-5 ">
    <p class="tw-mb-0">Ainda não tem uma conta?</p>
    <NuxtLink to="/account/register" >
      Cadastre-se
    </NuxtLink>
    </div>

  </Form>
</template>

<script setup lang="ts">
import { AuthService } from "~/services/";
import { loginSchema } from "~/validations/";
import { toast } from "vue3-toastify";

definePageMeta({
  layout: 'empty',
  name: "login",
});

const user = ref({
  email: "",
  password: "",
});
const logErrors = ref({
  email: false,
  password: false,
});
const { $userStore } = useNuxtApp();

watch(
  user,
  () => {
    validated();
  },
  {
    deep: true,
  }
);

async function submit() {
  if (user.value.email && user.value.password && validated()) {
    const res = await AuthService.login(user.value);

    if (res) {
      await $userStore.fetch();
      toast.success("Entrando!")

       setTimeout(() => {
        navigateTo("/")
      }, 2000)
    }
  }
}
function validated() {
  const result = loginSchema.safeParse(user.value);

  logErrors.value.email = false;
  logErrors.value.password = false;

  if (!result.success) {
    const errors = result.error.issues;
    for (const error of errors) {
      //@ts-ignore
      if (typeof logErrors.value[String(error.path[0])] === "boolean") {
        //@ts-ignore
        logErrors.value[String(error.path[0])] = true;
      }
    }
  }
  return result.success;
}
</script>

<style scoped></style>
