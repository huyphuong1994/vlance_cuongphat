<template>
	<li>
		<div class="sg-product">
			<div class="product-thumb">
				<a :href="getUrl('shop/' + shop.slug)" @click.prevent="routerNavigator('shop', shop.slug)">
					<img :src="shop.image_297x203" :alt="shop.shop_name" class="img-fluid" loading="lazy" />
				</a>
			</div>
			<div class="favorite-icon" v-if="authUser">
				<a href="javascript:void(0)"
					:class="{ disable_btn: btn_disabled }"
					@click="removeFollowed(shop.id)"
					v-if="shop.is_followed">
					<span class="mdi mdi-name mdi-heart"></span>
				</a>
				<a href="javascript:void(0)" @click="follow(shop.id)" :class="{ disable_btn: btn_disabled }" v-else>
					<span class="mdi mdi-name mdi-heart-outline"></span>
				</a>
			</div>

			<div class="seller-product-grid-view">
				<div class="product-info">
					<div class="seller-logo">
						<img :src="shop.image_82x82" :alt="shop.image_82x82" class="img-fluid" loading="lazy" />
					</div>
					<h3><a :href="getUrl('shop/' + shop.slug)" @click.prevent="routerNavigator('shop', shop.slug)">{{ shop.shop_name
					}}</a></h3>
					<div class="sg-rating">
						<span class="pe-2 fw-bold">{{ shop.rating }}</span>
						<star-rating class="pb-1 seller_min_margin" v-model:rating_count="shop.rating_count" :read-only="true"
							:star-size="12" :round-start-rating="false"></star-rating>
						<span class="review-count">({{ shop.reviews_count }} {{ lang.ratings }})</span>
					</div>
				</div>
				<div class="product-info-bottom">
					<ul class="global-list">
						<li>{{ lang.products }}: {{ shop.total_products }}</li>
						<li>{{ lang.joined }}: {{ shop.join_date }}</li>
					</ul>
					<a :href="getUrl('shop/' + shop.slug)" @click.prevent="routerNavigator('shop', shop.slug)"
						class="btn btn-primary w-100 mb-2">
						{{ lang.visit_store }}
					</a>
					<a :href="`tel:${shop.phone}`" v-if="shop.phone" class="btn btn-secondary w-100 mb-2" target="_blank"
						rel="nofollow">
						Liên hệ ngay
					</a>
					<a :href="shop.facebook" v-if="shop.facebook" class="btn btn-secondary w-100 mb-2" target="_blank"
						rel="nofollow">
						Facebook shop
					</a>
				</div>
			</div>
		</div>
	</li>
</template>

<script>
import StarRating from 'vue-star-rating';

export default {
	name: "single_seller",
	props: ['shop'],
	components: {
		StarRating
	},
	data() {
		return {
			btn_disabled: false
		}
	},
	computed: {
		homeResults() {
			return this.$store.getters.getHomeResults;
		},
		sellers() {
			return this.$store.getters.getAllSellers;
		},
		allFollowedSellers() {
			return this.$store.getters.getFollowedSellers;
		},
		shops() {
			return this.$store.getters.getCampaignShops;
		},
		productDetails() {
			return this.$store.getters.getProductDetails;
		}
	},
	methods: {
		follow(id) {
			let requestData = {
				id: id,
			};
			this.btn_disabled = true;
			let url = this.getUrl('user/follow-shop');
			axios.get(url, { params: requestData }).then((response) => {
				this.btn_disabled = false;
				if (response.data.error) {
					toastr.error(response.data.error, this.lang.Error + ' !!');
				} else {
					// this.shop.is_followed = true;
					if (this.homeResults.length > 0) {
						this.homeResults.forEach((homeResult) => {
							if (homeResult.key == 'top_sellers' || homeResult.key == 'featured_sellers' || homeResult.key == 'best_sellers' || homeResult.key == 'express_sellers') {
								homeResult.data.forEach((shop) => {
									if (shop.id == id) {
										shop.is_followed = true;
									}
								});
							}
						});
					}

					if (this.sellers.length > 0) {
						this.sellers.forEach((shop) => {
							if (shop.id == id) {
								shop.is_followed = true;
							}
						});
					}
					if (this.shops.length > 0) {
						this.shops.forEach((row) => {
							row.shops.data.forEach((shop) => {
								if (shop.id == id) {
									shop.is_followed = true;
								}
							});
						});
					}
					if (this.productDetails.length > 0) {
						this.productDetails.forEach((row) => {
							let shop = row.product.seller;
							if (shop.id == id) {
								shop.is_followed = true;
							}
						});
					}
				}
				this.$store.commit('setFollowedSellers', 1);
				this.$store.dispatch('FollowedSellers');
			}).catch((error) => {
				this.btn_disabled = false;
				if (error.response && error.response.status == 422) {
					toastr.error(error.response.statusText, this.lang.Error + ' !!');
				}
			})
		},
		removeFollowed(id) {

			this.checkListing = false;
			let requestData = {
				id: id,
			};
			this.btn_disabled = true;
			let url = this.getUrl('user/remove-followed');
			axios.get(url, { params: requestData }).then((response) => {
				this.btn_disabled = false;
				if (response.data.error) {
					this.$Progress.fail();
					toastr.error(response.data.error, this.lang.Error + ' !!');
				} else {
					// this.shop.is_followed = false;
					if (this.homeResults.length > 0) {
						this.homeResults.forEach((homeResult) => {
							if (homeResult.key == 'top_sellers' || homeResult.key == 'featured_sellers' || homeResult.key == 'best_sellers' || homeResult.key == 'express_sellers') {
								homeResult.data.forEach((shop) => {
									if (shop.id == id) {
										shop.is_followed = false;
									}
								});
							}
						});
					}
					if (this.sellers.length > 0) {
						this.sellers.forEach((shop) => {
							if (shop.id == id) {
								shop.is_followed = false;
							}
						});
					}
					let length = this.allFollowedSellers.length;
					if (length > 0) {
						for (let i = 0; i < length; i++) {
							let shop = this.allFollowedSellers[i];
							if (shop.id == id) {
								this.$store.commit('removeFollowedSellers', i);
							}
						}
					}

					if (this.shops.length > 0) {
						this.shops.forEach((row) => {
							row.shops.data.forEach((shop) => {
								if (shop.id == id) {
									shop.is_followed = false;
								}
							});
						});
					}

					if (this.productDetails.length > 0) {
						this.productDetails.forEach((row) => {
							let shop = row.product.seller;
							if (shop.id == id) {
								shop.is_followed = false;
							}
						});
					}
				}
			}).catch((error) => {
				this.btn_disabled = false;
				if (error.response && error.response.status == 422) {
					toastr.error(error.data.error, this.lang.Error + ' !!');
				}
			});

		}
	}
}
</script>
