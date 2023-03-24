<script setup lang="ts">
import { ref } from "vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import api from "@/services/api/api";
import { IPlot, IPost } from "@/interfaces/obbApiInterface";
import { PLOT } from "@/services/api/obbResources";
import { parseUsername, parseDate, currentPage, usePaginator } from "@/services/helpers/parsers";
import Paginator from "@/components/ui/elements/Paginator.vue";
import JoditEditor from "@/components/ui/partial/JoditEditor.vue";

const { t } = useI18n();
const store = useStore();
const route = useRoute();
const router = useRouter();
const id: number = Number(route.params.id);
const plot = ref<IPlot>();
const posts = ref<IPost[]>();
const paginator = usePaginator(posts, 'Plot', route, router);
const openTextEditor = ref(false);
const textValue = ref<string>('');

api.get<IPlot>({ resource: PLOT, id: id }).then((res) => {
  plot.value = res.body;

  api
    .get<IPost[]>({ resource: PLOT, id: id, query: `/posts?page=${currentPage(route)}` })
    .then((postsResponse) => {
      posts.value = postsResponse.body;
    });
});
</script>
<template>
  <div>
    <div class="f-grow" :key="route.name?.toString()">
      <div class="box">
        <div class="box-header">
          <Transition name="fade" mode="out-in">
            <span
              v-if="plot?.name"
              :key="plot.name"
              class="d-flex j-c-space-between a-i-center"
              ><h1>{{ plot.name }}</h1>
            </span>
            <h1 v-else key="loading">{{ t("loading") }}</h1>
          </Transition>
        </div>
      </div>
    </div>
    <Paginator
        v-if="paginator"
        :active="paginator('active')"
        :next="paginator('isNext')"
        :prev="paginator('isPrev')"
        :callBack="paginator"
      />
    <TransitionGroup name="list-complete" tag="div" mode="out-in">
      <div
        v-for="post in posts"
        class="box my-1 row list-complete-item"
        :key="post.id"
      >
        <div class="col-2">
          <div class="autor_post column-xl">
            <ul class="list-post">
              <li
                class="list-item-no-border text-center-xl fd-sm-column-xl-row a-item-sm-start-xl-center"
              >
                <p class="my-1">
                  <router-link
                    :to="{
                      name: 'Profile',
                      params: { slug: post.user.slug, id: post.user.id },
                    }"
                    v-html="
                      parseUsername(
                        post.user.username,
                        post.user.user_group.html_code
                      )
                    "
                  >
                  </router-link>
                </p>
                <p class="d-sm-block my-1">
                  {{ parseDate(post.created_at) }}
                </p>
              </li>
              <li class="list-item-no-border">
                <router-link
                  :to="{
                    name: 'Profile',
                    params: { slug: post.user.slug, id: post.user.id },
                  }"
                >
                  <img
                    :src="store.state.baseUrl + post.user.avatar"
                    alt="Avatar"
                    class="avatar post-img"
                  />
                </router-link>
              </li>
            </ul>
            <ul class="list-post d-sm-none-xl-flex">
              <li
                class="list-item"
                v-html="post.user.user_group.group_name"
              ></li>
              <li class="list-item">
                {{ t("Posts") }} - {{ post.user.posts_no }}
              </li>
              <li class="list-item">
                {{ t("Plots") }} - {{ post.user.plots_no }}
              </li>
            </ul>
          </div>
        </div>
        <div class="f-grow column j-c-space-between">
          <span>
            <div
              class="box-content my-1 d-sm-none-xl-flex border-bottom-primary-1"
            >
              {{ t("created at") }} {{ parseDate(post.created_at) }}
            </div>
            <div
              class="box-content my-1"
              v-html="post.content"
              :id="'post' + post.id"
            ></div>
          </span>
          
        </div>
      </div>
      <span v-if="!openTextEditor" class="m-4 d-flex j-c-end">
        <button class="btn btn-secondary" @click="openTextEditor = !openTextEditor">Reply</button>
      </span>
      <div v-if="openTextEditor" class="box">
        <div class="py-2">
        <JoditEditor :value="textValue" />
      </div>
        <span class="d-flex j-c-end">
        <button class="btn btn-secondary" >Send</button>
      </span>
      </div>
    </TransitionGroup>
  </div>

</template>
