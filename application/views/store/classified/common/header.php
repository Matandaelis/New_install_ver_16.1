<?php
  $db =& get_instance();
  $products = $db->Product_model;
  $sales_store_side_font = $products->getSettings('site','sales_store_side_font');
  $SiteSetting = $products->getSettings('site');
  
  if ($sales_store_side_font['sales_store_side_font'] == "Roboto") {
  	$sales_store_side_font['sales_store_side_font'] = '"Roboto", sans-serif';
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta property='og:url' content='<?= $_SERVER['REQUEST_URI']; ?>'/>
	<?php if(isset($meta_title)){ ?> <meta property="og:title" content="<?php echo $meta_title ?>"/><?php } ?>
	<?php if(isset($meta_description)){ ?> 
	<meta name="description" content="<?php echo $meta_description ?>"/>
	<meta property="og:description" content="<?php echo $meta_description ?>"/>
	<?php } ?>
	<?php if(isset($meta_image)){ ?> <meta property="og:image" content="<?php echo $meta_image ?>"/><?php } ?>

	<meta
		property="og:url"
		content="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>"
	/>

	<meta name="twitter:card" content="summary_large_image"/>

	<?php if($store_setting['favicon']){ ?>
		<link rel="icon" href="<?= base_url('assets/images/site/'.$store_setting['favicon']) ?>" type="image/*" sizes="16x16">
	<?php } ?>

	<?php 
	// Add canonical URL for SEO to prevent duplicate content issues  
	$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if(isset($product) && isset($product['product_id'])) {
		// Use the standard sales mode URL format as canonical
		$canonical_url = base_url("store/product/" . $product['product_id']);
		echo '<link rel="canonical" href="' . $canonical_url . '" />';
	} else {
		// For other pages, use current URL
		echo '<link rel="canonical" href="' . $current_url . '" />';
	}
	?>

	<?php show_messenger_button($SiteSetting, 'store'); ?>

   <title><?= $store_setting['name'] ?>  <?= isset($meta_title) ? '- ' . $meta_title : '' ?></title>

	<!-- Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/fontawesome/css/all.min.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/fontawesome/css/all.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/flaticon/flaticon.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/owl.carousel/css/owl.carousel.min.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/owl.carousel/css/owl.theme.default.min.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/jquery-animated-headlines/css/jquery.animatedheadline.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?= base_url('assets/store/classified/'); ?>assets/css/sweetalert2.min.css?v=9.0.0.3" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/magnific-popup/css/magnific-popup.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/animate.css/css/animate.min.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>dependencies/meanmenu/css/meanmenu.min.css" rel="stylesheet" />
	<link href="<?= base_url('assets/store/classified/'); ?>assets/css/app.css" rel="stylesheet" />
	
	<!-- jQuery -->
	<script src="<?= base_url('assets/store/classified/'); ?>dependencies/jquery/js/jquery.min.js"></script>
	<script src="<?= base_url('assets/store/shared/js/nouislider.min.js') ?>"></script>
	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Other JS Libraries -->
	<script src="<?= base_url('assets/store/classified/'); ?>dependencies/owl.carousel/js/owl.carousel.min.js"></script>
	<script src="<?= base_url('assets/store/classified/'); ?>dependencies/imagesloaded/js/imagesloaded.pkgd.min.js"></script>
	<script src="<?= base_url('assets/store/classified/'); ?>dependencies/jquery-animated-headlines/js/jquery.animatedheadline.min.js"></script>
	<script src="<?= base_url('assets/store/classified/'); ?>dependencies/magnific-popup/js/jquery.magnific-popup.min.js"></script>
	<script src="<?= base_url('assets/store/classified/'); ?>dependencies/elevatezoom/js/jquery.elevateZoom-2.2.3.min.js"></script>
	<script src="<?= base_url('assets/store/classified/'); ?>assets/js/sweetalert2.all.min.js"></script>
	<script src="<?= base_url('assets/store/classified/'); ?>dependencies/meanmenu/js/jquery.meanmenu.min.js"></script>
	
	<style type="text/css">
		/* Typography */
		h1, h2, h3, h4, h5, h6, span {
			font-family: <?= $sales_store_side_font['sales_store_side_font'] ?> !important;
		}
		
		/* Header shadow enhancement */
		.header-enhanced {
			box-shadow: 0 2px 15px rgba(0,0,0,0.1);
			background-color: rgba(255,255,255,0.98);
			backdrop-filter: blur(10px);
		}
		
		/* Logo hover effect */
		.logo-hover:hover {
			transform: scale(1.05);
			transition: transform 0.3s ease;
		}
		
		/* Navigation link hover effects */
		.nav-link-custom {
			position: relative;
			overflow: hidden;
		}
		
		.nav-link-custom::after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			width: 0;
			height: 2px;
			background: linear-gradient(45deg, #0d6efd, #0b5ed7);
			transition: all 0.3s ease;
			transform: translateX(-50%);
		}
		
		.nav-link-custom:hover::after {
			width: 80%;
		}
		
		/* User icon styling */
		.user-avatar {
			width: 40px;
			height: 40px;
			background: linear-gradient(135deg, #0d6efd, #0b5ed7);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			transition: all 0.3s ease;
		}
		
		.user-avatar:hover {
			transform: scale(1.1);
			box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
		}
	</style>

    <?php if(is_rtl()) { ?>
      <!-- RTL Support -->
      <link rel="stylesheet" href="<?= base_url('assets/store/classified/'); ?>assets/css/rtl.css?v=<?= av() ?>"/>
   	<?php } ?>
   	
   	<script type="text/javascript">
		var grecaptcha = undefined;
		
		// Enhanced interactions with Bootstrap 5
		document.addEventListener('DOMContentLoaded', function() {
			// Header scroll effect
			window.addEventListener('scroll', function() {
				const header = document.querySelector('.header-enhanced');
				if (window.scrollY > 50) {
					header?.classList.add('shadow-lg');
				} else {
					header?.classList.remove('shadow-lg');
				}
			});
		});
	</script>
   	
<?= render_js_error_reporter() ?>
</head>

<?php
$storelogoheight=40;
$storelogowidthstr='';
  if($store_setting['store_custom_logo_size']!=0)
  {
    $storelogowidth=$store_setting['store_logo_custom_width'];
    $storelogoheight=$store_setting['store_logo_custom_height'];
    $storelogowidthstr= 'width="'.$storelogowidth.'"'; 
  }
?>

<body class="sticky-header" style="font-family: <?= $sales_store_side_font['sales_store_side_font'] ?>;">
	<div class="wrapper" id="wrapper">
		<header class="header header-enhanced sticky-top bg-white" aff-section="classified_header"></header>
		<script aff-template="classified_header" type="text/html">
			<div id="rt-sticky-placeholder"></div>
			<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" id="header-menu">
				<div class="container">
					<!-- Logo -->
					<div class="navbar-brand me-4">
						<div class="d-flex align-items-center">
							<?php  
							$logo = ($store_setting['logo']) ? base_url('assets/images/site/'.$store_setting['logo']) : base_url('assets/store/default/').'img/logo.png'; 
							?>
							<a href="{{home_page_url}}" class="text-decoration-none logo-hover" aria-label="<?= $store_setting['name'] ?> Home">
							<img 
							    src="<?= $logo ?>" 
							    onerror="this.src='<?= base_url('assets/store/default/img/logo.png') ?>';" 
							    height="<?= $storelogoheight ?>" 
							    <?= $storelogowidthstr ?> 
							    loading="lazy" 
							    class="img-fluid"
							    style="max-height: <?= $storelogoheight ?>px; height: auto;">
							</a>
						</div>
					</div>

					<!-- Mobile Toggle Button -->
					<button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>

					<!-- Navigation -->
					<div class="collapse navbar-collapse" id="navbarNav">
						<ul class="navbar-nav me-auto mb-2 mb-lg-0">
							<li class="nav-item">
								<a class="nav-link fw-semibold nav-link-custom px-3" href="{{home_page_url}}" aria-label="Go to Home Page">
									<?= __('store.home')?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link fw-semibold nav-link-custom px-3" href="{{aboutus_page_url}}" aria-label="Learn About Us">
									<?= __('store.about_us')?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link fw-semibold nav-link-custom px-3" href="{{catalog_page_url}}" aria-label="Browse Our Catalog">
									<?= __('store.catalog')?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link fw-semibold nav-link-custom px-3" href="{{contact_page_url}}" aria-label="Contact Us">
									<?= __('store.contact')?>
								</a>
							</li>
						</ul>

						<!-- Right Side Actions -->
						<div class="d-flex align-items-center gap-2">
							<!-- Language Dropdown -->
							{{#SelectedLanguage}}
							<div class="dropdown">
								<button class="btn btn-outline-primary dropdown-toggle rounded-pill px-3" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select Language">
									<i class="fas fa-globe me-1" aria-hidden="true"></i>
									<span class="d-none d-sm-inline">{{SelectedLanguage}}</span>
								</button>
								<ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="languageDropdown">
									{{#LanguageHtml}}
									<li><a class="dropdown-item py-2" href="{{href}}" aria-label="Switch to {{name}}">{{name}}</a></li>
									{{/LanguageHtml}}
								</ul>
							</div>
							{{/SelectedLanguage}}

							<!-- Currency Dropdown -->
							{{#SelectedCurrency}}
							<div class="dropdown">
								<button class="btn btn-outline-success dropdown-toggle rounded-pill px-3" type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select Currency">
									<i class="fas fa-dollar-sign me-1" aria-hidden="true"></i>
									<span class="d-none d-sm-inline">{{SelectedCurrency}}</span>
								</button>
								<ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="currencyDropdown">
									{{#CurrencyHtml}}
									<li><a class="dropdown-item py-2" href="{{href}}" aria-label="Switch to {{code}}">{{code}}</a></li>
									{{/CurrencyHtml}}
								</ul>
							</div>
							{{/SelectedCurrency}}

							<!-- User Menu -->
							{{^loginUser}}
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle rounded-pill px-3" type="button" id="loginDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Login Options">
									<i class="fas fa-sign-in-alt me-1" aria-hidden="true"></i>
									<span class="d-none d-lg-inline"><?= __('store.login')?></span>
								</button>
								<ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="loginDropdown">
									<li>
										<a class="dropdown-item py-2" href="{{customer_login_url}}" aria-label="Customer Login">
											<i class="fas fa-user me-2 text-primary" aria-hidden="true"></i><?= __('store.client_login')?>
										</a>
									</li>
									<li>
										<a class="dropdown-item py-2" href="{{affiliate_login_url}}" target="_blank" aria-label="Affiliate Login">
											<i class="fas fa-handshake me-2 text-success" aria-hidden="true"></i><?= __('store.affiliate_login')?>
										</a>
									</li>
								</ul>
							</div>
							{{/loginUser}}
							
							{{#loginUser}}
							<div class="dropdown">
								<button class="btn p-0 border-0 bg-transparent" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User Menu">
									<div class="user-avatar">
										<i class="fas fa-user" aria-hidden="true"></i>
									</div>
								</button>
								<ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" aria-labelledby="userDropdown">
									<li>
										<a class="dropdown-item py-2" href="{{customer_profile}}" aria-label="View Profile">
											<i class="fas fa-user-circle me-2 text-info" aria-hidden="true"></i><?= __('store.client_profile')?>
										</a>
									</li>
									<li>
										<a class="dropdown-item py-2" href="{{customer_orders}}" aria-label="View Orders">
											<i class="fas fa-shopping-bag me-2 text-warning" aria-hidden="true"></i><?= __('store.client_orders')?>
										</a>
									</li>
									<li>
										<a class="dropdown-item py-2" href="{{customer_wishlist}}" aria-label="View Wishlist">
											<i class="fas fa-heart me-2 text-danger" aria-hidden="true"></i><?= __('store.wishlist')?>
										</a>
									</li>
									<li><hr class="dropdown-divider"></li>
									<li>
										<a class="dropdown-item py-2 text-danger" href="{{customer_logout_url}}" aria-label="Logout">
											<i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i><?= __('store.logout')?>
										</a>
									</li>
								</ul>
							</div>
							{{/loginUser}}
						</div>
					</div>
				</div>
			</nav>

			<!-- Mobile Menu Content (Hidden by default, shown when collapsed) -->
			<section id="meanmenu-content" class="d-none d-lg-none bg-light border-top">
				<div class="container py-3">
					<div class="row align-items-center">
						<div class="col-6">
							<div class="d-flex align-items-center">
								<a href="{{home_page_url}}" class="text-decoration-none" aria-label="{{store_name}} Home">
									<img src="{{logo}}" height="32" onerror="this.src='<?=base_url('assets/store/default/').'img/logo.png'?>';" alt="{{store_name}} Logo" class="img-fluid" loading="lazy">
								</a>
							</div>
						</div>

						<div class="col-6 d-flex justify-content-end align-items-center gap-2">
							{{#SelectedLanguage}}
							<div class="dropdown">
								<button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="mobileLanguageDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select Language">
									<i class="fas fa-globe" aria-hidden="true"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileLanguageDropdown">
									{{#LanguageHtml}}
									<li><a class="dropdown-item" href="{{href}}" aria-label="Switch to {{name}}">{{name}}</a></li>
									{{/LanguageHtml}}
								</ul>
							</div>
							{{/SelectedLanguage}}

							{{#SelectedCurrency}}
							<div class="dropdown">
								<button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" id="mobileCurrencyDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Select Currency">
									<i class="fas fa-dollar-sign" aria-hidden="true"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileCurrencyDropdown">
									{{#CurrencyHtml}}
									<li><a class="dropdown-item" href="{{href}}" aria-label="Switch to {{code}}">{{code}}</a></li>
									{{/CurrencyHtml}}
								</ul>
							</div>
							{{/SelectedCurrency}}

							{{^loginUser}}
							<div class="dropdown">
								<button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="mobileLoginDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Login Options">
									<i class="fas fa-sign-in-alt" aria-hidden="true"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileLoginDropdown">
									<li>
										<a class="dropdown-item" href="{{customer_login_url}}" aria-label="Customer Login">
											<i class="fas fa-user me-2 text-primary" aria-hidden="true"></i><?= __('store.client_login')?>
										</a>
									</li>
									<li>
										<a class="dropdown-item" href="{{affiliate_login_url}}" target="_blank" aria-label="Affiliate Login">
											<i class="fas fa-handshake me-2 text-success" aria-hidden="true"></i><?= __('store.affiliate_login')?>
										</a>
									</li>
								</ul>
							</div>
							{{/loginUser}}

							{{#loginUser}}
							<div class="dropdown">
								<button class="btn btn-sm p-1 border-0 bg-transparent" type="button" id="mobileUserDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User Menu">
									<div class="user-avatar" style="width: 32px; height: 32px;">
										<i class="fas fa-user" style="font-size: 0.8rem;" aria-hidden="true"></i>
									</div>
								</button>
								<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileUserDropdown">
									<li>
										<a class="dropdown-item" href="{{customer_profile}}" aria-label="View Profile">
											<i class="fas fa-user-circle me-2 text-info" aria-hidden="true"></i><?= __('store.client_profile')?>
										</a>
									</li>
									<li>
										<a class="dropdown-item" href="{{customer_orders}}" aria-label="View Orders">
											<i class="fas fa-shopping-bag me-2 text-warning" aria-hidden="true"></i><?= __('store.client_orders')?>
										</a>
									</li>
									<li>
										<a class="dropdown-item" href="{{customer_wishlist}}" aria-label="View Wishlist">
											<i class="fas fa-heart me-2 text-danger" aria-hidden="true"></i><?= __('store.wishlist')?>
										</a>
									</li>
									<li><hr class="dropdown-divider"></li>
									<li>
										<a class="dropdown-item text-danger" href="{{customer_logout_url}}" aria-label="Logout">
											<i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i><?= __('store.logout')?>
										</a>
									</li>
								</ul>
							</div>
							{{/loginUser}}
						</div>
					</div>
				</div>
			</section>
		</script>
	</div>
</body>
</html>