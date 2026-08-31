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
.usertree code, .usertree span {
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

.usertree span:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,123,255,0.2);
    border-color: #007bff;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}
.usertree code {
    font-family: monaco, Consolas, 'Lucida Console', monospace;
}
.usertree ul:before, .usertree code:before, .usertree span:before {
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
.usertree code:before, .usertree span:before {
    top: -.55em;
}
.usertree>li {
    margin-top: 0;
}
.usertree>li:before, .usertree>li:after, .usertree>li>code:before, .usertree>li>span:before {
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
}

.user-avtar-tree:hover {
    transform: scale(1.1);
}

/* Tree-specific positioning and user info styling */
.tree-user-node {
    position: relative;
    background: #ffffff !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 6px !important;
    padding: 8px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
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

.user-avtar-tree {
    margin-left: 6px;
}

/* Responsive Tree Viewport */
.tree-viewport {
    position: relative;
    width: 100%;
    height: 80vh;
    min-height: 600px;
    overflow: auto;
    border: 1px solid #e9ecef;
    background: #f8f9fa;
    cursor: grab;
    touch-action: none;
}

.tree-viewport * {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.tree-viewport:active {
    cursor: grabbing;
}

.tree-container {
    position: relative;
    width: 300vw;
    min-height: 100vh;
    padding: 20px;
    transform-origin: 0 0;
    transition: transform 0.2s ease;
}

.mlm-pyramid {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-width: max-content;
    width: 100%;
    position: relative;
}



/* Zoom Controls Styling */
#zoom-reset {
    min-width: 70px;
}

.zoom-text {
    font-weight: 600;
    margin-left: 4px;
}

/* Header Text Visibility */
.card-header .text-white {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

/* Tree Stats Visibility */
#tree-stats {
    font-size: 0.9rem !important;
    color: #ffffff !important;
}

/* Navigation Hint Visibility */
#tree-navigation-hint {
    font-size: 0.8rem !important;
    color: #ffffff !important;
}

.card-header .btn-outline-light {
    border-color: rgba(255,255,255,0.8);
    color: white;
}

.card-header .btn-outline-light:hover {
    background-color: rgba(255,255,255,0.2);
    border-color: white;
}

/* Tree Statistics */
#tree-stats {
    font-size: 0.85rem;
}

/* Admin Highlight Effect */
.admin-highlight {
    animation: adminPulse 2s ease-in-out 3;
    box-shadow: 0 0 20px rgba(255, 193, 7, 0.8) !important;
    border: 3px solid #ffc107 !important;
    border-radius: 8px !important;
}

/* Auto Admin Highlight (Subtle) */
.admin-auto-highlight {
    animation: adminAutoFade 4s ease-out forwards;
    box-shadow: 0 0 15px rgba(76, 175, 80, 0.6) !important;
    border: 2px solid #4caf50 !important;
    border-radius: 8px !important;
}

@keyframes adminPulse {
    0% { 
        box-shadow: 0 0 20px rgba(255, 193, 7, 0.8);
        transform: scale(1);
    }
    50% { 
        box-shadow: 0 0 30px rgba(255, 193, 7, 1);
        transform: scale(1.05);
    }
    100% { 
        box-shadow: 0 0 20px rgba(255, 193, 7, 0.8);
        transform: scale(1);
    }
}

@keyframes adminAutoFade {
    0% { 
        box-shadow: 0 0 15px rgba(76, 175, 80, 0.8);
        border-color: #4caf50;
        transform: scale(1.02);
    }
    25% { 
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.9);
        border-color: #388e3c;
    }
    100% { 
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border-color: #dee2e6;
        transform: scale(1);
    }
}

/* Search Highlight Effect */
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

/* Team Size Badge Styling */
.badge-sm {
    font-size: 0.65rem;
    padding: 0.25em 0.5em;
    font-weight: 600;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

/* Team Badge Colors - High Contrast */
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .tree-viewport {
        height: 60vh;
        min-height: 400px;
    }
    
    .card-header .btn-group {
        margin-top: 10px;
    }

}

</style>

