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

  // driver routes
  {
    path: "/driver_dashboard",
    name: "Driver Dashboard",
    component: () => import("../views/components/driver/driver_dashboard.vue"),
  },

  // company routes
  {
    path: "/company_dashboard",
    name: "Company Dashboard",
    component: () =>
      import("../views/components/company/company_dashboard.vue"),
  },

  // admin routes
  {
    path: "/admin_dashboard",
    name: "Admin Dashboard",
    component: () => import("../views/components/admin/admin_dashboard.vue"),
  },

  // super admin routes
  {
    path: "/super_admin_dashboard",
    name: "Super Admin Dashboard",
    component: () =>
      import("../views/components/super_admin/super_admin_dashboard.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
