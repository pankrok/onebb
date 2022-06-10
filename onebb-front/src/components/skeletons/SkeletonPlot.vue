<template>


<div v-for="n in boxes" class="box my-1 row" :key="n">
<div  class="col-3">
   <div class="autor_post column-xl">
    <ul class="list-post">
        <li class="list-item-no-border text-center-xl fd-sm-column-xl-row a-item-sm-start-xl-center">
            <p class="my-1">
                <span class="skeleton-box" style="width:100px;"></span>
            </p>
            <p class="d-sm-block my-1">
                <span class="skeleton-box" style="width:80px;"></span>
            </p>
        </li>
        <li class="list-item-no-border">
            <span class="skeleton-box skeleton-avatar"></span>
        </li>
     </ul>
     <ul class="list-post d-sm-none-xl-flex">
        <li class="list-item">
            <span class="skeleton-box" style="width:55px;"></span>
        </li>
        <li class="list-item">
            <span class="skeleton-box" style="width:55px;"></span>
        </li>
    </ul>
   </div>
  
   </div>
    <div class="f-grow">
    <div class="box-content my-1 d-sm-none-xl-flex border-bottom-primary-1"><span class="skeleton-box" style="width:55%;"></span></div>
    <div class="box-content my-1">
            <span class="skeleton-box" style="width:85%;"></span><br />
                <span class="skeleton-box" style="width:85%;"></span><br />
                <span class="skeleton-box" style="width:85%;"></span><br />
                <span class="skeleton-box" style="width:85%;"></span><br />
                <span class="skeleton-box" style="width:65%;"></span>
    </div>
</div>


       
    </div>

</template>

<script>
export default {
  name: 'SkeletonPlot',
  props: {
    boxes: Number,
  }
}
</script>

<style scoped>
.skeleton-header {
    background-color: var(--light);
}

.skeleton-box {
  display: inline-block;
  height: 1em;
  position: relative;
  overflow: hidden;
  background-color: var(--dark);
}
.skeleton-box::after {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  transform: translateX(-100%);
  background-image: linear-gradient(
    90deg,
    rgba(255, 255, 255, 0) 0,
    rgba(255, 255, 255, 0.2) 20%,
    rgba(255, 255, 255, 0.5) 60%,
    rgba(255, 255, 255, 0)
  );
  animation: shimmer 2s infinite;
  content: "";
}
@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}

.skeleton-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
}

@media (min-width: 768px) {
    .skeleton-avatar {
        width: 80px;
        height: 80px;
    }
}
</style>