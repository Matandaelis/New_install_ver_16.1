					<!-- Sidebar Column -->
					<div class="col-12 col-lg-3 col-xl-2">
						<div id="sidebar-nav" class="bg-white border-end shadow-sm position-sticky overflow-auto" style="top: 140px; height: calc(100vh - 140px);">
							<div class="p-3">
								<!-- Search Bar -->
								<div class="mb-4">
									<div class="card border-0 shadow-sm">
										<div class="card-body p-3">
											<div class="position-relative">
												<input type="text" id="apiSearch" class="form-control border-0 ps-5 pe-5 bg-light" placeholder="Search User APIs..." style="height: 45px;">
												<i class="bi bi-search position-absolute top-50 start-0 translate-middle-y text-muted ms-3"></i>
												<button class="btn btn-sm position-absolute top-50 end-0 translate-middle-y p-1 d-none me-2 border-0 bg-transparent" type="button" id="clearSearch">
													<i class="bi bi-x-circle-fill text-muted fs-6"></i>
												</button>
											</div>
											<div class="text-center mt-2">
												<small class="text-muted">
													<kbd class="bg-white text-muted border px-2 py-1 rounded-1 small">Ctrl</kbd>
													<span class="mx-1">+</span>
													<kbd class="bg-white text-muted border px-2 py-1 rounded-1 small">K</kbd>
												</small>
											</div>
										</div>
									</div>
								</div>

								<!-- Endpoint Counter -->
								<div class="mb-3 px-2">
									<div class="d-flex align-items-center justify-content-between">
										<span class="small text-muted fw-medium">User Endpoints</span>
										<span class="badge bg-primary rounded-pill">40+</span>
									</div>
								</div>

<nav>
	<ul class="nav flex-column gap-1" id="apiNavList">
		<li class="nav-item">
			<a href="#intro" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-house me-2 text-primary"></i>
				<span>Introduction</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#user" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#user_nav" aria-expanded="false">
				<i class="bi bi-person me-2 text-primary"></i>
				<span class="flex-grow-1">User</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="user_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#registration-form-details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Registration Form</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#registration" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Registration</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#login" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Login</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#change_password" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Change Password</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_my_profile_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>My Profile Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#update_my_profile" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Update My Profile</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#dashboard" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-speedometer2 me-2 text-primary"></i>
				<span>Dashboard</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#my_affiliate_links" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-link-45deg me-2 text-primary"></i>
				<span>My Affiliate Links</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#my_log_list" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-journal-text me-2 text-primary"></i>
				<span>My Logs List</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#my_network" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-diagram-3 me-2 text-primary"></i>
				<span>My Network</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#get_user_reports" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-file-earmark-text me-2 text-primary"></i>
				<span>My Report</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#contact_to_admin" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-envelope me-2 text-primary"></i>
				<span>Contact Admin</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#get_user_payments" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-credit-card me-2 text-primary"></i>
				<span>My Payments</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#get_user_payments_details" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#payments_nav" aria-expanded="false">
				<i class="bi bi-credit-card-2-front me-2 text-primary"></i>
				<span class="flex-grow-1">My Payment Details</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="payments_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#post_bank_detail" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Bank Details</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#post_paypal_account" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Add Paypal Account</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#post_primary_method" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Primary Payment Method</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_payment_information" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Payment Information</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#category" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#category_nav" aria-expanded="false">
				<i class="bi bi-tags me-2 text-primary"></i>
				<span class="flex-grow-1">Category</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="category_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#get_integration_category" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Integration Category</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_store_category" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Store Category</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#my_wallet" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#wallet_nav" aria-expanded="false">
				<i class="bi bi-wallet2 me-2 text-primary"></i>
				<span class="flex-grow-1">My Wallet</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="wallet_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#my_transaction" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>My Transaction</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#recurring_transaction_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Recurring Transaction List</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#withdraw_request_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Withdraw Request List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#withdraw_request" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Withdraw Request</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#perticular_withdraw_request_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Particular Withdraw Request</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#my_order" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#order_nav" aria-expanded="false">
				<i class="bi bi-cart me-2 text-primary"></i>
				<span class="flex-grow-1">My Order</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="order_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#my_order_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>My Order List</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#my_order_status_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>My Orders Status List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#subscription_plan" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#subscription_nav" aria-expanded="false">
				<i class="bi bi-calendar-check me-2 text-primary"></i>
				<span class="flex-grow-1">Subscription Plan</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="subscription_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#buy_membership_plan" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Buy Membership Plan</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#plan_history" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Plan History</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#vendor_market_place" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#vendor_nav" aria-expanded="false">
				<i class="bi bi-shop me-2 text-primary"></i>
				<span class="flex-grow-1">Vendor Market Place</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="vendor_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#my_products" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>My Products</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_product_name" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Product Name</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#manage_product_coupon" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Manage Product Coupon</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#delete_coupon" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Delete Coupon</span>
							<span class="badge bg-danger">DELETE</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_store_setting_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Store Setting Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#manage_store_setting_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Manage Store Setting Details</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_store_coupon_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Store Coupon List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_countrie_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Countries List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_state_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get State List</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#manage_product" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Manage Product</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#delete_product" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Delete Product</span>
							<span class="badge bg-danger">DELETE</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#create_duplicate_product" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Create Duplicate Product</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_product_all_images" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Product All Images</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#delete_product_image" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Delete Product Image</span>
							<span class="badge bg-danger">DELETE</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#add_product_images" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Add Product Images</span>
							<span class="badge bg-success">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#vendor_market_tools" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#tools_nav" aria-expanded="false">
				<i class="bi bi-tools me-2 text-primary"></i>
				<span class="flex-grow-1">Vendor Market Tools</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="tools_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#manage_my_marketing_program" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Manage My Marketing Program</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_my_marketing_program_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get My Marketing Program List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#delete_my_marketing_program" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Delete My Marketing Program</span>
							<span class="badge bg-danger">DELETE</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_integration_tools" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Integration Tools</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_dynamic_param" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Dynamic Param</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#duplicate_intrigation_tools" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Duplicate Integration Tools Ads</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#intrigation_tools_manage" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Integration Tools Manage</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#get_affiliate_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Affiliate List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#delete_intrigation_tools" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Delete Integration Tools</span>
							<span class="badge bg-danger">DELETE</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#notification" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#notification_nav" aria-expanded="false">
				<i class="bi bi-bell me-2 text-primary"></i>
				<span class="flex-grow-1">Notification</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="notification_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-primary border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#notification_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Notification List</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#delete_notifications" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Delete Notification</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
	</ul>
</nav>
							</div>
						</div>
					</div>

					<!-- Main Content Column -->
					<div class="col-12 col-lg-9 col-xl-10">
