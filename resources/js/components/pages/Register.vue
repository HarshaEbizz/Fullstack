<script setup>
import { useUserStore } from "../../store/user";
import { Form, Field, ErrorMessage } from "vee-validate";
import * as Yup from "yup";
import { useRouter } from "vue-router";
import { createToaster } from "@meforma/vue-toaster";

const userStore = useUserStore();
const router = useRouter();
const toaster = createToaster({
  /* options */
});
const schema = Yup.object().shape({
  first_name: Yup.string().required().label("First Name"),
  last_name: Yup.string().required().label("Last Name"),
  email: Yup.string().email().required().label("Email"),
  password: Yup.string().min(8).required().label("Password"),
});
function onSubmit(values) {
  userStore.register(values).then(() => {
    console.log(userStore);
    console.log(userStore.token);
    if (userStore.sucess_message) {
      toaster.success(userStore.sucess_message, {
        position: "top-right",
        duration: 3000,
      });
      localStorage.setItem("token",userStore.token);
      router.push("/");
    } else if (userStore.error) {
      toaster.error(userStore.error, {
        position: "top-right",
        duration: 3000,
      });
    }
  });
}
</script>

<template>
  <div class="content-wrapper d-flex align-items-center auth">
    <div class="row flex-grow">
      <div class="col-lg-4 mx-auto">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Fullstack</h4>
            <p class="card-description">
              Signing up is easy. It only takes a few steps
            </p>
            <Form @submit="onSubmit" :validation-schema="schema">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <Field
                  type="text"
                  class="form-control"
                  id="first_name"
                  name="first_name"
                  placeholder="First Name"
                />
                <ErrorMessage class="form-required" name="first_name" />
              </div>
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <Field
                  type="text"
                  class="form-control"
                  id="last_name"
                  name="last_name"
                  placeholder="Last Name"
                />
                <ErrorMessage class="form-required" name="last_name" />
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <Field
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  placeholder="Email"
                />
                <ErrorMessage class="form-required" name="email" />
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <Field
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  placeholder="Password"
                />
                <ErrorMessage class="form-required" name="password" />
              </div>
              <button type="submit" class="btn btn-gradient-primary me-2">
                Submit
              </button>
            </Form>
             <div class="text-center mt-4 font-weight-light">
            Already have an account?
            <router-link to="login" class="text-primary">Login</router-link>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
