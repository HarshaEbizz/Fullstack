 <script setup>
import { useUserStore } from "../../store/user";

const userStore = useUserStore();
</script>
<template>
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-profile">
        <a href="#" class="nav-link">
          <div class="nav-profile-image">
            <img src="assets/images/faces/face1.jpg" alt="profile" />
            <span class="login-status online"></span>
            <!--change to offline or busy as needed-->
          </div>
          <div class="nav-profile-text d-flex flex-column">
            <span class="font-weight-bold mb-2">{{ userStore.user.first_name }}</span>
            <!-- <span class="text-secondary text-small">Project Manager</span> -->
          </div>
          <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
        </a>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/">
          <span class="menu-title">Dashboard</span>
          <i class="mdi mdi-home menu-icon"></i>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/tags">
          <span class="menu-title">Tags</span>
          <i class="mdi mdi-contacts menu-icon"></i>
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/categories">
          <span class="menu-title">Categories</span>
          <i class="mdi mdi-format-list-bulleted menu-icon"></i>
        </router-link>
      </li>
    </ul>
  </nav>
</template>