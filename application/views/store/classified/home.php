<!--classified home -->
<section aff-section="classified_home_page"></section>
<script aff-template="classified_home_page" type="text/html">

	<!-- Hero Banner Section -->
	<section class="slide-home" style="overflow: visible;">
		<div class="owl-carousel owl-theme" id="slider-home">
			<div class="item">
				<section class="bg-dark position-relative py-5" style="background-image: url('{{theme_sections.classifiedbannerimg}}'); background-size: cover; background-position: center; min-height: 500px; overflow: visible;">
					<div class="bg-dark bg-opacity-50 position-absolute top-0 start-0 w-100 h-100"></div>
					<div class="container position-relative" style="overflow: visible;">
						<div class="row justify-content-center text-center text-white py-5">
							<div class="col-lg-8">
								<h2 class="display-4 fw-bold mb-3">{{theme_sections.classified_banner_title}}</h2>
								<p class="lead mb-5">{{theme_sections.classified_banner_subtitle}}</p>
								
								<!-- Search Form -->
								{{#filter}}
								<div class="bg-white rounded-3 shadow-lg p-4 position-relative" style="overflow: visible; z-index: 1000;">
									<form id="filter-form" action="<?php echo base_url('store/catalog') ?>">
										<div class="row g-3 align-items-end">
											<div class="col-lg-3">
												<label class="form-label text-dark fw-semibold"><?= __('store.location')?></label>
												<div class="dropdown">
													<button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="fas fa-map-marker-alt me-2"></i><?= __('store.select_location')?>
													</button>
													<ul class="dropdown-menu w-100 shadow border-0 position-absolute" style="z-index: 1060; max-height: 200px; overflow-y: auto;">
														{{#countries}}
														<li><a class="dropdown-item" href="javascript:void(0);" data-sort-key="aff_filter_country" data-sort-value="{{id}}">{{name}}</a></li>
														{{/countries}}
													</ul>
												</div>
											</div>
											
											<div class="col-lg-3">
												<label class="form-label text-dark fw-semibold"><?= __('store.category')?></label>
												<div class="dropdown">
													<button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
														<i class="fas fa-tags me-2"></i><?= __('store.select_category')?>
													</button>
													<ul class="dropdown-menu w-100 shadow border-0 position-absolute" style="z-index: 1060; max-height: 200px; overflow-y: auto;">
														{{#categories}}
														<li><a class="dropdown-item" href="javascript:void(0);" data-sort-key="aff_filter_category" data-sort-value="{{id}}">{{name}}</a></li>
														{{/categories}}
													</ul>
												</div>
											</div>

											<div class="col-lg-4">
												<label class="form-label text-dark fw-semibold"><?= __('store.keyword')?></label>
												<div class="input-group">
													<span class="input-group-text"><i class="fas fa-search"></i></span>
													<input type="text" class="form-control" placeholder="<?= __('store.enter_keyword_here...')?>" name="aff_filter_keyword">
												</div>
											</div>

											<input type="hidden" name="aff_filter_country" value="">
											<input type="hidden" name="aff_filter_category" value="">
											
											<div class="col-lg-2">
												<button class="btn btn-primary w-100 py-2" type="submit">
													<i class="fas fa-search me-1"></i><?= __('store.search')?>
												</button>
											</div>
										</div>
									</form>
								</div>
								{{/filter}}
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</section>

	<!-- Advertisements Section -->
	<section class="py-5 bg-light">
		<div class="container">
			<div class="text-center mb-5">
				<h2 class="display-6 fw-bold text-dark mb-3"><?= __('store.advertisements')?></h2>
				<div class="border-bottom border-primary border-3 mx-auto" style="width: 80px;"></div>
			</div>

			<div class="bg-white rounded-3 shadow-sm overflow-hidden">
				<!-- Tab Navigation -->
				<ul class="nav nav-tabs nav-justified border-bottom-0" role="tablist">
					<li class="nav-item">
						<a class="nav-link active fw-semibold py-3" data-bs-toggle="tab" href="#latest-ads" role="tab">
							<i class="fas fa-clock me-2"></i><?= __('store.latest_ads')?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link fw-semibold py-3" data-bs-toggle="tab" href="#discount-ads" role="tab">
							<i class="fas fa-percent me-2"></i><?= __('store.discount_ads')?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link fw-semibold py-3" data-bs-toggle="tab" href="#popular-ads" role="tab">
							<i class="fas fa-fire me-2"></i><?= __('store.popular_ads')?>
						</a>
					</li>
				</ul>

				<!-- Tab Content -->
				<div class="tab-content p-4">
					<div class="tab-pane fade active show" id="latest-ads" role="tabpanel">
						<div class="owl-carousel owl-theme product_slider">
							{{#latest_products}}
							<div class="listing-wrapper row item">
								{{#.}}
								<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
									<div class="card h-100 shadow-sm border-0">
										<div class="position-relative overflow-hidden">
											<a class="{{#product_sale_is_on}}position-relative{{/product_sale_is_on}}" href="{{product_details_url}}">
												<img alt="Product" src="{{product_featured_image}}" class="card-img-top" style="height: 200px; object-fit: cover;" />
												{{#product_sale_is_on}}
												<span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
												{{/product_sale_is_on}}
											</a>
										</div>
										
										<div class="card-body d-flex flex-column">
											<ul class="list-unstyled small text-muted mb-2">
												<li class="d-inline me-3"><i class="fa fa-tags me-1"></i>{{total_sale}} <?= __('store.sold')?></li>
												<li class="d-inline"><i class="fas fa-user me-1"></i><a href="<?=base_url('store/productionstore/')?>{{product_created_by_base64}}" class="text-decoration-none">{{product_created_by_name}}</a></li>
											</ul>

											<h5 class="card-title mb-2">
												<a href="{{product_details_url}}" class="text-decoration-none text-dark">{{product_name}}</a>
											</h5>

											<div class="mt-auto">
												<div class="d-flex justify-content-between align-items-center">
													<span class="h6 text-primary fw-bold mb-0">{{product_price}}</span>
												</div>
												<div class="d-flex gap-2 mt-2">
													<a href="{{product_details_url}}" class="btn btn-outline-primary btn-sm flex-fill"><?= __('store.read_more')?></a>
													<a href="{{product_url}}" class="btn btn-primary btn-sm flex-fill"><?= __('store.buy_now')?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{/.}}
							</div>
							{{/latest_products}}
						</div>
					</div>
					
					<div class="tab-pane fade" id="discount-ads" role="tabpanel">
						<div class="owl-carousel owl-theme product_slider">
							{{#discount_products}}
							<div class="listing-wrapper row item">
								{{#.}}
								<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
									<div class="card h-100 shadow-sm border-0">
										<div class="position-relative overflow-hidden">
											<a class="{{#product_sale_is_on}}position-relative{{/product_sale_is_on}}" href="{{product_details_url}}">
												<img alt="Product" src="{{product_featured_image}}" class="card-img-top" style="height: 200px; object-fit: cover;" />
												{{#product_sale_is_on}}
												<span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
												{{/product_sale_is_on}}
											</a>
										</div>

										<div class="card-body d-flex flex-column">
											<ul class="list-unstyled small text-muted mb-2">
												<li class="d-inline me-3"><i class="fa fa-tags me-1"></i>{{total_sale}} sold</li>
												<li class="d-inline"><i class="fas fa-user me-1"></i>{{product_created_by_name}}</li>
											</ul>

											<h5 class="card-title mb-2">
												<a href="{{product_details_url}}" class="text-decoration-none text-dark">{{product_name}}</a>
											</h5>

											<div class="mt-auto">
												<div class="d-flex justify-content-between align-items-center">
													<span class="h6 text-primary fw-bold mb-0">{{product_price}}</span>
												</div>
												<div class="d-flex gap-2 mt-2">
													<a href="{{product_details_url}}" class="btn btn-outline-primary btn-sm flex-fill"><?= __('store.read_more')?></a>
													<a href="{{product_url}}" class="btn btn-primary btn-sm flex-fill"><?= __('store.buy_now')?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{/.}}
							</div>
							{{/discount_products}}
						</div>
					</div>
					
					<div class="tab-pane fade" id="popular-ads" role="tabpanel">
						<div class="owl-carousel owl-theme product_slider">
							{{#popular_products}}
							<div class="listing-wrapper row item">
								{{#.}}
								<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
									<div class="card h-100 shadow-sm border-0">
										<div class="position-relative overflow-hidden">
											<a class="{{#product_sale_is_on}}position-relative{{/product_sale_is_on}}" href="{{product_details_url}}">
												<img alt="Product" src="{{product_featured_image}}" class="card-img-top" style="height: 200px; object-fit: cover;" />
												{{#product_sale_is_on}}
												<span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
												{{/product_sale_is_on}}
											</a>
										</div>

										<div class="card-body d-flex flex-column">
											<ul class="list-unstyled small text-muted mb-2">
												<li class="d-inline me-3"><i class="fa fa-tags me-1"></i>{{total_sale}} <?= __('store.sold')?></li>
												<li class="d-inline"><i class="fas fa-user me-1"></i>{{product_created_by_name}}</li>
											</ul>

											<h5 class="card-title mb-2">
												<a href="{{product_details_url}}" class="text-decoration-none text-dark">{{product_name}}</a>
											</h5>

											<div class="mt-auto">
												<div class="d-flex justify-content-between align-items-center">
													<span class="h6 text-primary fw-bold mb-0">{{product_price}}</span>
												</div>
												<div class="d-flex gap-2 mt-2">
													<a href="{{product_details_url}}" class="btn btn-outline-primary btn-sm flex-fill"><?= __('store.read_more')?></a>
													<a href="{{product_url}}" class="btn btn-primary btn-sm flex-fill"><?= __('store.buy_now')?></a>
												</div>
											</div>
										</div>
									</div>
								</div>
								{{/.}}
							</div>
							{{/popular_products}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Popular Categories Section -->
	<section class="py-5">
		<div class="container">
			<div class="text-center mb-5">
				<h2 class="display-6 fw-bold text-dark mb-3"><?= __('store.popular_categories')?></h2>
				<div class="border-bottom border-primary border-3 mx-auto" style="width: 80px;"></div>
			</div>

			<div class="row justify-content-center g-4">
				{{#popular_categories}}
				<div class="col-lg-2 col-md-3 col-sm-4 col-6">
					<div class="card h-100 shadow-sm border-0 text-center">
						<div class="card-body p-4">
							<div class="mb-3">
								<a href="javascript:void(0);" data-sort-key="aff_filter_category" data-sort-value="{{id}}" data-sort="true">
									<img src="{{image}}" alt="category" class="rounded-circle" style="width:60px;height:60px;object-fit:cover;" />
								</a>
							</div>
							<h6 class="card-title mb-2">
								<a href="javascript:void(0);" data-sort-key="aff_filter_category" data-sort-value="{{id}}" data-sort="true" class="text-decoration-none text-dark">{{name}}</a>
							</h6>
							<small class="text-muted">
								<a href="javascript:void(0);" data-sort-key="aff_filter_category" data-sort-value="{{id}}" data-sort="true" class="text-decoration-none">{{products_count}} <?= __('store.products')?></a>
							</small>
						</div>
					</div>
				</div>
				{{/popular_categories}}
			</div>
		</div>
	</section>

	<!-- Featured Ads Section -->
	<section class="py-5 bg-light">
		<div class="container">
			<div class="text-center mb-5">
				<h2 class="display-6 fw-bold text-dark mb-3"><?= __('store.featured_ads')?></h2>
				<div class="border-bottom border-primary border-3 mx-auto" style="width: 80px;"></div>
			</div>

			<div class="owl-carousel owl-theme" id="pupularCat">
				{{#featured_products}}
				<div class="listing-wrapper row item">
					{{#.}}
					<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
						<div class="card h-100 shadow-sm border-0">
							<div class="position-relative overflow-hidden">
								<a class="{{#product_sale_is_on}}position-relative{{/product_sale_is_on}}" href="{{product_details_url}}">
									<img alt="Product" src="{{product_featured_image}}" class="card-img-top" style="height: 200px; object-fit: cover;" />
									{{#product_sale_is_on}}
									<span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
									{{/product_sale_is_on}}
								</a>
							</div>

							<div class="card-body d-flex flex-column">
								<ul class="list-unstyled small text-muted mb-2">
									<li class="d-inline me-3"><i class="fa fa-tags me-1"></i>{{total_sale}} <?= __('store.sold')?></li>
									<li class="d-inline"><i class="fas fa-user me-1"></i>{{product_created_by_name}}</li>
								</ul>

								<h5 class="card-title mb-2">
									<a href="{{product_details_url}}" class="text-decoration-none text-dark">{{product_name}}</a>
								</h5>

								<div class="mt-auto">
									<div class="d-flex justify-content-between align-items-center">
										<span class="h6 text-primary fw-bold mb-0">{{product_price}}</span>
									</div>
									<div class="d-flex gap-2 mt-2">
										<a href="{{product_details_url}}" class="btn btn-outline-primary btn-sm flex-fill"><?= __('store.read_more')?></a>
										<a href="{{product_url}}" class="btn btn-primary btn-sm flex-fill"><?= __('store.buy_now')?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					{{/.}}
				</div>
				{{/featured_products}}
			</div>
		</div>
	</section>

	<!-- Launching Products Section -->
	{{#launching_products_available}}
	<section class="py-5 bg-primary">
		<div class="container">
			<div class="owl-carousel owl-theme" id="premusAds">
				{{#launching_products}}
				<div class="item">
					<div class="bg-white rounded-3 shadow-sm p-4">
						<div class="d-flex align-items-center">
							<div class="flex-shrink-0 me-3">
								<img alt="listing item" src="{{product_featured_image}}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
							</div>
							<div class="flex-grow-1">
								<h6 class="mb-2"><a href="#" class="text-decoration-none text-dark">{{product_name}}</a></h6>
								<small class="text-muted">
									<i class="fas fa-calendar-alt me-1"></i><?= __('store.launching_on')?>: {{product_launch_date}}
								</small>
							</div>
						</div>
					</div>
				</div>
				{{/launching_products}}
			</div>
		</div>
	</section>
	{{/launching_products_available}}
</script>

<script type="text/javascript">
	function aff_prepare_classified_home_page(data) 
	{
		data['latest_products'] = createChunks(data['latest_products'], 8);
		data['discount_products'] = createChunks(data['discount_products'], 8);
		data['popular_products'] = createChunks(data['popular_products'], 8);
		data['featured_products'] = createChunks(data['featured_products'], 8);
		return data;
	}

	function createChunks(array, chunk_size) {
		let chunks = [];
		while (array.length > 0)
		  chunks.push(array.splice(0, chunk_size));
		return chunks;
	}

	$(document).on('submit', '#filter-form', function () {
		$(this)
		.find('input[name]')
		.filter(function () {
		    return !this.value;
		})
		.prop('name', '');
	});

	$(document).on('click', '[data-sort-key]', function () {
		$('input[name="'+$(this).data('sort-key')+'"]').val($(this).data('sort-value'));

		console.log($(this).data('sort') );

		if($(this).data('sort') == true) {
			$('input[name="'+$(this).data('sort-key')+'"]').closest('form').submit();
		}
	});
</script>