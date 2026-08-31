<div class="container-fluid">
<div class="row mb-4 g-3">
	<div class="col-sm-6 col-xl-3">
		<div class="card border-0 shadow h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                	<div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                		<i class="fas fa-mouse-pointer fs-4 text-primary"></i>
                	</div>
                	<div class="flex-grow-1">
	                    <div class="fw-bold text-muted small text-uppercase mb-1"><?= __('user.referals_product_click_commissions') ?></div>
	                    <h4 class="fw-bold text-dark mb-0"><?= (int)$refer_total['total_product_click']['clicks'] ?> <span class="text-muted fs-6">/ <?= c_format($refer_total['total_product_click']['amounts']) ?></span></h4>
                	</div>
                </div>
            </div>
        </div>
	</div>
	
	<div class="col-sm-6 col-xl-3">
		<div class="card border-0 shadow h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                	<div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                		<i class="fas fa-shopping-cart fs-4 text-success"></i>
                	</div>
                	<div class="flex-grow-1">
	                    <div class="fw-bold text-muted small text-uppercase mb-1"><?= __('user.referals_sale_commissions') ?></div>
	                    <h4 class="fw-bold text-dark mb-0"><?= (int)$refer_total['total_product_sale']['counts'] ?> <span class="text-muted fs-6">/ <?= c_format($refer_total['total_product_sale']['amounts']) ?></span></h4>
                	</div>
                </div>
            </div>
        </div>
	</div>
	
	<div class="col-sm-6 col-xl-3">
		<div class="card border-0 shadow h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                	<div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                		<i class="fas fa-link fs-4 text-info"></i>
                	</div>
                	<div class="flex-grow-1">
	                    <div class="fw-bold text-muted small text-uppercase mb-1"><?= __('user.referals_general_click_commissions') ?></div>
	                    <h4 class="fw-bold text-dark mb-0"><?= (int)$refer_total['total_ganeral_click']['total_clicks'] ?> <span class="text-muted fs-6">/ <?= c_format($refer_total['total_ganeral_click']['total_amount']) ?></span></h4>
                	</div>
                </div>
            </div>
        </div>
	</div>
	
	<div class="col-sm-6 col-xl-3">
		<div class="card border-0 shadow h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                	<div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                		<i class="fas fa-bolt fs-4 text-warning"></i>
                	</div>
                	<div class="flex-grow-1">
	                    <div class="fw-bold text-muted small text-uppercase mb-1"><?= __('user.referals_action_commissions') ?></div>
	                    <h4 class="fw-bold text-dark mb-0"><?= (int)$refer_total['total_action']['click_count'] ?> <span class="text-muted fs-6">/ <?= c_format($refer_total['total_action']['total_amount']) ?></span></h4>
                	</div>
                </div>
            </div>
        </div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<ul class="nav nav-tabs nav-tabs-custom border-0 mb-3">
			<li class="nav-item">
				<a class="nav-link active border-0 rounded-top px-4 py-3 fw-semibold" data-bs-toggle="tab" href="#tab-menu_referring_tree">
					<i class="fas fa-sitemap me-2"></i><?= __('user.menu_referring_tree') ?>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link border-0 rounded-top px-4 py-3 fw-semibold" data-bs-toggle="tab" href="#tab-referred_users_tree">
					<i class="fas fa-users me-2"></i><?= __('user.referred_users_tree') ?>
				</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane" id="tab-referred_users_tree">
				<div class="card border-0 shadow-sm mt-3">
					<div class="card-header bg-primary text-white">
						<div class="d-flex justify-content-between align-items-center">
							<h5 class="mb-0"><i class="fas fa-tree me-2"></i><?= __('user.referred_users_tree') ?></h5>
							<div class="btn-group" role="group">
								<button class="btn btn-sm btn-light" onclick='$("#tree").fancytree("getTree").expandAll();'>
									<i class="fas fa-folder-open me-1"></i><?= __('user.open_all') ?>
								</button>
								<button class="btn btn-sm btn-light" onclick='$("#tree").fancytree("getTree").expandAll(false);'>
									<i class="fas fa-folder me-1"></i><?= __('user.close_all') ?>
								</button>
							</div>
						</div>
				</div>
				<div class="card-body">
					<link href="<?= base_url('assets/plugins/fancytree/skin-win8/ui.fancytree.css') ?>?v=<?= av() ?>" rel="stylesheet" />
					    <script src="<?= base_url('assets/plugins/fancytree/jquery.fancytree.js') ?>"></script>
					    <script src="<?= base_url('assets/plugins/fancytree/jquery.fancytree.table.js') ?>"></script>
					    
				    <script type="text/javascript">
				    	$(function() {
				    		if (typeof $.fn.fancytree === 'undefined') {
				    			/* fancytree failed to load (e.g. during a server update) — show a reload prompt */
				    			$('#tree').closest('.card-body').html(
				    				'<div class="alert alert-warning d-flex align-items-center gap-2 m-3">' +
				    				'<i class="fas fa-exclamation-triangle"></i>' +
				    				'<span><?= __("user.tree_load_error") ?> <a href="javascript:location.reload()" class="alert-link"><?= __("user.reload_page") ?></a></span>' +
				    				'</div>'
				    			);
				    			return;
				    		}
				    		$("#tree").fancytree({
				    			checkbox: false,
				    			debugLevel: 0,
				    			checkboxAutoHide: true,
				    			titlesTabbable: true,
				    			source: { url: "<?= base_url('usercontrol/myreferal_ajax') ?>" },
				    			extensions: ["table"],
				    			table: {
				    				indentation: 10,
				    				nodeColumnIdx: 0,
				    				checkboxColumnIdx: 0,
				    			},
				    			renderColumns: function(event, data) {
				    				var node = data.node,
				    				$tdList = $(node.tr).find(">td");

				    				var col1 = node.data.phone;
				    				var col2 = node.data.email;
				    				var col3 = (node.data.click + node.data.external_click + node.data.form_click + node.data.aff_click) + ' / ' + node.data.click_commission;
				    				var col4 = node.data.external_action_click + "/" + node.data.action_click_commission;
				    				var col5 = node.data.amount_external_sale_amount + "/" + node.data.sale_commission;
				    				var col6 = node.data.paid_commition + "/" + node.data.unpaid_commition;
				    				var col7 = node.data.in_request_commiton;
				    				var col8 = node.data.all_commition;

				    				$tdList.eq(1).html(col1);
				    				$tdList.eq(2).html(col2);
				    				$tdList.eq(3).html(col3);
				    				$tdList.eq(4).html(col4);
				    				$tdList.eq(5).html(col5);
				    				$tdList.eq(6).html(col6);
				    				$tdList.eq(7).html(col7);
				    				$tdList.eq(8).html(col8);
				    			},
				    			modifyChild: function(event, data) {
				    				data.tree.info(event.type, data);
				    			},
				    		});

				    		$("#tree").fancytree("getTree").expandAll();
				    	});
				    </script>
					    <div class="table-responsive">
						    <table id="tree" class="table table-hover align-middle">
						    	<colgroup>
						    		<col width="250px" />
						    		<col width="100px" />
						    		<col width="200px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    		<col width="100px" />
						    	</colgroup>
						    	<thead class="table-light">
						    		<tr>
						    			<th><i class="fas fa-user me-1"></i><?= __('user.username') ?></th>
						    			<th><i class="fas fa-phone me-1"></i><?= __('user.phone') ?></th>
						    			<th><i class="fas fa-envelope me-1"></i><?= __('user.email') ?></th>
						    			<th><i class="fas fa-mouse-pointer me-1"></i><?= __('user.clicks') ?> / <?= __('user.commissions') ?></th>
						    			<th><i class="fas fa-bolt me-1"></i><?= __('user.action_click') ?> / <?= __('user.commission') ?></th>
						    			<th><i class="fas fa-shopping-cart me-1"></i><?= __('user.sales_commissions') ?></th>
						    			<th><i class="fas fa-money-bill me-1"></i><?= __('user.paid_unpaid') ?> / <?= __('user.commissions') ?></th>
						    			<th><i class="fas fa-clock me-1"></i><?= __('user.in_request') ?></th>
						    			<th><i class="fas fa-chart-line me-1"></i><?= __('user.total') ?> / <?= __('user.commissions') ?></th>
						    		</tr>
						    	</thead>
						    	<tbody>
						    		<tr>
						    			<td></td>
						    			<td>55</td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    			<td></td>
						    		</tr>
						    	</tbody>
						    </table>
					    </div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane active" id="tab-menu_referring_tree">
				<div class="card border-0 shadow">
					<div class="card-header bg-white border-bottom py-3">
						<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
							<div class="d-flex align-items-center">
								<h5 class="mb-0 me-3 fw-bold text-dark">
									<i class="fas fa-sitemap me-2 text-primary"></i><?= __('user.menu_referring_tree') ?>
								</h5>
								<span class="badge bg-primary bg-opacity-10 text-primary fs-6 fw-semibold" id="user-tree-stats"></span>
							</div>
							
							<div class="d-flex gap-2 flex-wrap">
								<div class="btn-group btn-group-sm" role="group">
									<button type="button" class="btn btn-light" id="user-zoom-out" title="<?= __('user.zoom_out') ?>">
										<i class="fa fa-search-minus"></i>
									</button>
									<button type="button" class="btn btn-light" id="user-zoom-reset" title="<?= __('user.reset_zoom') ?>">
										<i class="fa fa-search"></i> <span class="zoom-text">100%</span>
									</button>
									<button type="button" class="btn btn-light" id="user-zoom-in" title="<?= __('user.zoom_in') ?>">
										<i class="fa fa-search-plus"></i>
									</button>
								</div>
								<button type="button" class="btn btn-sm btn-light" id="user-center-tree">
									<i class="fa fa-crosshairs me-1"></i><?= __('user.center_view') ?>
								</button>
								<button type="button" class="btn btn-sm btn-success" id="user-my-position">
									<i class="fa fa-user me-1"></i><?= __('user.my_position') ?>
								</button>
							</div>
						</div>
						
						<div class="row align-items-center mt-3">
							<div class="col-md-6">
								<div class="input-group input-group-sm">
									<span class="input-group-text bg-white">
										<i class="fa fa-search text-muted"></i>
									</span>
									<input type="text" class="form-control" id="user-tree-search" placeholder="<?= __('user.search_users_in_tree') ?>" autocomplete="off">
									<button class="btn btn-outline-light" type="button" id="user-clear-search">
										<i class="fa fa-times"></i>
									</button>
								</div>
							</div>
							<div class="col-md-6">
								<div class="d-flex align-items-center justify-content-end mt-2 mt-md-0 gap-2">
									<small class="fw-bold" id="user-search-results"></small>
									<small class="fw-bold" id="user-tree-navigation-hint">
										<i class="fa fa-info-circle me-1"></i>
										<strong><?= __('user.tree_navigation_tip') ?>:</strong>
										<?= __('user.use_mouse_wheel_zoom') ?> • <?= __('user.drag_to_pan') ?>
									</small>
									<button type="button" class="btn btn-sm btn-light p-1" onclick="dismissUserTreeHint()" title="<?= __('user.close') ?>">
										<i class="fa fa-times"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body p-0">
						<div class="user-tree-viewport bg-light" id="user-tree-viewport">
							<div class="user-tree-container" id="user-tree-container">
				            <?php 
					                function buildTree($data, $usersDetail = array()){
					                   $html = '';
				                   foreach ($data as $key => $value) {
					                     $userId = isset($value['id']) ? $value['id'] : null;
					                     $cleanName = strip_tags($value['name']);
					                     $teamSize = !empty($value['children']) ? count($value['children']) : 0;
					                     $isOnline = false;
					                     $joinDate = '';
					                     
					                     $userDetail = null;
					                     if(!empty($usersDetail)) {
					                       foreach($usersDetail as $user) {
					                         if((isset($user['id']) && $user['id'] == $userId) || 
					                            (isset($user['username']) && $user['username'] == $cleanName) ||
					                            (trim(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')) == $cleanName)) {
					                           $userDetail = $user;
					                           $userId = isset($user['id']) ? $user['id'] : $userId;
					                           break;
					                         }
					                       }
					                     }
					                     
					                     if($userDetail) {
					                       $lastPing = isset($userDetail['last_ping']) ? $userDetail['last_ping'] : null;
					                       $createdAt = isset($userDetail['created_at']) ? $userDetail['created_at'] : null;
					                       
					                       if($lastPing) {
					                         $lastPingTime = strtotime($lastPing);
					                         $isOnline = (time() - $lastPingTime) < 300;
					                       }
					                       
					                       if($createdAt) {
					                         $joinDate = date('M Y', strtotime($createdAt));
					                       }
					                     } else {
					                       $isOnline = false;
					                       
					                       if(isset($value['created_at']) && $value['created_at']) {
					                         $joinDate = date('M Y', strtotime($value['created_at']));
					                       }
					                     }
					                     
					                     $onlineClass = $isOnline ? 'online' : 'offline';
					                     $onlineIcon = $isOnline ? 'fa-circle text-success' : 'fa-circle text-muted';
					                     
					                     $avatarUrl = base_url('assets/template/images/avatar-1.jpg');
					                     
					                     if(strpos($value['name'], '<img') !== false) {
					                       preg_match('/src=["\']([^"\']+)["\']/', $value['name'], $matches);
					                       if(!empty($matches[1])) {
					                         $avatarUrl = $matches[1];
					                       }
					                     } elseif(isset($value['avatar']) && !empty($value['avatar'])) {
					                       $avatarUrl = base_url('assets/images/users/' . $value['avatar']);
					                     }
					                     
					                     $html .= '<li> <span class="tree-user-node ' . $onlineClass . '" data-user-id="' . $userId . '" data-user-display="' . htmlspecialchars($cleanName) . '">';
					                     
					                     $html .= '<div class="tree-user-info">';
					                     $html .= '<div class="tree-user-main">';
					                     $html .= '<i class="fa ' . $onlineIcon . ' tree-online-status" style="font-size: 8px; margin-right: 6px;"></i>';
					                     $html .= htmlspecialchars($cleanName);
					                     $html .= '<img class="user-avtar-tree" src="' . $avatarUrl . '" alt="' . htmlspecialchars($cleanName) . '">';
					                     $html .= '</div>';
					                     
					                     $html .= '<div class="tree-user-details">';
					                     $html .= '<small class="text-muted">';
					                     if($userId) $html .= '#' . $userId;
					                     if($joinDate) {
					                       $html .= ($userId ? ' • ' : '') . $joinDate;
					                     }
					                     $html .= '</small>';
					                     
					                     if($teamSize > 0) {
					                       $badgeClass = 'team-badge-blue';
					                       if($teamSize >= 10) $badgeClass = 'team-badge-green';
					                       if($teamSize >= 25) $badgeClass = 'team-badge-orange';
					                       if($teamSize >= 50) $badgeClass = 'team-badge-red';
					                       
					                       $html .= '<div class="mt-1">';
					                       $html .= '<span class="badge ' . $badgeClass . ' badge-sm">';
					                       $html .= '<i class="fa fa-users" style="font-size: 8px; margin-right: 3px;"></i>' . $teamSize;
					                       $html .= '</span>';
					                       $html .= '</div>';
					                     }
					                     
					                     $html .= '</div>';
					                     $html .= '</div>';
					                     $html .= '</span>';
					                     
					                     if(!empty($value['children'])) {
					                       $t = buildTree($value['children'], $usersDetail);
				                        if($t) $html .= "<ul>{$t}</ul>";
					                     }
				                     $html .= '</li>';
				                   }
				                   return $html;
				                }
				                echo "<div class='user-mlm-pyramid'>";
				                $userDetailsArray = isset($userslistDetail) ? $userslistDetail : array();
				                if(isset($userdetails) && !empty($userdetails)) {
				                  $userDetailsArray[] = $userdetails;
				                }
				                echo "<ul class='usertree'>". buildTree($userslist, $userDetailsArray) ."</ul>";
				                echo "</div>";
				              ?>
							</div>
						</div>
					</div>
				</div>
				        
<style>
.usertree, .usertree ul, .usertree li {
    list-style: none;
    margin: 0;
    padding: 0;
    position: relative;
}

.usertree {
    margin: 0 0 1em;
    text-align: center;
}

.usertree, .usertree ul {
    display: table;
    margin: 0 auto;
}

.usertree ul {
    width: 100%;
}

.usertree li {
    display: table-cell;
    padding: .5em 0;
    vertical-align: top;
}

.usertree li:before {
    outline: solid 2px #e9ecef;
    content: "";
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    border-radius: 1px;
}

.usertree li:first-child:before {
    left: 50%;
}

.usertree li:last-child:before {
    right: 50%;
}

.usertree code, .usertree span, .usertree .tree-user-node {
    border: solid .1em #666;
    border-radius: .5em;
    display: inline-block;
    margin: 0 .2em .5em;
    padding: .4em .8em;
    position: relative;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    font-weight: 500;
}

.usertree span:hover, .usertree .tree-user-node:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,123,255,0.2);
    border-color: #007bff;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.usertree .tree-user-node:hover .tree-user-main,
.usertree .tree-user-node:hover .tree-user-details,
.usertree .tree-user-node:hover .tree-user-details small {
    color: white !important;
}

.usertree ul:before, .usertree code:before, .usertree span:before, .usertree .tree-user-node:before {
    outline: solid 2px #e9ecef;
    content: "";
    height: .5em;
    left: 50%;
    position: absolute;
    border-radius: 1px;
}

.usertree ul:before {
    top: -.5em;
}

.usertree code:before, .usertree span:before, .usertree .tree-user-node:before {
    top: -.55em;
}

.usertree>li {
    margin-top: 0;
}

.usertree>li:before, .usertree>li:after, .usertree>li>code:before, .usertree>li>span:before, .usertree>li>.tree-user-node:before {
    outline: none;
}

.user-avtar-tree {
    width: 40px;
    height: 40px;
    display: block;
    margin: 0 auto;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
    margin-left: 6px;
}

.user-avtar-tree:hover {
    transform: scale(1.1);
}


.tree-user-info {
    display: inline-block;
    text-align: center;
}

.tree-user-main {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2px;
    color: #212529 !important;
    font-weight: 600 !important;
    font-size: 12px !important;
}

.tree-user-details {
    font-size: 10px;
    line-height: 1.2;
    margin-top: 2px;
}

.tree-user-details small {
    color: #495057 !important;
    font-weight: 500;
}

.tree-online-status {
    flex-shrink: 0;
}

.tree-user-node.online .tree-user-main {
    font-weight: 500;
}

.tree-user-node.offline .tree-user-main {
    opacity: 0.8;
}

.user-tree-viewport {
    position: relative;
    width: 100%;
    height: 80vh;
    min-height: 600px;
    overflow: auto;
    border: 1px solid #e9ecef;
    background: #f8f9fa;
    cursor: grab;
}

.user-tree-viewport:active {
    cursor: grabbing;
}

.user-tree-container {
    position: relative;
    width: 300vw;
    min-height: 100vh;
    padding: 20px;
    transform-origin: 0 0;
    transition: transform 0.2s ease;
}

.user-mlm-pyramid {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-width: max-content;
    width: 100%;
    position: relative;
}

.zoom-text {
    font-weight: 600;
    margin-left: 4px;
}

.nav-tabs-custom .nav-link {
    color: #6c757d;
    background-color: transparent;
}

.nav-tabs-custom .nav-link:hover {
    color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.1);
}

