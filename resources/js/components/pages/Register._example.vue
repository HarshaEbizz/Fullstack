<script setup>
import { useUserStore } from "../../store/user";
import { useField, useForm } from "vee-validate";

const { handleSubmit, handleReset } = useForm({
  validationSchema: {
    first_name(value) {
      if (value?.length >= 2) return true;
      return "First Name needs to be at least 2 characters.";
    },
    last_name(value) {
      if (value?.length >= 2) return true;
      return "Last Name needs to be at least 2 characters.";
    },
    email(value) {
      if (/^[a-z.-]+@[a-z.-]+\.[a-z]+$/i.test(value)) return true;

      return "Must be a valid e-mail.";
    },
    password(value) {
      if (value?.length >= 8) return true;
      return "Password needs to be at least 8 characters.";
    },
  },
});

const first_name = useField("first_name");
const last_name = useField("last_name");
const email = useField("email");
const password = useField("password");

const userStore = useUserStore();

const submit = handleSubmit((values) => {
  userStore.register(values).then(() => {
    if (userStore.sucess_message) {
      this.$toast.success(userStore.sucess_message, {
        position: "top-right",
        duration: 3000,
      });
      localStorage.setItem("token", userStore.token);
      this.$router.push("/");
    } else if (userStore.error) {
      this.$toast.error(userStore.error, {
        position: "top-right",
        duration: 3000,
      });
    }
  });
});
</script>

<template>
  <div class="content-wrapper d-flex align-items-center auth">
    <div class="row flex-grow">
      <div class="col-lg-4 mx-auto">
        <div class="auth-form-light text-left p-5">
          <div class="brand-logo">
            <h1>Fullstack</h1>
          </div>
          <h4>New here?</h4>
          <h6 class="font-weight-light">
            Signing up is easy. It only takes a few steps
          </h6>
          <form @submit.prevent="submit">
            <v-text-field
              v-model="first_name.value.value"
              :counter="10"
              :error-messages="first_name.errorMessage.value"
              label="First Name"
            ></v-text-field>

            <v-text-field
              v-model="last_name.value.value"
              :counter="10"
              :error-messages="last_name.errorMessage.value"
              label="Last Name"
            ></v-text-field>

            <v-text-field
              v-model="email.value.value"
              :error-messages="email.errorMessage.value"
              label="Email"
            ></v-text-field>

            <v-text-field
              v-model="password.value.value"
              :counter="10"
              :error-messages="password.errorMessage.value"
              label="Password"
            ></v-text-field>

            <v-btn class="me-4" type="submit"> submit </v-btn>

            <v-btn @click="handleReset"> clear </v-btn>
          </form>
        </div>
          <div class="text-center mt-4 font-weight-light">
            Already have an account?
            <router-link to="login" class="text-primary">Login</router-link>
          </div>
      </div>
    </div>
  </div>
</template >