<!-- MLM Tree Content inside proper theme container -->
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card shadow-lg border-0" style="border-radius: 1rem; overflow: hidden;">
        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
          <!-- Top Row: Title and Controls -->
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="m-0 text-white">
              <i class="fas fa-sitemap me-2"></i><?= __('admin.menu_referring_tree') ?>
              <small class="text-white fw-bold ms-2" id="tree-stats" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);"></small>
            </h3>
            
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-sm btn-dark" id="zoom-out" title="<?= __('admin.zoom_out') ?>">
                <i class="fa fa-search-minus"></i>
              </button>
              <button type="button" class="btn btn-sm btn-dark" id="zoom-reset" title="<?= __('admin.reset_zoom') ?>">
                <i class="fa fa-search"></i> <span class="zoom-text">100%</span>
              </button>
              <button type="button" class="btn btn-sm btn-dark" id="zoom-in" title="<?= __('admin.zoom_in') ?>">
                <i class="fa fa-search-plus"></i>
              </button>
              <button type="button" class="btn btn-sm btn-secondary ms-2" id="center-tree">
                <i class="fa fa-crosshairs"></i> <?= __('admin.center_view') ?>
              </button>
              <button type="button" class="btn btn-sm btn-warning ms-1" id="admin-view">
                <i class="fa fa-user-shield"></i> <?= __('admin.admin_view') ?>
              </button>
            </div>
          </div>
          
          <!-- Bottom Row: Search and Navigation -->
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">
                  <i class="fa fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control" id="tree-search" placeholder="<?= __('admin.search_users_in_tree') ?>" autocomplete="off">
                <button class="btn btn-outline-secondary" type="button" id="clear-search" style="display: none;">
                  <i class="fa fa-times"></i>
                </button>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center justify-content-end">
                <small class="text-white fw-bold me-3" id="search-results"></small>
                <small class="text-white fw-bold me-2" id="tree-navigation-hint" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8); background: rgba(0,0,0,0.2); padding: 4px 8px; border-radius: 4px;">
                  <i class="fa fa-info-circle me-1"></i>
                  <strong><?= __('admin.tree_navigation_tip') ?>:</strong>
                  <?= __('admin.use_mouse_wheel_zoom') ?> • <?= __('admin.drag_to_pan') ?> • <?= __('admin.drag_users_to_change_hierarchy') ?>
                </small>
                <button type="button" class="btn btn-sm btn-outline-light p-1 ms-2" onclick="dismissTreeHint()" title="<?= __('admin.close') ?>">
                  <i class="fa fa-times"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body p-0">
            
          <!-- Scrollable Tree Viewport -->
          <div class="tree-viewport" id="tree-viewport">
            <div class="tree-container" id="tree-container">
            <?php 
                function buildTree($data, $usersDetail = array()){
                   $html = '';
                   foreach ($data as $key => $value) {
                     // Extract user ID from the tree data
                     $userId = isset($value['id']) ? $value['id'] : null;
                     
                     // Find user details for avatar and other info
                     $userDetail = null;
                     if($userId) {
                       foreach($usersDetail as $user) {
                         if($user['id'] == $userId) {
                           $userDetail = $user;
                           break;
                         }
                       }
                     }
                     
                     // Get avatar URL - check both user details and tree data
                     $avatarUrl = base_url('assets/template/images/avatar-1.jpg'); // default
                     if($userDetail && !empty($userDetail['avatar'])) {
                       $avatarUrl = base_url('assets/images/users/' . $userDetail['avatar']);
                     } elseif(!empty($value['avatar'])) {
                       // Check if avatar is in the tree data itself (for admin user)
                       $avatarUrl = base_url('assets/images/users/' . $value['avatar']);
                     }
                     
                     // Calculate online status (last 7 days) and team size
                     $isOnline = false;
                     $joinDate = '';
                     $teamSize = 0;
                     
                     if($userDetail) {
                       $lastPing = isset($userDetail['last_ping']) ? $userDetail['last_ping'] : null;
                       $createdAt = isset($userDetail['created_at']) ? $userDetail['created_at'] : null;
                       
                       if($lastPing) {
                         $lastPingTime = strtotime($lastPing);
                         $weekAgo = strtotime('-7 days');
                         $isOnline = ($lastPingTime > $weekAgo);
                       }
                       
                       if($createdAt) {
                         $joinDate = date('M Y', strtotime($createdAt));
                       }
                     }
                     
                     // Calculate team size (direct children count)
                     $teamSize = !empty($value['children']) ? count($value['children']) : 0;
                     
                     $onlineClass = $isOnline ? 'online' : 'offline';
                     $onlineIcon = $isOnline ? 'fa-circle text-success' : 'fa-circle text-muted';
                     
                     $html .= '<li> <span draggable="true" data-user-id="' . $userId . '" data-user-display="' . htmlspecialchars($value['name']) . '" onclick="showUserModal(' . $userId . ')" style="cursor: pointer;" class="tree-user-node ' . $onlineClass . '">';
                     $html .= '<i class="fa fa-grip-vertical me-2 tree-drag-handle" style="font-size: 12px; color: #ccc;"></i>';
                     
                     // User name and avatar
                     $html .= '<div class="tree-user-info">';
                     $html .= '<div class="tree-user-main">';
                     $html .= '<i class="fa ' . $onlineIcon . ' tree-online-status" style="font-size: 8px; margin-right: 6px;"></i>';
                     $html .= htmlspecialchars($value['name']);
                     $defaultAvatar = base_url('assets/template/images/avatar-1.jpg');
                     $html .= "<img class='user-avtar-tree' src='" . $avatarUrl . "' onerror=\"this.onerror=null;this.src='" . $defaultAvatar . "'\">";
                  $html .= '</div>';
                     
                     // User details (ID, join date, and team size)
                     $html .= '<div class="tree-user-details">';
                     $html .= '<small class="text-muted">';
                     $html .= '#' . $userId;
                     if($joinDate) {
                       $html .= ' • ' . $joinDate;
                     }
                     $html .= '</small>';
                     
                     // Team size badge
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
                echo "<div class='mlm-pyramid'>";
                echo "<ul class='usertree'>". buildTree($userslist, $userslistDetail) ."</ul>";
                echo "</div>";
            ?>
            </div>
          </div>
          

          
        </div>
      </div>
    </div>
  </div>
</div>

<!-- User Detail Modal -->
<div class="modal fade" id="userTreeModal" tabindex="-1" aria-labelledby="userTreeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userTreeModalLabel">
          <i class="fas fa-user me-2"></i><?= __('admin.user_details') ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-md-3 text-center">
            <div id="userModalAvatar" class="mb-3">
              <img src="<?= base_url('assets/template/images/avatar-1.jpg') ?>" alt="User Avatar" class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.onerror=null;this.src='<?= base_url('assets/template/images/avatar-1.jpg') ?>'">
            </div>
          </div>
          <div class="col-md-9">
            <div class="table-responsive">
              <table class="table table-bordered">
                <tbody id="userModalDetails">
                  <!-- User details will be populated here -->
                </tbody>
              </table>
            </div>
          </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i><?= __('admin.close') ?>
        </button>
        <a id="userModalEditLink" href="#" class="btn btn-primary">
          <i class="fas fa-edit me-1"></i><?= __('admin.edit_user') ?>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Include shared drag and drop functionality -->
<script src="<?= base_url('assets/template/js/admin-drag-drop.js'); ?>?v=<?= av() ?>"></script>

<?= render_performance_indicator() ?>

<script>
// User details data for quick lookup
const usersData = <?= json_encode($userslistDetail) ?>;

function showUserModal(userId) {
  if (!userId) return;
  
  // Find user in the data
  const user = usersData.find(u => u.id == userId);
  if (!user) {
    alert('<?= __("admin.user_not_found") ?>');
    return;
  }
  

  
  // Set avatar
  const avatarUrl = user.avatar ? 
    '<?= base_url("assets/images/users/") ?>' + user.avatar : 
    '<?= base_url("assets/template/images/avatar-1.jpg") ?>';
  const $modalImg = $('#userModalAvatar img');
  $modalImg.off('error').on('error', function() {
    this.onerror = null;
    this.src = '<?= base_url("assets/template/images/avatar-1.jpg") ?>';
  }).attr('src', avatarUrl);
  
  // Set modal title
  $('#userTreeModalLabel').html('<i class="fas fa-user me-2"></i>' + user.firstname + ' ' + user.lastname);
  
  // Build user details table
  let detailsHtml = '';
  
  detailsHtml += '<tr><td><strong><?= __("admin.username") ?></strong></td><td>' + (user.username || '-') + '</td></tr>';
  detailsHtml += '<tr><td><strong><?= __("admin.name") ?></strong></td><td>' + user.firstname + ' ' + user.lastname + '</td></tr>';
  detailsHtml += '<tr><td><strong><?= __("admin.email") ?></strong></td><td>' + (user.email || '-') + '</td></tr>';
  detailsHtml += '<tr><td><strong><?= __("admin.phone") ?></strong></td><td>' + (user.phone || '-') + '</td></tr>';
  // Get country name - could be in different fields depending on query
  let countryName = '-';
  if (user.name && user.sortname) {
    // If we have both name and sortname, then name is likely the country name
    countryName = user.name;
  } else if (user.country_name) {
    countryName = user.country_name;
  } else if (user.Country && typeof user.Country === 'string' && user.Country !== '') {
    countryName = user.Country;
  }
  detailsHtml += '<tr><td><strong><?= __("admin.country") ?></strong></td><td>' + countryName + '</td></tr>';
  // Format date if available
  let registrationDate = user.created_at || user.register_at || '-';
  if (registrationDate !== '-' && registrationDate) {
    const date = new Date(registrationDate);
    registrationDate = date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  }
  detailsHtml += '<tr><td><strong><?= __("admin.registration_date") ?></strong></td><td>' + registrationDate + '</td></tr>';
  
  if (user.reg_approved !== null) {
    const approvalStatus = user.reg_approved == 1 ? 
      '<span class="badge bg-success"><?= __("admin.approved") ?></span>' : 
      '<span class="badge bg-warning"><?= __("admin.pending") ?></span>';
    detailsHtml += '<tr><td><strong><?= __("admin.status") ?></strong></td><td>' + approvalStatus + '</td></tr>';
  }
  
  if (user.under_affiliate) {
    detailsHtml += '<tr><td><strong><?= __("admin.under_affiliate") ?></strong></td><td>' + user.under_affiliate + '</td></tr>';
  }
  
  $('#userModalDetails').html(detailsHtml);
  
  // Set edit link
  $('#userModalEditLink').attr('href', '<?= base_url("admincontrol/addusers/") ?>' + userId);
  
  // Show modal
  $('#userTreeModal').modal('show');
}

// Set up configuration and translations for shared drag and drop
window.AdminDragDropConfig = {
  baseUrl: '<?= base_url() ?>'
};

window.AdminDragDropTranslations = {
  confirm_hierarchy_change: '<?= __("admin.confirm_hierarchy_change") ?>',
  hierarchy_change_warning: '<?= __("admin.hierarchy_change_warning") ?>',
  move_user_under_new_parent: '<?= __("admin.move_user_under_new_parent") ?>',
  user_to_move: '<?= __("admin.user_to_move") ?>',
  new_parent: '<?= __("admin.new_parent") ?>',
  cancel: '<?= __("admin.cancel") ?>',
  confirm_change: '<?= __("admin.confirm_change") ?>',
  success: '<?= __("admin.success") ?>',
  error: '<?= __("admin.error") ?>',
  hierarchy_changed_successfully: '<?= __("admin.hierarchy_changed_successfully") ?>',
  hierarchy_change_failed: '<?= __("admin.hierarchy_change_failed") ?>'
};

// Custom AJAX handler for tree (uses same endpoint as users list)
AdminDragDrop.executeHierarchyChange = function(childId, newParentId) {
  const modal = bootstrap.Modal.getInstance(document.getElementById('dndConfirmModal'));
  modal.hide();
  
  const translations = window.AdminDragDropTranslations || {};
  
  $.ajax({
    url: '<?= base_url("admincontrol/users_change_parent") ?>',
    type: 'POST',
    data: {
      child_id: childId,
      new_parent_id: newParentId
    },
    success: function(response) {
      if(response.success) {
        if(typeof showToast === 'function') {
          showToast(translations.success, translations.hierarchy_changed_successfully, 'success', 3000);
        }
        // Reload the tree
        setTimeout(function() {
          location.reload();
        }, 1500);
              } else {
        if(typeof showToast === 'function') {
          showToast(translations.error, response.message || translations.hierarchy_change_failed, 'error', 5000);
        }
      }
    },
    error: function() {
      if(typeof showToast === 'function') {
        showToast(translations.error, translations.hierarchy_change_failed, 'error', 5000);
      }
    }
  });
};

// Tree Navigation and Zoom functionality
class TreeNavigator {
  constructor() {
    this.viewport = document.getElementById('tree-viewport');
    this.container = document.getElementById('tree-container');
    this.currentZoom = 1;
    this.minZoom = 0.3;
    this.maxZoom = 2;
    this.isPanning = false;
    this.startX = 0;
    this.startY = 0;
    this.scrollLeft = 0;
    this.scrollTop = 0;
    
    this.init();
  }
  
  init() {
    if (!this.viewport || !this.container) {
      console.error('Tree viewport or container not found');
      return;
    }
    
    this.viewport.style.cursor = 'grab';
    
    const startTime = PerformanceMonitor.start('performance-indicator');
    
    this.setupEventListeners();
    this.updateTreeStats();
    
    PerformanceMonitor.end(startTime, 'performance-indicator');
    
    setTimeout(() => {
      this.showAdminView();
    }, 1000);
  }


  
  setupEventListeners() {
    // Zoom controls
    document.getElementById('zoom-in')?.addEventListener('click', () => this.zoomIn());
    document.getElementById('zoom-out')?.addEventListener('click', () => this.zoomOut());
    document.getElementById('zoom-reset')?.addEventListener('click', () => this.resetZoom());
    document.getElementById('center-tree')?.addEventListener('click', () => this.centerTree());
    document.getElementById('admin-view')?.addEventListener('click', () => this.showAdminView());
    
    // Search functionality
    document.getElementById('tree-search')?.addEventListener('input', (e) => this.handleSearch(e.target.value));
    document.getElementById('clear-search')?.addEventListener('click', () => this.clearSearch());
    
    // Mouse wheel zoom
    this.viewport.addEventListener('wheel', (e) => {
      if (e.ctrlKey) {
        e.preventDefault();
        const delta = e.deltaY > 0 ? -0.1 : 0.1;
        this.zoom(this.currentZoom + delta);
      } else if (e.shiftKey) {
        // Hold Shift to scroll horizontally with wheel
        e.preventDefault();
        const maxLeft = this.viewport.scrollWidth - this.viewport.clientWidth;
        this.viewport.scrollLeft = Math.min(Math.max(0, this.viewport.scrollLeft + (e.deltaY > 0 ? 80 : -80)), Math.max(0, maxLeft));
      }
    });
    
    // Pan functionality - allow panning from anywhere
    this.viewport.addEventListener('mousedown', (e) => {
      // Only prevent panning on user nodes with onclick handlers
      if (e.target.closest('.tree-user-node[onclick]') || e.target.closest('button') || e.target.closest('a')) {
        return;
      }
      
      e.preventDefault();
      e.stopPropagation();
      this.startPanning(e);
    });
    
    document.addEventListener('mousemove', (e) => {
      if (this.isPanning) {
        e.preventDefault();
        
        const deltaX = (this.lastMoveX - e.clientX) * 1.5 / (this.currentZoom || 1);
        const deltaY = (this.lastMoveY - e.clientY) * 1.5 / (this.currentZoom || 1);
        
        this.lastMoveX = e.clientX;
        this.lastMoveY = e.clientY;
        
        const maxLeft = this.viewport.scrollWidth - this.viewport.clientWidth;
        const maxTop = this.viewport.scrollHeight - this.viewport.clientHeight;
        const nextLeft = Math.min(Math.max(0, this.viewport.scrollLeft + deltaX), Math.max(0, maxLeft));
        const nextTop = Math.min(Math.max(0, this.viewport.scrollTop + deltaY), Math.max(0, maxTop));
        
        this.viewport.scrollLeft = nextLeft;
        this.viewport.scrollTop = nextTop;
      }
    });
    
    document.addEventListener('mouseup', (e) => {
      if (this.isPanning) {
        e.preventDefault();
        this.stopPanning();
      }
    });
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
    
    // Update zoom display
    const zoomPercent = Math.round(this.currentZoom * 100);
    const zoomText = document.querySelector('#zoom-reset .zoom-text');
    if (zoomText) {
      zoomText.textContent = `${zoomPercent}%`;
              } else {
      document.getElementById('zoom-reset').innerHTML = `<i class="fa fa-search"></i> <span class="zoom-text">${zoomPercent}%</span>`;
    }
  }
  
  centerTree() {
    const treeContent = this.container.querySelector('.mlm-pyramid');
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
    this.startX = e.clientX;
    this.startY = e.clientY;
    this.scrollLeft = this.viewport.scrollLeft;
    this.scrollTop = this.viewport.scrollTop;
    this.viewport.style.cursor = 'grabbing';
    
    this.viewport.setAttribute('data-panning', 'true');
    
    this.lastMoveX = e.clientX;
    this.lastMoveY = e.clientY;
    

  }
  
  pan(e) {
    if (!this.isPanning) return;
    e.preventDefault();
    
    const x = e.clientX;
    const y = e.clientY;
    const walkX = (this.startX - x) * 1.5 / (this.currentZoom || 1);
    const walkY = (this.startY - y) * 1.5 / (this.currentZoom || 1);
    
    const maxLeft = this.viewport.scrollWidth - this.viewport.clientWidth;
    const maxTop = this.viewport.scrollHeight - this.viewport.clientHeight;
    
    const newScrollLeft = Math.min(Math.max(0, this.scrollLeft + walkX), Math.max(0, maxLeft));
    const newScrollTop = Math.min(Math.max(0, this.scrollTop + walkY), Math.max(0, maxTop));
    
    this.viewport.scrollLeft = newScrollLeft;
    this.viewport.scrollTop = newScrollTop;
    

  }
  
  stopPanning() {
    this.isPanning = false;
    this.viewport.style.cursor = 'grab';
    this.viewport.removeAttribute('data-panning');
  }
  
  updateTreeStats() {
    const userNodes = this.container.querySelectorAll('.tree-user-node');
    const levels = new Set();
    
    userNodes.forEach(node => {
      let level = 0;
      let parent = node.closest('li').parentElement;
      while (parent && parent.classList.contains('usertree')) {
        if (parent.tagName === 'UL') level++;
        parent = parent.parentElement;
      }
      levels.add(level);
    });
    
    const statsEl = document.getElementById('tree-stats');
    if (statsEl) {
      statsEl.textContent = `${userNodes.length} users • ${levels.size} levels`;
    }
  }
  
      showAdminView() {
    // Find admin node (user with type 'admin' or the root user)
    const adminNode = this.findAdminNode();
    
    if (adminNode) {
      // Remove any existing highlights
      this.container.querySelectorAll('.admin-highlight').forEach(el => {
        el.classList.remove('admin-highlight');
      });
      
      this.scrollToElementSafe(adminNode);
      
      // Highlight admin node
      adminNode.classList.add('admin-highlight');
      
      // Remove highlight after animation completes
      setTimeout(() => {
        adminNode.classList.remove('admin-highlight');
      }, 6000);
      
      // Show toast notification
      if (typeof showToast === 'function') {
        showToast('<?= __("admin.admin_view") ?>', '<?= __("admin.admin_position_highlighted") ?>', 'info', 3000);
                }
              } else {
      // Admin not found in tree
      if (typeof showToast === 'function') {
        showToast('<?= __("admin.admin_view") ?>', '<?= __("admin.admin_not_found_in_tree") ?>', 'warning', 3000);
      }
    }
  }
  
  findAdminNode() {
    // Try to find admin by looking for user ID 1 (usually admin) or by checking data attributes
    const userNodes = this.container.querySelectorAll('.tree-user-node');
    
    // First try to find user with ID 1 (typical admin ID)
    for (let node of userNodes) {
      const userId = node.getAttribute('data-user-id');
      if (userId === '1') {
        return node;
      }
    }
    
    // If not found, try to find by looking for admin-related text or root position
    // Check if the first user in the tree is admin (root position)
    const firstUser = this.container.querySelector('.usertree > li .tree-user-node');
    if (firstUser) {
      return firstUser;
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
  
  handleSearch(searchTerm) {
    const clearBtn = document.getElementById('clear-search');
    const resultsEl = document.getElementById('search-results');
    
    // Show/hide clear button
    if (searchTerm.length > 0) {
      clearBtn.style.display = 'block';
                } else {
      clearBtn.style.display = 'none';
      this.clearSearch();
      return;
    }
    
    // Perform search
    if (searchTerm.length >= 2) {
      const results = this.searchUsers(searchTerm);
      this.highlightSearchResults(results);
      
      // Update results display
      if (results.length > 0) {
        resultsEl.textContent = `${results.length} user${results.length > 1 ? 's' : ''} found`;
        resultsEl.className = 'text-success';
        
        // Scroll to first result
        if (results[0]) {
          this.scrollToElement(results[0]);
              }
            } else {
        resultsEl.textContent = 'No users found';
        resultsEl.className = 'text-warning';
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
    // Clear previous highlights
    this.clearSearchHighlights();
    
    // Add search highlight to results
    results.forEach(node => {
      node.classList.add('search-highlight');
    });
  }
  
  clearSearchHighlights() {
    this.container.querySelectorAll('.search-highlight').forEach(node => {
      node.classList.remove('search-highlight');
    });
  }
  
  clearSearch() {
    const searchInput = document.getElementById('tree-search');
    const clearBtn = document.getElementById('clear-search');
    const resultsEl = document.getElementById('search-results');
    
    searchInput.value = '';
    clearBtn.style.display = 'none';
    resultsEl.textContent = '';
    this.clearSearchHighlights();
  }
  
  autoHighlightAdmin() {
    // Find admin node
    const adminNode = this.findAdminNode();
    
    if (adminNode) {
      // Scroll to admin position smoothly
      this.scrollToElement(adminNode);
      
      // Add a subtle highlight that fades out
      adminNode.classList.add('admin-auto-highlight');
      
      // Remove highlight after 4 seconds
      setTimeout(() => {
        adminNode.classList.remove('admin-auto-highlight');
      }, 4000);
      
      // Show subtle toast notification
      if (typeof showToast === 'function') {
        showToast('<?= __("admin.admin_view") ?>', '<?= __("admin.admin_auto_located") ?>', 'info', 2000);
      }
    }
  }
  

}

// Dismiss tree navigation hint
function dismissTreeHint() {
  document.getElementById('tree-navigation-hint').style.display = 'none';
  // Save preference in localStorage
  localStorage.setItem('tree-hint-dismissed', 'true');
}

// Initialize drag and drop when document is ready
$(document).ready(function(){
  // Initialize tree navigation FIRST
  new TreeNavigator();
  
  // Initialize tree drag and drop using shared function AFTER navigation
  if(typeof AdminDragDrop !== 'undefined') {
    // Delay drag and drop initialization to ensure panning works first
    setTimeout(() => {
      AdminDragDrop.initTreeDragDrop();
      AdminDragDrop.autoDismissDragHint('tree-drag-hint', 10000);
    }, 500);
  }
  
  // Auto-hide hint if previously dismissed
  if(localStorage.getItem('tree-hint-dismissed') === 'true') {
    document.getElementById('tree-navigation-hint').style.display = 'none';
  }
});
</script>

<?= performance_indicator_html('performance-indicator') ?>