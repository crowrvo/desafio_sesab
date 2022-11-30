import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";

const userRouter: RouteRecordRaw[] = [
  { path: "/users", component: () => import("./views/users/index.vue") },
  { path: "/user/create", component: () => import("./views/users/create.vue") },
  { path: "/user/:id/edit", component: () => import("./views/users/edit.vue") },
  {
    path: "/user/:id/details",
    component: () => import("./views/users/details.vue"),
  },
  { path: "/user/create", component: () => import("./views/users/index.vue") },
];

const routes = [...userRouter];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
