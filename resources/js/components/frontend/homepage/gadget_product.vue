<template>
  <div style="margin: 24px 0">
    <div :style="{'border-bottom': `2px solid ${setting.title_active_color}`}"
         v-if="setting && setting.type == 5" class="container flex justify-content-center w-full bg-white">
      <h2 :style="{'color': setting.title_active_color}" class="text-uppercase text-center">{{
          lang.danh_muc_sp_noi_bat
        }}</h2>
    </div>
    <section :style="{'background-color': setting?.bg ? setting?.bg : '#fff'}"
             class="container products-section category-products ajax_scroll">
      <div class="" v-if="data && activeCategory">
        <div class="position-relative">
          <div
              class="d-flex align-items-center mb-3 nowrap justify-content-start category-list"
              style="border-bottom: 1px solid #e1e1e1"
              :class="{ 'title-bg' : addons.includes('ishopet') }">
            <button
                v-for="(category, index) in categories"
                :key="category.id"
                @click="() => changeCategory(category)"
                class="bg-white category-item"
                :class="{ 'category-item-active' : index == 0 }"
                :style="{
				          'color': index == 0
							    ? setting.title_active_color
							    : setting.title_color,
						      'border': 'none',
						      'border-color': setting.bg,
						      'background-color': activeCategory.id == category.id && index != 0 ? setting.title_active_color + '!important' : '#fff !important'
					      }"
            >
              {{ category.title }}
            </button>
          </div>
          <div class="row">
            <div class="col-md-5"
                 v-if="
						setting &&
						banners.length > 0 &&
						window.width >= breakpoint[4] &&
						(setting.type == 1 || setting.type == 3)
					">
              <VueSlickCarousel v-bind="slick_settings">
                <div class="category-product h-100" v-for="(banner, index) in banners" :key="index">
                  <div class="thumb h-600" v-if="banner">
                    <a v-if="urlCheck(banner.url)" :href="banner.url" class="add-banner h-100">
                      <img :src="banner.thumbnail" alt="" class="img-fluid h-100"/>
                    </a>
                    <router-link v-else :to="banner.url" class="add-banner h-100">
                      <img :src="banner.thumbnail" alt="" class="img-fluid h-100"/>
                    </router-link>
                  </div>
                </div>
              </VueSlickCarousel>
            </div>
            <div :class="[
						window.width < breakpoint[4] || (setting.type != 1 && setting.type != 3) ? 'col-lg-12' : 'col-lg-7'
					]">
              <product
                  :products="products"
                  :grid_class="grid_class()"
                  :type="setting.type"
                  :banners="banners"
              ></product>
              <div class="py-2 gradet-bottom" :style="{
					'text-align': window.width > breakpoint[2] ? 'center' : 'center'
				}">
                <div v-if="
              setting &&
              banners.length > 0 &&
              window.width >= breakpoint[4] &&
              (setting.type == 1 || setting.type == 3)
					  ">
                  <div class="row">
                    <div class="col-4">
                      <button>
                        <router-link
                            class="bg-white p-2 d-inline-flex gap-2 btn-seemore"
                            :style="{
                            'color': setting.title_active_color
                          }"
                            :to="{ name: 'product.by.gadget', params: { slug: activeCategory.slug } }"
                        >{{ lang.xem_toan_bo }}
                        </router-link>
                      </button>
                    </div>
                    <div class="col-4">
                      <button @click="() => {
                      this.current_page = this.current_page + 1
                      this.getProducts(this.activeCategory)
                    }">
                        <div
                            class="bg-white p-2 d-inline-flex gap-2 btn-seemore"
                            :style="{'color': setting.title_active_color}">
                          {{ lang.xem_them_12 }} <span class="icon mdi mdi-chevron-down"></span>
                        </div>
                      </button>
                    </div>
                    <div class="col-4">
                      <button @click="() => {
                      this.current_page = 0;
                      this.getProducts(this.activeCategory)
                    }">
                        <div
                            class="bg-white p-2 d-inline-flex gap-2 btn-seemore"
                            :style="{
                            'color': setting.title_active_color
                          }"
                        >{{ lang.thu_gon }} <span class="icon mdi mdi-chevron-up"></span>
                        </div>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="py-2 gradet-bottom" :style="{
					'text-align': window.width > breakpoint[2] ? 'center' : 'center'
				}">
            <div v-if="
						!(setting &&
						banners.length > 0 &&
						window.width >= breakpoint[4] &&
						(setting.type == 1 || setting.type == 3))
					">
              <div class="row">
                <div class="col-4">
                  <button>
                    <router-link
                        class="bg-white p-2 d-inline-flex gap-2 btn-seemore"
                        :style="{
                      'color': setting.title_active_color
                    }"
                        :to="{ name: 'product.by.gadget', params: { slug: activeCategory.slug } }"
                    >{{ lang.xem_toan_bo }}
                    </router-link>
                  </button>
                </div>
                <div class="col-4">
                  <button @click="() => {
                      this.current_page = this.current_page + 1
                      this.getProducts(this.activeCategory)
                    }">
                    <div
                        class="bg-white p-2 d-inline-flex gap-2 btn-seemore"
                        :style="{'color': setting.title_active_color}">
                      {{ lang.xem_them_12 }} <span class="icon mdi mdi-chevron-down"></span>
                    </div>
                  </button>
                </div>
                <div class="col-4">
                  <button @click="() => {
                      this.current_page = 0;
                      this.getProducts(this.activeCategory)
                    }">
                    <div
                        class="bg-white p-2 d-inline-flex gap-2 btn-seemore"
                        :style="{
                      'color': setting.title_active_color
                    }"
                    >{{ lang.thu_gon }} <span class="icon mdi mdi-chevron-up"></span>
                    </div>
                  </button>
                </div>
              </div>
            </div>
            <!--					<router-link-->
            <!--						class="bg-white p-2 d-inline-flex gap-2 btn-seemore"-->
            <!--						:style="{-->
            <!--							'color': setting.title_color-->
            <!--						}"-->
            <!--						:to="{ name: 'product.by.gadget', params: { slug: activeCategory.slug } }"-->
            <!--					>{{ lang.more_products }} <span class="icon mdi mdi-name mdi-arrow-right"></span></router-link>-->
          </div>
        </div>
      </div>
      <div class="" v-else-if="show_shimmer">
        <div class="title justify-content-between">
          <shimmer :height="45"></shimmer>
        </div>
        <div class="row">
          <div class="col-md-4" v-if="window.width >= breakpoint[4]">
            <div class="products">
              <div class="category-product">
                <div class="thumb">
                  <a href="#">
                    <shimmer :height="754"></shimmer>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div :class="[window.width < breakpoint[4] ? 'col-lg-12' : 'col-lg-8']">
            <ul class="products" :class="window.width < breakpoint[4] ? 'grid-4 grid-show' : 'grid-4 product-space'">
              <li v-for="(product, index) in 8" :key="index">
                <div class="sg-product">
                  <a href="javaScript:void(0)">
                    <shimmer :height="364"></shimmer>
                  </a>
                </div><!-- /.sg-product -->
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import VueSlickCarousel from "vue-slick-carousel";
import product from "../pages/product";
import shimmer from "../partials/shimmer";

