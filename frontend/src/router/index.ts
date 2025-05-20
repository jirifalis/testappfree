import type {RouteRecordRaw} from 'vue-router'
import {createRouter, createWebHistory} from 'vue-router'
import IndexView from '@/views/IndexView.vue'
import UsersView from "@/views/UsersView.vue";
import GroupsView from "@/views/GroupsView.vue";
import UserDetailView from "@/views/UserDetailView.vue";
import GroupDetailView from "@/views/GroupDetailView.vue";
import GroupCreateView from "@/views/GroupCreateView.vue";

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    name: 'Index',
    component: IndexView,
  },
  {
    path: '/users',
    name: 'Users',
    component: UsersView,
  },
  {
    path: '/user/:id',
    name: 'User',
    component: UserDetailView,
  },
  {
    path: '/groups',
    name: 'Groups',
    component: GroupsView,
  },
  {
    path: '/group/:id',
    name: 'Group',
    component: GroupDetailView,
  },
  {
    path: '/group/create',
    name: 'CreateGroup',
    component: GroupCreateView,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
