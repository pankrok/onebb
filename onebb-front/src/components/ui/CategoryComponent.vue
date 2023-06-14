<script setup lang="ts">

import { defineProps, ref } from 'vue';
import Image from './ImageComponent.vue';
import { useUser } from '@/hooks/useUser';
import type { IBoard } from '@/interfaces/OnebbInterfaces';
import { useMoment } from '@/hooks/useMoment';

const props = defineProps<{
    board: IBoard
}>()

const {parseUsername} = useUser();
const moment = useMoment();
const last_active_user = ref();

if (props.board.last_active_user) {
  last_active_user.value = parseUsername(props.board.last_active_user)
}
const updated_at = moment.parse(props.board.updated_at);
</script>

<template>
    <div class="col-9">
            <router-link :to="{
                name: 'Board',
                params: { slug: board.slug, id: board.id, page: 1 },
              }">
              <span class="margin-s">{{ board.name }}</span>
            </router-link>
          </div>
          <div v-if="board.last_active_user" class="col-3 row">
            <div class="col-9 column text-align-end">
              <div v-html="last_active_user">
              </div>
              <div>
                {{ updated_at }}
              </div>
            </div>
            <div class="col-3 row align-items-center justify-content-flex-end">

              <Image :src="board.last_active_user.avatar" :alt="board.last_active_user.username" :size="[50, 50]"
                rounded />

            </div>
          </div>
</template>