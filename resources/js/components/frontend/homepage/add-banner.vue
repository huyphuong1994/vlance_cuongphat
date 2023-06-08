<template>
  <section class="offers-section">
    <div class="container">
      <div class="row" v-if="countBanner > 0">
        <div v-for="(banner,index) in banners" :key="index" :class="defineClass()">
          <a v-if="urlCheck(banner)" :href="banner" class="add-banner">
            <img :src="index.slice(0, -2)" alt="Image" class="img-fluid" style="max-height: 140px">
          </a>
          <router-link v-else :to="banner" class="add-banner" :id="index.substr(-2,2)">
            <img :src="index.slice(0, -2)" :alt="index" class="img-fluid" style="max-height: 140px">
          </router-link>
        </div>
      </div>
      <div class="row" v-else-if="show_shimmer">
        <div class="col-md-12" v-for="(attr,i) in 1" :key="'banner_'+i">
          <a href="javascript:void(0)" class="add-banner">
            <shimmer :height="312"></shimmer>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import shimmer from "../partials/shimmer";

export default {
  name: "add-banner",
  components: {
    shimmer
  },
  props: [
    'data'
  ],
  data() {
    return {
      mouse_drag: true,
      show_shimmer: true,
      banners: true
    }
  },

  mounted() {
    this.checkHomeComponent("banner");
  },
  watch : {
    homeResponse() {
      this.checkHomeComponent("banner");
    },
    data() {
      if(this.data[0]) this.banners = this.data[0]
    }
  },
  computed: {
    countBanner() {
      let length = 0;
      if (this.banners) {
        length = Object.keys(this.banners).length;
      }
      return length;
    }
  },
  methods: {
    defineClass() {
      let total_banner = this.countBanner;

      let col_class = 'col-md-12';
      if (total_banner == 4) {
        col_class = 'col-md-3';
      }
      if (total_banner == 3) {
        col_class = 'col-md-4';
      }
      if (total_banner == 2) {
        col_class = 'col-md-6';
      }
      return col_class;
    },
    checkHomeComponent(component_name) {
      let component = this.homeResponse.find(data => data == component_name);

      if (component) {
        return this.show_shimmer = false;
      }
    }
  }
}
</script>
