<template>
  <div v-if="supportedComponents">
    <slider v-if="settings.show_categories_section == 1"></slider>
    <services v-if="settings.show_service_info_section == 1"></services>
    <div v-for="(componentName, index) in supportedComponents" :key="index">
      <component
        :is="componentName"
        :data="homeResults[index] ? homeResults[index].data : []"
        :popular_categories="componentName == 'popular_category' && homeResults[index] ? homeResults[index].data : []"
        :top_categories="componentName == 'top_category' && homeResults[index] ? homeResults[index].data : []"
        :today_deals="componentName == 'today_deals' && homeResults[index] ? homeResults[index].data : []"
        :banners="componentName == 'banner' && homeResults[index] ? homeResults[index].data : []"
        :flash_products="componentName == 'flash_products' && homeResults[index] ? homeResults[index].data : []"
        :best_selling_product="componentName == 'best_selling_product' && homeResults[index] ? homeResults[index].data : []"
        :offer_ending_products="componentName == 'offer_ending' && homeResults[index] ? homeResults[index].data : []"
        :custom_products="componentName == 'custom_products' && homeResults[index] ? homeResults[index].data : []"
        :blogs="componentName == 'latest_news' && homeResults[index] ? homeResults[index].data : []"
        :brands="componentName == 'brands' && homeResults[index] ? homeResults[index].data : []"
        :sellers="componentName == 'top_shop' && homeResults[index] ? homeResults[index].data : []"
        :best_shop="componentName == 'best_shop' && homeResults[index] ? homeResults[index].data : []"
        :featured_shop="componentName == 'featured_shop' && homeResults[index] ? homeResults[index].data : []"
        :express_shop="componentName == 'express_shop' && homeResults[index] ? homeResults[index].data : []"
        :download_section="componentName == 'download_section' && homeResults[index] ? homeResults[index].data : []"
        :offer_ending_banner="componentName == 'offer_ending' && homeResults[index] ? homeResults[index].banner : null"
        :offer_ending_banner_url="componentName == 'offer_ending' && homeResults[index] ? homeResults[index].banner_url : null"
        :campaigns="componentName == 'campaign' && homeResults[index] ? homeResults[index].data : []"
        :latest_products="componentName == 'latest_products' && homeResults[index] ? homeResults[index].data : []"
        :video_shopping="addons.includes('video_shopping') && componentName == 'video_shopping' && homeResults[index] ? homeResults[index].data : []">
      </component>
    </div>

    <viewed_product v-if="settings.recent_viewed == 1"></viewed_product>
    <subscribe v-if="settings.subscription_section && settings.subscription_section == 1"></subscribe>
    <deal v-if="settings.article_section && settings.article_section == 1"></deal>
  </div>
</template>

<script>

import slider from "../homepage/slider";
import services from "../homepage/services";
import campaign from "../homepage/campaign";
import popular_categories from "../homepage/popular_categories";
import top_categories from "../homepage/top_categories";
import today_deals from "../homepage/today_deals";
import addBanner from "../homepage/add-banner";
import flash_products from "../homepage/flash_products";
import custom_products from "../homepage/custom_product";
import gadget_product from "../homepage/gadget_product";
import best_selling_product from "../homepage/best_selling_product";
import offer_ending from "../homepage/offer_ending";
import blog from "../homepage/blog";
import viewed_product from "../homepage/viewed_product";
import subscribe from "../homepage/subscribe";
import deal from "../homepage/article";
import brand from "../homepage/brand";
import top_shop from "../homepage/top_shop";
import best_shop from "../homepage/best_shop";
import featured_shop from "../homepage/featured-shop";
import express_shop from "../homepage/express-shop";
import download_section from "../homepage/download_section";
import latest_products from "../homepage/latest_product";
import video_shop from "../homepage/video_shop";

export default {
  name: "home.vue",
  data() {
    return {
      scroll_continue: true,
      scroller_key: 0,
      supportedComponents: [],
      results: [],
      video_shops: [],
      paginate: 1
    }
  },
  components: {
    slider,
    services,
    banner: addBanner,
    campaign: campaign,
    popular_category: popular_categories,
    top_category: top_categories,
    today_deals: today_deals,
    flash_products: flash_products,
    latest_products: latest_products,
    category_section: gadget_product,
    best_selling_product: best_selling_product,
    offer_ending_soon: offer_ending,
    latest_news: blog,
    custom_products: custom_products,
    viewed_product: viewed_product,
    subscribe: subscribe,
    deal: deal,
    brands: brand,
    top_shop: top_shop,
    best_shop: best_shop,
    express_shop: express_shop,
    featured_shop: featured_shop,
    download_section: download_section,
    video_shopping: video_shop
  },
  mounted() {
    this.loadComponents();

    if (this.homeResponse) {
      this.$store.commit('setShimmer', 0);
    }
  },
  computed: {
    homeComponents() {
      console.log('123', this.$store.getters.getHomeComponents)
      return this.$store.getters.getHomeComponents;
    },
    homeResults() {
      return this.$store.getters.getHomeResults;
    },
    homeScroller() {
      return this.$store.getters.getHomeScroller;
    },
  },
  watch: {
    homeComponents() {
      this.loadComponents();
    },
    homeResponse() {
      this.$store.commit('setShimmer', 0);
    },
    supportedComponents() {
      // console.log('supportedComponents', this.supportedComponents)
      // this.$store.commit('setHomeResults', this.results);
    },
    results() {
      this.$store.commit('setHomeResults', this.results);
      this.scroll_continue = true;
    }
  },
  created() {
  },
  methods: {
    submit() {
      let url = this.getUrl('home/subscribers');
      axios.post(url, this.form)
        .then((response) => {
          if (response.data.success) {
            $('#pop_up').modal('hide');
            toastr.success(response.data.success, this.lang.Success + ' !!');
            this.form.email = '';
          } else {
            if (response.data.error) {
              toastr.error(response.data.error, this.lang.Error + ' !!');
            }
          }
        }).catch((error) => {
          if (error.response.status == 422) {
            let errors = Object.keys(error.response.data.errors);
            for (let i = 0; i <= errors.length; i++) {
              toastr.error(error.response.data.errors[errors[i]][0], this.lang.Error + ' !!');
            }

          }
        })
    },
    loadComponents() {
      let keys = this.homeComponents;
      if (keys.length > 0) {
        for (let i = 0; i < keys.length; i++) {
          this.supportedComponents.push(keys[i]);
        }
        this.scrollData();
      }
    },
    scrollData() {
      let url = this.getUrl('home/page?page=' + this.paginate);
      this.scroll_continue = false;

      if (this.homeScroller) {
        axios.get(url).then((response) => {
          if (response.data.error) {
            toastr.error(response.data.error, this.lang.Error + ' !!');
            this.$Progress.fail();
          } else {
            let fetch_components = response.data.component_names;
            let data = this.homeResponse;
            for (let i = 0; i < fetch_components.length; i++) {
              data.push(fetch_components[i]);
            }
            this.paginate++;
            this.$store.commit('setResponseDone', data);
            this.componentAppend(response.data.components);
            this.$store.commit('setHomeScroller', response.data.has_more_data);
            if (this.homeScroller && fetch_components.length > 0) {
              this.scrollData();
            }
          }
        });
      }
    },
    async componentAppend(home_component) {
      let components = Object.keys(home_component);
      let results = this.results;
      for (let i = 0; i < components.length; i++) {
        let component = components[i].split('-');
        results.push({
          key: component[0],
          data: home_component[components[i]]
        });
      }

      this.results = results;
    }
  }
}
</script>
