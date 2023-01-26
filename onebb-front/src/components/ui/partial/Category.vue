<script setup lang="ts">
import { ICategory } from "@/interfaces/obbApiInterface";
import { defineProps } from "vue";

const props = defineProps<{
  category: ICategory;
}>();
</script>

<template>
  <div :key="category.id" class="box">
    <div class="box-header">
      <h2>
        <router-link
          :to="{
            name: 'Category',
            params: { slug: category.slug, id: category.id },
          }"
          >{{ category.name }}
        </router-link>
      </h2>
    </div>
    <div class="box-content">
      <ul class="list">
        <li v-for="board in category.boards" :key="board.id" class="list-item">
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
              >{{ board.plots_no }} <i class="fas fa-comments"></i> /
              {{ board.posts_no }} <i class="fas fa-comment-dots"></i
            ></strong>
          </div>
          <div class="autor col-sm-3">
            <div class="px-xl-1 m-0">
              <div v-if="board.last_active_user" class="d-sm-none">
                {{ board.last_active_user.username }}
              </div>
              <div class="text-center">2022-12-03</div>
            </div>
            <img
              src="https://onebb.mazda5.pl/upload/img/55fef6c86f3b7e1a8da813d6384d8755.png"
              alt="Avatar"
              class="avatar"
            />
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