export default {
  name: "gadget_product",
  data() {
    return {
      window: {
        width: 0,
        height: 0,
      },
      currentPage: 1,
      show_shimmer: true,
      slick_settings: {
        dots: true,
        edgeFriction: 0.35,
        infinite: true,
        speed: 500,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        autoplay: true,
        // "fade": true,
        autoplaySpeed: 5000
      },
      currentCategory: null,
      products: [],
      breakpoint: [
        0, 512, 768, 1024, 1256, 1440, 1560
      ],
      activeCategory: null,
      current_page: 0
    };
  },
  components: {
    VueSlickCarousel,
    product,
    shimmer
  },

  props: ["data"],

  computed: {
    categories() {
      return (this.data && this.data.categories) ? this.data.categories : [];
    },
    setting() {
      return (this.data && this.data.setting) ? this.data.setting : null;
    },
    banners() {
      return (this.data && this.data.banners) ? this.data.banners : [];
    },
    paginate() {
      var paginate = 8;
      if (this.data && this.data.setting.type) {
        switch (this.data.setting.type) {
          case 1:
            paginate = 6;
            break;

          case 2:
            paginate = 12;
            break;

          case 3:
            paginate = 8;
            break;

          case 4:
            paginate = 12;
            break;

          case 5:
            paginate = 8;
            break;

          default:
            break;
        }
      }

      return paginate + this.current_page * 12;
    }
  },
  created() {
    window.addEventListener("resize", this.handleResize);
    this.handleResize();
  },
  destroyed() {
    window.removeEventListener("resize", this.handleResize);
  },
  mounted() {
  },
  watch: {
    categories(categories) {
      if (categories[0]) {
        this.activeCategory = categories[0];
      }
    },
    activeCategory(category) {
      if (category && category.slug) {
        this.getProducts(category);
      }
    }
  },
  methods: {
    handleResize() {
      this.window.width = window.innerWidth;
      this.window.height = window.innerHeight;
    },
    grid_class() {
      var classes = '';

      if (this.window.width < this.breakpoint[1]) {
        classes += 'grid-2';
      } else if (this.window.width < this.breakpoint[4]) {
        classes += 'grid-3';
      } else if (this.window.width < this.breakpoint[3]) {
        classes += 'grid-4';
      } else {
        classes += 'product-space';
      }

      if (this.window.width >= this.breakpoint[4]) {
        switch (this.setting.type) {
          case 1:
            classes += ' grid-3';
            break;

          case 2:
            classes += ' grid-6';
            break;

          case 3:
            classes += ' grid-4';
            break;

          case 4:
            classes += ' grid-6';
            break;

          case 5:
            if (this.window.width > this.breakpoint[5]) {
              classes += ' grid-5';
            } else {
              classes += ' grid-4';
            }
            break;

          default:
            classes += ' grid-5';
            break;
        }
      }

      return classes;
    },
    changeCategory(category) {
      this.activeCategory = category;
      this.getProducts(category);
    },
    getProducts(category) {
      // console.log('123', this.paginate);
      // this.products = [];
      this.show_shimmer = true;
      const url = document.querySelector('meta[name="base_url"]').getAttribute('content');
      axios.get(url + '/home/filtered_products', {
        params: {
          type: 'product',
          page: 1,
          sort: 'newest',
          paginate: this.paginate,
          route: '',
          category: [category.id]
        }
      }).then((response) => {
        if (response.data && response.data.products) {
          this.products = response.data.products.data;
        }
        this.show_shimmer = false;
      });
    }
  },
};
</script>
