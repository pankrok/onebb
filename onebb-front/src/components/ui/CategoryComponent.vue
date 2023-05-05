<script setup lang="ts">

import { defineProps } from 'vue';
import Image from './ImageComponent.vue';
import { useUser } from '@/hooks/useUser';
import type { IBoard } from '@/interfaces/OnebbInterfaces';

defineProps<{
    board: IBoard
}>()

const user = useUser();

</script>

<template>
    <div class="col-9">
            <router-link :to="{
                name: 'Board',
                params: { slug: board.slug, id: board.id, page: 1 },
              }">
              <span class="margin-s ">{{ board.name }}</span>
            </router-link>
          </div>
          <div v-if="board.last_active_user" class="col-3 row ">
            <div class="col-9 column text-align-end">
              <div v-html="user.parseUsername(board.last_active_user)">
              </div>
              <div>
                {{ board.updated_at }}
              </div>
            </div>
            <div class="col-3 row align-items-center justify-content-flex-end">

              <Image :src="board.last_active_user.avatar" :alt="board.last_active_user.username" :size="[50, 50]"
                rounded />

            </div>
          </div>
</template>