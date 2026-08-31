					<div class="col-12 col-lg-3 col-xl-2">
						<div id="sidebar-nav" class="bg-white border-end shadow-sm position-sticky overflow-auto" style="top: 140px; height: calc(100vh - 140px);">
							<div class="p-3">
								<!-- Search Bar -->
								<div class="mb-4">
									<div class="card border-0 shadow-sm">
										<div class="card-body p-3">
											<div class="position-relative">
												<input type="text" id="apiSearch" class="form-control border-0 ps-5 pe-5 bg-light" placeholder="Search Admin APIs..." style="height: 45px;">
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
										<span class="small text-muted fw-medium">Admin Endpoints</span>
										<span class="badge bg-danger rounded-pill">36</span>
									</div>
								</div>

<nav>
	<ul class="nav flex-column gap-1" id="apiNavList">
		<li class="nav-item">
			<a href="#admin_api" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_intro_nav" aria-expanded="false">
				<i class="bi bi-house me-2 text-danger"></i>
				<span class="flex-grow-1">Introduction</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_intro_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_api" class="nav-link d-flex align-items-center text-muted rounded-1 py-1 px-2 text-decoration-none small">Overview</a>
					</li>
					<li class="nav-item">
						<a href="#admin_api_roles" class="nav-link d-flex align-items-center text-muted rounded-1 py-1 px-2 text-decoration-none small">Sub-admin roles</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_dashboard" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-speedometer2 me-2 text-danger"></i>
				<span>Dashboard</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#admin_users" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_users_nav" aria-expanded="false">
				<i class="bi bi-people me-2 text-danger"></i>
				<span class="flex-grow-1">Users</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_users_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_users_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Users List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_user_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>User Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_update_user_status" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Toggle User Status</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_team" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_team_nav" aria-expanded="false">
				<i class="bi bi-shield-lock me-2 text-danger"></i>
				<span class="flex-grow-1">Admin team</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_team_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_admin_staff_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Admin staff list</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_admin_staff_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Admin staff details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_admin_roles_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Admin roles list</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_notifications" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_notifications_nav" aria-expanded="false">
				<i class="bi bi-bell me-2 text-danger"></i>
				<span class="flex-grow-1">Notifications</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_notifications_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_notifications_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_notification_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_notification_read" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Mark read</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_notifications_mark_all_read" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Mark all read</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_withdrawals" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_withdrawals_nav" aria-expanded="false">
				<i class="bi bi-cash-stack me-2 text-danger"></i>
				<span class="flex-grow-1">Withdrawals</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_withdrawals_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_withdrawals_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Withdrawals List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_update_withdrawal_status" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Update Status</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_profile" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_profile_nav" aria-expanded="false">
				<i class="bi bi-person-circle me-2 text-danger"></i>
				<span class="flex-grow-1">Profile</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_profile_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_profile_get" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Get Profile</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_profile_update" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Update Profile</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_reports" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-graph-up me-2 text-danger"></i>
				<span>Reports & Analytics</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#admin_wallet" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-wallet2 me-2 text-danger"></i>
				<span>Wallet Overview</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#admin_programs" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_programs_nav" aria-expanded="false">
				<i class="bi bi-puzzle me-2 text-danger"></i>
				<span class="flex-grow-1">Programs</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_programs_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_programs_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Programs List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_program_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Program Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_campaigns" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_campaigns_nav" aria-expanded="false">
				<i class="bi bi-megaphone me-2 text-danger"></i>
				<span class="flex-grow-1">Campaigns</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_campaigns_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_campaigns_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Campaigns List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_campaign_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Campaign Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_categories" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_categories_nav" aria-expanded="false">
				<i class="bi bi-folder2-open me-2 text-danger"></i>
				<span class="flex-grow-1">Categories</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_categories_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_categories_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Categories List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_category_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Category Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_orders" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_orders_nav" aria-expanded="false">
				<i class="bi bi-cart-check me-2 text-danger"></i>
				<span class="flex-grow-1">Orders</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_orders_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_orders_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Orders List</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_order_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Order Details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_tickets" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_tickets_nav" aria-expanded="false">
				<i class="bi bi-headset me-2 text-danger"></i>
				<span class="flex-grow-1">Tickets</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_tickets_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_ticket_subjects" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Subjects</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_tickets_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Tickets list</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_ticket_details" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Ticket details</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_ticket_reply" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Reply</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_ticket_status" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Status</span>
							<span class="badge bg-warning text-dark">POST</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_settings" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_settings_nav" aria-expanded="false">
				<i class="bi bi-gear me-2 text-danger"></i>
				<span class="flex-grow-1">Settings</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_settings_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_settings_summary" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Settings summary</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>

		<li class="nav-item">
			<a href="#admin_membership" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_membership_nav" aria-expanded="false">
				<i class="bi bi-award me-2 text-danger"></i>
				<span class="flex-grow-1">Membership</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_membership_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_membership_plans" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Plans list</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#admin_membership_orders" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Orders list</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_click_logs" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#admin_click_logs_nav" aria-expanded="false">
				<i class="bi bi-mouse2 me-2 text-danger"></i>
				<span class="flex-grow-1">Click logs</span>
				<i class="bi bi-chevron-down"></i>
			</a>
			<div id="admin_click_logs_nav" class="collapse">
				<ul class="nav flex-column ms-3 mt-2 border-start border-2 border-danger border-opacity-25 ps-3">
					<li class="nav-item">
						<a href="#admin_click_logs_list" class="nav-link d-flex align-items-center justify-content-between text-muted rounded-1 py-1 px-2 text-decoration-none small">
							<span>Click logs list</span>
							<span class="badge bg-success">GET</span>
						</a>
					</li>
				</ul>
			</div>
		</li>

		<!-- Implementation Examples -->
		<li class="nav-item mt-3 mb-1">
			<div class="px-3">
				<hr class="my-2">
				<div class="d-flex align-items-center py-1">
					<span class="badge bg-success me-2"><i class="bi bi-code-slash"></i></span>
					<span class="fw-bold text-dark small text-uppercase">Examples</span>
				</div>
			</div>
		</li>
		<li class="nav-item">
			<a href="#admin_examples" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-braces me-2 text-success"></i>
				<span>Introduction</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#example_login" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-key me-2 text-success"></i>
				<span>Login & Token</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#example_dashboard" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-speedometer2 me-2 text-success"></i>
				<span>Dashboard Cards</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#example_users" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-people me-2 text-success"></i>
				<span>Users Table</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#example_withdrawals" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-cash-stack me-2 text-success"></i>
				<span>Withdrawals Manager</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#example_reports" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-graph-up me-2 text-success"></i>
				<span>Reports Chart</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#example_profile" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-person-circle me-2 text-success"></i>
				<span>Admin Profile</span>
			</a>
		</li>
		<li class="nav-item">
			<a href="#example_full_dashboard" class="nav-link d-flex align-items-center text-dark rounded-2 py-2 px-3 text-decoration-none">
				<i class="bi bi-window-desktop me-2 text-success"></i>
				<span>Full Starter Dashboard</span>
			</a>
		</li>

	</ul>
</nav>
							</div>
						</div>
					</div>

					<!-- Main Content Column -->
					<div class="col-12 col-lg-9 col-xl-10">
