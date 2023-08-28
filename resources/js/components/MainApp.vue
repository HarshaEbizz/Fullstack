 <script setup>
import { useUserStore } from "../store/user";

import Navbar from "./includes/Navbar.vue";
import Sidebar from "./includes/Sidebar.vue";

const userStore = useUserStore();

</script>
<template>
  <!-- <router-view /> -->
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <Navbar v-if="userStore.isAuthenticated"/>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <Sidebar v-if="userStore.isAuthenticated" />
      <!-- partial -->

      <router-view />
      <!-- main-panel ends -->
    </div>
  </div>
</template>

