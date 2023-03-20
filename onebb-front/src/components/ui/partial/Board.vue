<script setup lang="ts">
import Skeleton from "@/components/ui/elements/Skeleton/CategorySkeleton.vue";
import Paginator from "@/components/ui/elements/Paginator.vue";
import { IBoard, IPlot } from "@/interfaces/obbApiInterface";
import { defineProps } from "vue";
import { useStore } from "vuex";
import { parseUsername, parseDate } from "@/services/helpers/parsers";

const store = useStore();
const CategorySkeleton = Skeleton;

const props = defineProps<{
  board: IBoard;
  plots: IPlot[];
  loading: boolean;
  boxes: number;
  paginator?: Function;
}>();

const $t = (t: any) => {
  return t;
};

console.log(props.plots);
</script>

<template>
  <Transition name="fade" mode="out-in">
    <div v-if="loading" key="skeleton">
      <CategorySkeleton :boxes="boxes" />
    </div>
    <div v-else :key="board.id">
      
      <div class="box">
        <div class="box-header">
          <h2>{{ board.name }}</h2>
        </div>
      </div>
      <Paginator
        v-if="paginator"
        :active="paginator('active')"
        :next="paginator('isNext')"
        :prev="paginator('isPrev')"
        :callBack="paginator"
      />
      <div class="box my-1">
        <ul class="box-content list">
          <li v-for="plot in plots" class="list-item-no-border" :key="plot.id">
            <div class="mr-4">
              <i class="text-success fas fa-file fa-2x"></i>
            </div>
            <div class="content f-grow">
              <h3>
                <router-link
                  :to="{
                    name: 'Plot',
                    params: {
                      slug: plot.slug,
                      id: plot.id,
                      page: Math.ceil(plot.post_no / 20),
                    },
                  }"
                  >{{ plot.name }}</router-link
                >
              </h3>
              <span
                >przez:
                <strong style="color: red"
                  ><i class="fas fa-circle-notch fa-spin"></i>
                  {{ plot.user.username }}</strong
                ></span
              >
            </div>
            <div class="row col-2 text-center d-sm-none">
              <strong
                >{{ plot.post_no }} <i class="fas fa-comments"></i> /
                {{ plot.views }} <i class="fas fa-eye"></i
              ></strong>
            </div>
            <div class="autor col-sm-4">
              <div class="column px-xl-1 m-0">
                <div
                  class="d-sm-none text-right"
                  v-html="
                    parseUsername(
                      plot.last_active_user.username,
                      plot.last_active_user.user_group.html_code
                    )
                  "
                ></div>
                <div class="text-center">
                  <i class="far fa-clock"></i> {{ parseDate(plot.updated_at) }}
                </div>
              </div>
              <img
                :src="store.state.baseUrl + plot.last_active_user.avatar"
                alt="Avatar"
                class="avatar"
              />
            </div>
          </li>
        </ul>
      </div>
      <Paginator
        v-if="paginator"
        :active="paginator('active')"
        :next="paginator('isNext')"
        :prev="paginator('isPrev')"
        :callBack="paginator"
      />
    </div>
  </Transition>
</template>
