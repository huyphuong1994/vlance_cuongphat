<template>
  <section class="categories-section"
           style="
           background-image: url('images/baner-category.jpg');
           margin-top: 5px;
">
    <div class="container" v-if="lengthCounter(countCategories) >0">
      <div class="justify-content-between" :class="{ 'title-bg' : addons.includes('ishopet') }">
        <h1 class="text-white fw-bolder">{{ lang.popular_categories }}</h1>
<!--        <a :href="getUrl('categories')"-->
<!--           @click.prevent="routerNavigator('categories',null)">{{ lang.browse_all_categories }} <span-->
<!--            class="icon mdi mdi-name mdi-arrow-right"></span></a>-->
      </div>
      <div class="category-content" :class="{ 'slider-arrows ishopet-category' : addons.includes('ishopet') }">
<!--        <VueSlickCarousel v-bind="slick_settings" :rtl="settings.text_direction == 'rtl'">-->
<!--          <div class="global-list category-slider"-->
<!--               v-for="(category,index) in popular_categories" :key="index">-->
<!--            <a class="slider_div" @mouseup="checkEl($event)" :href="getUrl('category/'+category.slug)"-->
<!--               @click.prevent="routerNavigator('product.by.category',category.slug)">-->
<!--              <img :src="category.popular_image"-->
<!--                   :alt="category.title" class="img-fluid"><span>{{ category.title }}</span>-->
<!--            </a>-->
<!--          </div>-->
<!--        </VueSlickCarousel>-->

        <div class="row">
          <div class="col-lg-3 mb-2" v-for="(category,index) in popular_categories" :key="index">
              <div class="w-100 d-flex align-middle" style="background: linear-gradient(to right, #CCFFCC, #88adf7);">
                <a class="slider_div py-1 px-4 fs-5 fw-normal" @mouseup="checkEl($event)" :href="getUrl('category/'+category.slug)"
                   @click.prevent="routerNavigator('product.by.category',category.slug)">
                {{category.title}}
                </a>
              </div>
          </div>
        </div>
      </div>
    </div>

<!--    <div class="container" v-else-if="show_shimmer">-->
<!--      <div class="category-content">-->
<!--        <div class="d-flex">-->
<!--          <div class="global-list category-slider"-->
<!--               v-for="(category,index) in 6" :key="index">-->
<!--            <a :href="getUrl('category/'+category.slug)">-->
<!--              <shimmer :height="160"></shimmer>-->
<!--            </a>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
  </section>
</template>

<script>
import shimmer from "../partials/shimmer";
import VueSlickCarousel from 'vue-slick-carousel'

export default {
  name: "popular_categories",
  components: {shimmer, VueSlickCarousel},
  props: ['popular_categories'],
  data() {
    return {
      slick_settings: {
        "dots": false,
        "edgeFriction": 0.35,
        "infinite": true,
        "arrows": false,
        "autoplay": false,
        "slidesToShow": 6,
        "slidesToScroll": 4,
        "centerPadding": '50px',
        "responsive": [
          {
            "breakpoint": 1024,
            "settings": {
              "slidesToShow": 5,
              "slidesToScroll": 5,
            }
          },
          {
            "breakpoint": 768,
            "settings": {
              "slidesToShow": 3,
              "slidesToScroll": 3,
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
      show_shimmer: true
    }
  },

  mounted() {
    this.checkHomeComponent("popular_category");
  },
  watch: {
    homeResponse() {
      this.checkHomeComponent("popular_category");
    }
  },
  computed: {
    countCategories() {
      if (this.popular_categories && this.popular_categories.length > 0) {
        return this.popular_categories;
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
    }
  }
}
</script>