.nav-tabs-custom .nav-link.active {
    color: #0d6efd;
    background-color: #fff;
    box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
}

.search-highlight {
    background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 100%) !important;
    border: 3px solid #ff9800 !important;
    border-radius: 8px !important;
    box-shadow: 0 3px 12px rgba(255, 152, 0, 0.4) !important;
    animation: searchPulse 1.5s ease-in-out infinite;
    transform: scale(1.02);
}

@keyframes searchPulse {
    0%, 100% { 
        box-shadow: 0 3px 12px rgba(255, 152, 0, 0.4);
        border-color: #ff9800;
    }
    50% { 
        box-shadow: 0 5px 20px rgba(255, 152, 0, 0.6);
        border-color: #f57c00;
    }
}

.badge-sm {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
    font-weight: 600;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.team-badge-blue {
    background: #1976d2 !important;
    color: white !important;
    border: 1px solid #0d47a1;
}

.team-badge-green {
    background: #388e3c !important;
    color: white !important;
    border: 1px solid #1b5e20;
}

.team-badge-orange {
    background: #f57c00 !important;
    color: white !important;
    border: 1px solid #e65100;
}

.team-badge-red {
    background: #d32f2f !important;
    color: white !important;
    border: 1px solid #b71c1c;
}
</style>

<script>
class UserTreeNavigator {
  constructor() {
    this.viewport = document.getElementById('user-tree-viewport');
    this.container = document.getElementById('user-tree-container');
    this.currentZoom = 1;
    this.minZoom = 0.3;
    this.maxZoom = 2;
    this.isPanning = false;
    this.startX = 0;
    this.startY = 0;
    this.scrollLeft = 0;
    this.scrollTop = 0;
    this.currentUserId = <?= isset($userdetails['id']) ? $userdetails['id'] : 'null' ?>;
    this.currentUsername = <?= isset($userdetails['username']) ? json_encode($userdetails['username']) : 'null' ?>;
    this.currentFullName = <?= isset($userdetails['firstname']) || isset($userdetails['lastname']) ? json_encode(trim(($userdetails['firstname'] ?? '') . ' ' . ($userdetails['lastname'] ?? ''))) : 'null' ?>;
	    
    this.init();
  }
  
  init() {
    if (!this.viewport || !this.container) return;
    
    const startTime = PerformanceMonitor.start('user-performance-indicator');
    
    this.setupEventListeners();
    this.updateTreeStats();
    
    PerformanceMonitor.end(startTime, 'user-performance-indicator');
    
    setTimeout(() => {
      this.centerTree();
      this.autoHighlightMyPosition();
    }, 500);
  }
  
  setupEventListeners() {
    document.getElementById('user-zoom-in')?.addEventListener('click', () => this.zoomIn());
    document.getElementById('user-zoom-out')?.addEventListener('click', () => this.zoomOut());
    document.getElementById('user-zoom-reset')?.addEventListener('click', () => this.resetZoom());
    document.getElementById('user-center-tree')?.addEventListener('click', () => this.centerTree());
    document.getElementById('user-my-position')?.addEventListener('click', () => this.showMyPosition());
    
    document.getElementById('user-tree-search')?.addEventListener('input', (e) => this.handleSearch(e.target.value));
    document.getElementById('user-clear-search')?.addEventListener('click', () => this.clearSearch());
    
    this.viewport.addEventListener('wheel', (e) => {
      if (e.ctrlKey) {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.1 : 0.1;
        this.zoom(this.currentZoom + delta);
      }
    });
    
    this.viewport.addEventListener('mousedown', (e) => {
      if (e.target.closest('.user-tree-node')) return;
      this.startPanning(e);
    });
    
    this.viewport.addEventListener('mousemove', (e) => {
      if (this.isPanning) {
        this.pan(e);
      }
    });
    
    this.viewport.addEventListener('mouseup', () => this.stopPanning());
    this.viewport.addEventListener('mouseleave', () => this.stopPanning());
  }
  
  zoomIn() {
    this.zoom(Math.min(this.currentZoom + 0.2, this.maxZoom));
  }
  
  zoomOut() {
    this.zoom(Math.max(this.currentZoom - 0.2, this.minZoom));
  }
  
  resetZoom() {
    this.zoom(1);
    this.centerTree();
  }
  
  zoom(newZoom) {
    this.currentZoom = Math.max(this.minZoom, Math.min(this.maxZoom, newZoom));
    this.container.style.transform = `scale(${this.currentZoom})`;
    
    const zoomPercent = Math.round(this.currentZoom * 100);
    const zoomText = document.querySelector('#user-zoom-reset .zoom-text');
    if (zoomText) {
      zoomText.textContent = `${zoomPercent}%`;
    }
  }
  
  centerTree() {
    const treeContent = this.container.querySelector('.user-mlm-pyramid');
    if (!treeContent) return;
    
    const contentRect = treeContent.getBoundingClientRect();
    const viewportRect = this.viewport.getBoundingClientRect();
    
    const currentScrollLeft = this.viewport.scrollLeft;
    const currentScrollTop = this.viewport.scrollTop;
    
    const contentLeft = contentRect.left - viewportRect.left;
    const contentTop = contentRect.top - viewportRect.top;
    
    const targetScrollLeft = currentScrollLeft + contentLeft - (viewportRect.width - contentRect.width) / 2;
    const targetScrollTop = currentScrollTop + contentTop - (viewportRect.height - contentRect.height) / 2;
    
    const maxScrollLeft = Math.max(0, this.container.scrollWidth - this.viewport.clientWidth);
    const maxScrollTop = Math.max(0, this.container.scrollHeight - this.viewport.clientHeight);
    
    const finalLeft = Math.max(0, Math.min(targetScrollLeft, maxScrollLeft));
    const finalTop = Math.max(0, Math.min(targetScrollTop, maxScrollTop));
    
    this.viewport.scrollTo({ left: finalLeft, top: finalTop, behavior: 'smooth' });
  }
  
  startPanning(e) {
    this.isPanning = true;
    this.startX = e.pageX - this.viewport.offsetLeft;
    this.startY = e.pageY - this.viewport.offsetTop;
    this.scrollLeft = this.viewport.scrollLeft;
    this.scrollTop = this.viewport.scrollTop;
    this.viewport.style.cursor = 'grabbing';
  }
  
  pan(e) {
    if (!this.isPanning) return;
    e.preventDefault();
    
    const x = e.pageX - this.viewport.offsetLeft;
    const y = e.pageY - this.viewport.offsetTop;
    const walkX = (x - this.startX) * 2;
    const walkY = (y - this.startY) * 2;
    
    this.viewport.scrollLeft = this.scrollLeft - walkX;
    this.viewport.scrollTop = this.scrollTop - walkY;
  }
  
  stopPanning() {
    this.isPanning = false;
    this.viewport.style.cursor = 'grab';
  }
  
  updateTreeStats() {
    const userNodes = this.container.querySelectorAll('.tree-user-node');
    const levels = new Set();
    
    userNodes.forEach(node => {
      let level = 0;
      let parent = node.closest('li');
      if (parent) {
        parent = parent.parentElement;
        while (parent) {
          if (parent.tagName === 'UL' && parent.closest('.usertree')) {
            level++;
          }
          parent = parent.parentElement;
          if (parent && parent.classList && parent.classList.contains('user-mlm-pyramid')) break;
        }
      }
      levels.add(level);
    });
    
    const statsEl = document.getElementById('user-tree-stats');
    if (statsEl) {
      statsEl.textContent = `${userNodes.length} users • ${levels.size} levels`;
    }
  }
  
  handleSearch(searchTerm) {
    const clearBtn = document.getElementById('user-clear-search');
    const resultsEl = document.getElementById('user-search-results');
    
    if (searchTerm.length > 0) {
      clearBtn.style.display = 'block';
    } else {
      clearBtn.style.display = 'none';
      this.clearSearch();
      return;
    }
    
    if (searchTerm.length >= 2) {
      const results = this.searchUsers(searchTerm);
      this.highlightSearchResults(results);
      
      if (results.length > 0) {
        resultsEl.textContent = `${results.length} user${results.length > 1 ? 's' : ''} found`;
        resultsEl.className = 'fw-bold text-success';
        
        if (results[0]) {
          this.scrollToElementSafe(results[0]);
        }
      } else {
        resultsEl.textContent = 'No users found';
        resultsEl.className = 'fw-bold text-warning';
      }
    } else {
      resultsEl.textContent = '';
      this.clearSearchHighlights();
    }
  }
  
  searchUsers(searchTerm) {
    const userNodes = this.container.querySelectorAll('.tree-user-node');
    const results = [];
    const term = searchTerm.toLowerCase();
    
    userNodes.forEach(node => {
      const userName = node.getAttribute('data-user-display') || '';
      const userId = node.getAttribute('data-user-id') || '';
      
      if (userName.toLowerCase().includes(term) || userId.includes(term)) {
        results.push(node);
      }
    });
    
    return results;
  }
  
  highlightSearchResults(results) {
    this.clearSearchHighlights();
    
    results.forEach(node => {
      node.classList.add('search-highlight');
    });
  }
  
  clearSearchHighlights() {
    this.container.querySelectorAll('.tree-user-node.search-highlight').forEach(node => {
      node.classList.remove('search-highlight');
    });
  }
  
  clearSearch() {
    const searchInput = document.getElementById('user-tree-search');
    const clearBtn = document.getElementById('user-clear-search');
    const resultsEl = document.getElementById('user-search-results');
    
    searchInput.value = '';
    clearBtn.style.display = 'none';
    resultsEl.textContent = '';
    this.clearSearchHighlights();
  }
  
  showMyPosition() {
    const myNode = this.findMyNode();
    
    if (myNode) {
      this.clearSearchHighlights();
      
      this.scrollToElementSafe(myNode);
      
      myNode.classList.add('search-highlight');
      
      setTimeout(() => {
        myNode.classList.remove('search-highlight');
      }, 6000);
      
      if (typeof showToast === 'function') {
        showToast('info', '<?= __("user.my_position_highlighted") ?>');
      }
    } else {
      if (typeof showToast === 'function') {
        showToast('warning', '<?= __("user.my_position_not_found") ?>');
      }
    }
  }
  
  autoHighlightMyPosition() {
    const myNode = this.findMyNode();
    
    if (myNode) {
      setTimeout(() => {
        this.scrollToElementSafe(myNode);
        
        myNode.classList.add('search-highlight');
        
        setTimeout(() => {
          myNode.classList.remove('search-highlight');
        }, 5000);
      }, 200);
    }
  }
  
  findMyNode() {
    if (!this.currentUserId && !this.currentUsername && !this.currentFullName) return null;
	    
    const userNodes = this.container.querySelectorAll('.tree-user-node');
	    
    if (this.currentUserId) {
      for (let node of userNodes) {
        const userId = node.getAttribute('data-user-id');
        if (userId == this.currentUserId) {
          return node;
        }
      }
    }
    
    for (let node of userNodes) {
      const display = (node.getAttribute('data-user-display') || '').trim();
      if ((this.currentUsername && display === this.currentUsername) ||
          (this.currentFullName && display === this.currentFullName)) {
        return node;
      }
    }
    
    return null;
  }
  
  scrollToElement(element) {
    this.viewport.style.scrollBehavior = 'auto';
    
    const elementRect = element.getBoundingClientRect();
    const viewportRect = this.viewport.getBoundingClientRect();
    
    const currentLeft = this.viewport.scrollLeft;
    const currentTop = this.viewport.scrollTop;
    
    const targetLeft = currentLeft + (elementRect.left - viewportRect.left) - (viewportRect.width / 2) + (elementRect.width / 2);
    const targetTop = currentTop + (elementRect.top - viewportRect.top) - (viewportRect.height / 2) + (elementRect.height / 2);
    
    this.viewport.scrollLeft = Math.max(0, targetLeft);
    this.viewport.scrollTop = Math.max(0, targetTop);
    
    setTimeout(() => {
      this.viewport.style.scrollBehavior = 'smooth';
    }, 100);
  }
  
  scrollToElementSafe(element) {
    element.scrollIntoView({ 
      behavior: 'smooth', 
      block: 'center', 
      inline: 'center' 
    });
  }
}

function dismissUserTreeHint() {
  document.getElementById('user-tree-navigation-hint').style.display = 'none';
  localStorage.setItem('user-tree-hint-dismissed', 'true');
}

$(document).ready(function(){
  new UserTreeNavigator();
  
  if(localStorage.getItem('user-tree-hint-dismissed') === 'true') {
    document.getElementById('user-tree-navigation-hint').style.display = 'none';
  }
});
</script>

<?= performance_indicator_html('user-performance-indicator') ?>
<?= render_performance_indicator() ?>
					
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
		var hash = $(e.target).attr('href');
		if (history.pushState) {
		    history.pushState(null, null, hash);
		} else {
		    location.hash = hash;
		}
	});

	$(document).ready(function(){
		var hash = window.location.hash;
		if (hash) { $('.nav-link[href="' + hash + '"]').tab('show'); }
	})
</script>
</div>
