<template>
  <section class="categories-section category-style-1 bg-color top">
    <div class="container" v-if="lengthCounter(countCategories)>0">
      <div class="title justify-content-center" v-if="!addons.includes('ishopet')">
        <h1>{{ lang.top_categories_of_this_month }}</h1>
      </div>
      <div class="category-content" :class="{ 'slider-arrows ishopet-category' : addons.includes('ishopet') }">
        <VueSlickCarousel ref="carousel" v-bind="slick_settings" :rtl="settings.text_direction == 'rtl'">
          <div class="global-list category-slider text-center"
               v-for="(category,index) in top_categories" :key="index">
            <router-link class="slider_div" :to="{name : 'product.by.category',params : {id : category.id, slug : category.slug } }"><img
                :src="category.top_image" :alt="category.title" class="img-fluid"><span
                class="text-ellipse text-ellipse-one">{{ category.title }}</span></router-link>
          </div>
        </VueSlickCarousel>
      </div>
    </div>
    <div class="container" v-else-if="show_shimmer">
      <div class="category-content">
        <div class="d-flex">
          <div class="global-list category-slider text-center"
               v-for="(category,index) in 6" :key="index">
            <a href="#"><shimmer :height="160"></shimmer></a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import shimmer from "../partials/shimmer";
import VueSlickCarousel from 'vue-slick-carousel'

export default {
  name: "top_categories",
  props: ['top_categories'],
  components: {
    shimmer, VueSlickCarousel
  },
  data() {
    return {
      slick_settings: {
        "dots": false,
        "edgeFriction": 0.35,
        "infinite": true,
        "arrows": true,
        "autoplay": false,
        "slidesToShow": 6,
        "slidesToScroll": 4,
        "centerPadding": '50px',
        "responsive": [
          {
            "breakpoint": 1024,
            "settings": {
              "slidesToShow": 5,
              "slidesToScroll": 2,
            }
          },
          {
            "breakpoint": 768,
            "settings": {
              "slidesToShow": 3,
              "slidesToScroll": 2,
            }
          },
          {

            "breakpoint": 480,
            "settings": {
              "slidesToShow": 2,
              "slidesToScroll": 2
            }
          },
          {

            "breakpoint": 320,
            "settings": {
              "slidesToShow": 2,
              "slidesToScroll": 2
            }
          }
        ]
      },
      show_shimmer: true,
      current_slider : 0
    }
  },

  mounted() {
    this.checkHomeComponent("top_category");
  },
  watch: {
    homeResponse() {
      this.checkHomeComponent("top_category");
    }
  },
  computed: {
    countCategories() {
      if (this.top_categories && this.top_categories.length > 0) {
        return this.top_categories;
      } else {
        return [];
      }
    },
  },
  methods: {
    checkHomeComponent(component_name) {
      let component = this.homeResponse.find(data => data == component_name);

      if (component) {
        return this.show_shimmer = false;
      }
    },
  }
}
</script>
