<script setup lang="ts">
import { ICategory } from "@/interfaces/obbApiInterface";
import { defineProps } from "vue";
import { parseAvatar, parseUsername, parseDate } from '@/services/helpers/parsers';

const props = defineProps<{
  category: ICategory;
}>();

</script>

<template>
  <div v-if="category" :key="props.category.id" class="box">
    <div class="box-header">
      <h2>
        <router-link
          :to="{
            name: 'Category',
            params: { slug: props.category.slug, id: props.category.id },
          }"
          >{{ category.name }}
        </router-link>
      </h2>
    </div>
    <div class="box-content">
      <ul class="list">
        <li v-for="board in props.category.boards" :key="board.id" class="list-item">
          <div class="content f-grow">
            <h3>
              <router-link
                :to="{
                  name: 'Board',
                  params: { slug: board.slug, id: board.id },
                }"
                >
                {{ board.name }}
              </router-link>
            </h3>
            <p v-html="board.description"></p>
          </div>
          <div class="row col-2 text-right d-sm-none">
            <strong
              >{{ board.plots_no ?? '0'}} <i class="fas fa-comments"></i> /
              {{ board.posts_no ?? '0' }} <i class="fas fa-comment-dots"></i
            ></strong>
          </div>
          <div v-if="board.last_active_user" class="autor col-sm-3">
            <div class="px-xl-1 m-0">
              <div class="d-sm-none" v-html="parseUsername(board.last_active_user)">
              </div>
              <div class="text-center">{{ parseDate(board.updated_at)}}</div>
            </div>
            <img
              
              :src="parseAvatar(board.last_active_user.avatar ?? null)"
              alt="Avatar"
              class="avatar"
            />
          </div>
          <div v-else class="autor col-sm-3"></div>

        </li>
      </ul>
    </div>
  </div>
</template>
