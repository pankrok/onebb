<script setup lang="ts">
import Skeleton from "@/components/ui/elements/Skeleton/CategorySkeleton.vue";
import Paginator from "@/components/ui/elements/Paginator.vue";
import { IUser } from "@/interfaces/obbApiInterface";
import { defineProps } from "vue";
import { useStore } from "vuex";
import { parseUsername } from "@/services/helpers/parsers";

const props = defineProps<{
  canEdit: boolean;
  userData?: IUser;
}>();

const store = useStore();
</script>

<template>
  <div v-if="userData" class="f-grow list-complete-item p-relative">
    <div class="f-grow row">
      <div class="col-3">
        <div class="box">
          <div class="box-content d-flex column a-i-center j-c-center">
            <div class="relative">
              <img
                :src="store.state.baseUrl + userData.avatar"
                :alt="userData.username + ' avatar'"
                class="avatar img100"
              />
            </div>
            <ul class="list w-100">
              <li class="list-item text-center px-1">
                <span>Użytkownik:</span>
                <span v-html="parseUsername(userData)"></span>
              </li>
              <li class="list-item text-center px-1">
                <span>Grupa:</span>
                <!-- fixme -->
                <span
                  v-html="
                    parseUsername(
                      userData.user_group.group_name,
                      userData.user_group.html_code
                    )
                  "
                > 
                </span><!-- fixme -->
              </li>
              <li class="list-item text-center px-1">
                <span>Postów:</span> <span>{{ userData.posts_no }}</span>
              </li>
              <li class="list-item text-center px-1">
                <span>Tematów:</span> <span>{{ userData.plots_no }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-9"></div>
      <!---->
    </div>
  </div>
  <Skeleton v-else :boxes="3" />
</template>
