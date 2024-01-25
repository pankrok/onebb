<script setup lang="ts">
import { useMessenger } from '@/hooks/obbClient';


const messenger = useMessenger()
</script>

<template>

        <div class="column flex-grow-1">
          <div class="row justify-content-flex-start align-items-center padding-m">
            <span class="col-12 font-size-14 font-weight-100 color-white padding-bottom-s"
              >PanKrok</span
            >
            <img
              src="https://www.ftsgps.com/wp-content/uploads/2020/04/pies-kasi.png.webp"
              class="border-radius-circel img-size-mobile-s margin-right-s"
            />

            <span
              class="flex-1 border-1 border-color-blue border-radius-5 background-blue color-black font-size-14 padding-s"
              >Hello dude</span
            >
          </div>
          <div class="row justify-content-flex-end align-items-center padding-m">
            <span
              class="col-12 font-size-14 font-weight-100 color-white padding-bottom-s text-align-right"
              >Some Dude</span
            >
            <span
              class="flex-1 border-1 background-primary border-color-dark border-radius-5 font-size-14 padding-s text-align-right"
              >Whatsup!</span
            >
            <img
              src="https://www.ftsgps.com/wp-content/uploads/2020/04/pies-kasi.png.webp"
              class="border-radius-circel img-size-mobile-s margin-left-s"
            />
          </div>
          <span class="border-bottom-1 border-color-blue color-blue font-size-14 text-align-center">
            new message!
          </span>

          <div class="row justify-content-flex-start align-items-center padding-m">
            <span class="col-12 font-size-14 font-weight-100 color-white padding-bottom-s"
              >PanKrok</span
            >
            <img
              src="https://www.ftsgps.com/wp-content/uploads/2020/04/pies-kasi.png.webp"
              class="border-radius-circel img-size-mobile-s margin-right-s"
            />

            <span
              class="flex-1 border-1 border-color-blue border-radius-5 background-blue box-shadow-blue color-black font-size-14 padding-s"
            >
              this is my new message!
            </span>
          </div>
        </div>
        <div class="row">
          <div class="col-8 column">
            <input type="text" class="form-control color-white font-weight-400" />
            </div>
            <div class="col-4 column">
              <button
              class="button color-white"
              @click="messenger.sendMessage('test')"
            >
              Send
            </button>
            </div>
        </div>

</template>