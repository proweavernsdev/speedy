import { createRouter, createWebHistory } from "@ionic/vue-router";
import { RouteRecordRaw } from "vue-router";
import HomePage from "../views/HomePage.vue";

const routes: Array<RouteRecordRaw> = [
  {
    path: "/",
    redirect: "/home",
  },
  {
    path: "/home",
    name: "Home",
    component: HomePage,
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/components/LoginComponent.vue"),
  },
  {
    path: "/register",
    name: "Register",
    component: () => import("../views/components/RegisterComponent.vue"),
  },
  {
    path: "/forgotPassword",
    name: "ForgotPassword",
    component: () => import("../views/components/ForgotPasswordComponent.vue"),
  },
  {
    path: "/resetPassword",
    name: "ResetPassword",
    component: () => import("../views/components/ResetPasswordComponent.vue"),
  },
  // customer routes
  {
    path: "/customer_dashboard",
    name: "Customer Dashboard",
    component: () =>
      import("../views/components/customer/customer_dashboard.vue"),
  },

  {
    path: "/customer_account",
    name: "Customer Account",
    component: () =>
      import("../views/components/customer/account_management.vue"),
  },

  {
    path: "/customer_deliveries",
    name: "Customer Deliveries",
    component: () =>
      import("../views/components/customer/customer_deliveries.vue"),
  },

  {
    path: "/customer_history",
    name: "Customer History",
    component: () =>
      import("../views/components/customer/customer_history.vue"),
  },

  {
    path: "/customer_others-settings",
    name: "Customer Settings",
    component: () => import("../views/components/customer/others_settings.vue"),
  },

  // driver routes
  {
    path: "/driver_dashboard",
    name: "Driver Dashboard",
    component: () => import("../views/components/driver/driver_dashboard.vue"),
  },

  {
    path: "/driver_account",
    name: "Driver Account",
    component: () =>
      import("../views/components/driver/account_management.vue"),
  },

  {
    path: "/uploads",
    name: "Upload Document",
    component: () => import("../views/components/driver/upload_documents.vue"),
  },
  {
    path: "/test",
    name: "Test",
    component: () => import("../views/components/TestPage.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
