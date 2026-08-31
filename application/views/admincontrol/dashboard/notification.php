<div class="container-fluid notification-page">
    <div class="row">
        <div class="col-12">

            <!-- Header Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <i class="bi bi-bell-fill me-2 fs-4"></i>
                            <div>
                                <h4 class="mb-0 fw-bold"><?= __('admin.all_notification') ?></h4>
                                <small class="opacity-75"><?= __('admin.manage_system_notifications') ?></small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-outline-light btn-sm" onclick="refreshNotifications()" title="<?= __('admin.refresh') ?>">
                                <i class="bi bi-arrow-clockwise me-1"></i><?= __('admin.refresh') ?>
                            </button>
                            <button class="btn btn-outline-light btn-sm" onclick="selectAllNotifications()" title="<?= __('admin.select_all') ?>">
                                <i class="bi bi-check-square me-1"></i><?= __('admin.select_all') ?>
                            </button>
                            <button class="btn btn-outline-light btn-sm delete-selected" title="<?= __('admin.delete_selected') ?>">
                                <i class="bi bi-trash me-1"></i><?= __('admin.delete_selected') ?>
                            </button>
                            <a href="<?= base_url('admincontrol/notification?clearall=1') ?>" class="btn btn-danger btn-sm clear_notification" title="<?= __('admin.clear_notification') ?>">
                                <i class="bi bi-x-circle me-1"></i><?= __('admin.clear_notification') ?>
                            </a>
                        </div>
		</div>
	</div>

                <!-- Stats Summary -->
                <div class="card-body bg-light border-bottom">
                    <div class="row g-3" id="notification-stats">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-bell text-info fs-5"></i>
                                </div>
                                <div>
                                    <div class="text-muted small"><?= __('admin.total_notifications') ?></div>
                                    <div class="fw-bold fs-5" id="total-notifications"><?= isset($notification['total']) && is_numeric($notification['total']) ? $notification['total'] : (is_array($notifications) ? count($notifications) : 0) ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-eye text-success fs-5"></i>
                                </div>
                                <div>
                                    <div class="text-muted small"><?= __('admin.unread_notifications') ?></div>
                                    <div class="fw-bold fs-5" id="unread-notifications">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-cart-check text-warning fs-5"></i>
                                </div>
                                <div>
                                    <div class="text-muted small"><?= __('admin.order_notifications') ?></div>
                                    <div class="fw-bold fs-5" id="order-notifications">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-person-plus text-primary fs-5"></i>
                                </div>
                                <div>
                                    <div class="text-muted small"><?= __('admin.user_notifications') ?></div>
                                    <div class="fw-bold fs-5" id="user-notifications">-</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <h6 class="mb-0 fw-semibold text-muted">
                                <i class="bi bi-list-ul me-2"></i><?= __('admin.notifications_list') ?>
                            </h6>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="selectAllSwitch">
                                <label class="form-check-label small" for="selectAllSwitch"><?= __('admin.select_all') ?></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <?php if($notifications == null || empty($notifications)) { ?>
                        <!-- Empty State -->
                        <div class="notification-empty-state">
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                                <h5 class="text-muted mt-3"><?= __('admin.no_notifications_found') ?></h5>
                                <p class="text-muted mb-0"><?= __('admin.no_notifications_message') ?></p>
				 </div>
				</div>
                <?php } else { ?>
                        <!-- Notifications Table -->
			<div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50" class="border-0">
                                            <div class="form-check">
                                                <input class="form-check-input select_all" type="checkbox" id="selectAllCheckbox">
                                                <label class="form-check-label" for="selectAllCheckbox"></label>
								</div>
                                        </th>
                                        <th class="border-0"><?= __('admin.notification') ?></th>
                                        <th width="120" class="border-0 text-center"><?= __('admin.type') ?></th>
                                        <th width="100" class="border-0 text-center"><?= __('admin.actions') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($notifications as $key => $notification) { ?>
                                        <tr class="notification-row" data-id="<?= $notification['notification_id'] ?>">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input notification_id" type="checkbox" value="<?= $notification['notification_id'] ?>" name="notification[]" id="notif_<?= $notification['notification_id'] ?>">
                                                    <label class="form-check-label" for="notif_<?= $notification['notification_id'] ?>"></label>
									</div>
								</td>
								<td>
                                                <div class="d-flex align-items-start">
                                                    <div class="notification-icon me-3">
                                                        <?php
                                                        $iconClass = 'bi-bell';
                                                        $iconColor = 'text-primary';
                                                        if(strpos($notification['notification_title'], 'Subscription') !== false || $notification['notification_type'] === 'membership_order') {
                                                            $iconClass = 'bi-star';
                                                            $iconColor = 'text-dark';
                                                        } elseif(strpos($notification['notification_title'], 'Order') !== false) {
                                                            $iconClass = 'bi-cart-check';
                                                            $iconColor = 'text-success';
                                                        } elseif(strpos($notification['notification_title'], 'User') !== false || strpos($notification['notification_title'], 'Register') !== false) {
                                                            $iconClass = 'bi-person-plus';
                                                            $iconColor = 'text-info';
                                                        } elseif(strpos($notification['notification_title'], 'Payment') !== false) {
                                                            $iconClass = 'bi-credit-card';
                                                            $iconColor = 'text-warning';
                                                        }
                                                        ?>
                                                        <i class="bi <?= $iconClass ?> <?= $iconColor ?> fs-5"></i>
                                                    </div>
                                                    <div class="notification-content flex-grow-1">
                                                        <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($notification['notification_title']) ?></h6>
                                                        <p class="mb-1 text-muted small"><?= htmlspecialchars($notification['notification_description']) ?></p>
                                                        <small class="text-muted">
                                                            <i class="bi bi-clock me-1"></i><?= date('M d, Y H:i', strtotime($notification['notification_created_date'])) ?>
		                        	</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $badgeClass = 'bg-primary';
                                                $badgeText = __('admin.notification_type_general');
                                                if(strpos($notification['notification_title'], 'Subscription') !== false || $notification['notification_type'] === 'membership_order') {
                                                    $badgeClass = 'bg-dark';
                                                    $badgeText = __('admin.notification_type_membership');
                                                } elseif(strpos($notification['notification_title'], 'Order') !== false) {
                                                    $badgeClass = 'bg-success';
                                                    $badgeText = __('admin.notification_type_order');
                                                } elseif(strpos($notification['notification_title'], 'User') !== false || strpos($notification['notification_title'], 'Register') !== false) {
                                                    $badgeClass = 'bg-info';
                                                    $badgeText = __('admin.notification_type_user');
                                                } elseif(strpos($notification['notification_title'], 'Payment') !== false) {
                                                    $badgeClass = 'bg-warning';
                                                    $badgeText = __('admin.notification_type_payment');
                                                }
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $badgeText ?></span>
							</td>
                                            <td class="text-center">
                                                <?php
                                                $notif_url = $notification['notification_url'];
                                                if (strpos($notif_url, '/membership/') !== false) {
                                                    $full_url = base_url() . ltrim($notif_url, '/');
                                                } else {
                                                    $full_url = base_url('admincontrol') . (strpos($notif_url, '/') === 0 ? '' : '/') . $notif_url;
                                                }
                                                ?>
                                                <button class="btn btn-primary btn-sm" onclick="viewNotification(<?= $notification['notification_id'] ?>, '<?= $full_url ?>')" title="<?= __('admin.details') ?>">
                                                    <i class="bi bi-eye me-1"></i><?= __('admin.details') ?>
                                                </button>
							</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } ?>
	</div>

                <!-- Pagination Footer -->
                <?php if($notifications != null && !empty($notifications)) { ?>
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                            <div class="mb-2 mb-md-0">
                                <?php 
                                $settings = get_pagination_settings();
                                $current_page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
                                $total = isset($notification['total']) && is_numeric($notification['total']) ? $notification['total'] : (is_array($notifications) ? count($notifications) : 0);
                                echo pagination_summary_html($current_page + 1, $settings['per_page'], $total);
                                ?>
                            </div>
                            <div>
                                <?php 
                                $settings_for_pagination = get_pagination_settings();
                                $per_page = $settings_for_pagination['per_page'];
                                $total_pages = ceil($total / $per_page);
                                
                                if ($total_pages <= 1) {
                                    // Show single page pagination
                                    echo '<ul class="pagination pagination-sm justify-content-center">';
                                    echo '<li class="page-item active"><span class="page-link">1</span></li>';
                                    echo '</ul>';
                                } else {
                                    // Show multi-page pagination
                                    $pagination = easy_pagination(base_url('admincontrol/notification'), $total, $current_page);
                                    echo isset($pagination['html']) ? $pagination['html'] : '';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Notification Details Modal -->
<div class="modal fade" id="notificationDetailsModal" tabindex="-1" aria-labelledby="notificationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="notificationDetailsModalLabel">
                    <i class="bi bi-bell me-2"></i><?= __('admin.notification_details') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"><?= __('admin.loading') ?>...</span>
                    </div>
                    <p class="text-muted mt-2"><?= __('admin.loading_notification_details') ?>...</p>
                </div>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    // Select all functionality
    $('#selectAllCheckbox, #selectAllSwitch').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('.notification_id').prop('checked', isChecked);
        $('#selectAllCheckbox, #selectAllSwitch').prop('checked', isChecked);
    });

    // Individual checkbox change
    $('.notification_id').on('change', function() {
        var totalCheckboxes = $('.notification_id').length;
        var checkedCheckboxes = $('.notification_id:checked').length;
        
        if(checkedCheckboxes === totalCheckboxes) {
            $('#selectAllCheckbox, #selectAllSwitch').prop('checked', true);
        } else {
            $('#selectAllCheckbox, #selectAllSwitch').prop('checked', false);
        }
    });

    // Delete selected notifications
    $('.delete-selected').on('click', function() {
        var selectedIds = [];
        $('.notification_id:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if(selectedIds.length === 0) {
            if(typeof showToast === 'function') {
                showToast('<?= __('admin.warning') ?>', '<?= __('admin.select_notification') ?>', 'warning', 3000);
            } else {
                alert('<?= __('admin.select_notification') ?>');
            }
            return;
        }

        var $btn = $(this);
        window.confirmDelete('<?= __('admin.delete_selected_notifications_confirmation') ?>', function(){
            $btn.prop('disabled', true).html('<i class="bi bi-spinner-border spinner-border-sm me-1"></i><?= __('admin.deleting') ?>...');

			$.ajax({
                type: 'POST',
                url: '<?= base_url('admincontrol/notification') ?>',
                data: {delete_ids: selectedIds},
                dataType: 'json',
                success: function(response) {
                    if(typeof showToast === 'function') {
                        showToast('<?= __('admin.success') ?>', '<?= __('admin.notifications_deleted_successfully') ?>', 'success', 3000);
                    }
                    
                    // Fade out selected rows
                    $('.notification_id:checked').closest('tr').fadeOut(500, function() {
                        $(this).remove();
                        updateNotificationStats();
                        
                        // Check if table is empty
                        if($('.notification-row').length === 0) {
                            location.reload();
                        }
                    });
                },
                error: function() {
                    if(typeof showToast === 'function') {
                        showToast('<?= __('admin.error') ?>', '<?= __('admin.failed_to_delete_notifications') ?>', 'error', 5000);
                    }
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i><?= __('admin.delete_selected') ?>');
                }
            });
        });
        return false;
    });

    // Clear all notifications confirmation
    $('.clear_notification').on('click', function(e) {
        e.preventDefault();
        var el = this;
        window.confirmDelete('<?= __('admin.delete_notifications_confirmation') ?>', function(){
            window.location.href = el.href;
        });
        return false;
    });

    // Update notification stats
    function updateNotificationStats() {
        var totalRows = $('.notification-row').length;
        $('#total-notifications').text(totalRows);
        
        // Count by type
        var orderCount = $('.notification-row').filter(function() {
            return $(this).find('.badge').text().trim() === 'Order';
        }).length;
        
        var userCount = $('.notification-row').filter(function() {
            return $(this).find('.badge').text().trim() === 'User';
        }).length;
        
        $('#order-notifications').text(orderCount);
        $('#user-notifications').text(userCount);
    }

    // Initialize stats
    updateNotificationStats();
});

// View notification details
function viewNotification(id, url) {
    // Validate URL before redirecting
    if (!url || url === '' || url === 'undefined') {
        if(typeof showToast === 'function') {
            showToast('<?= __('admin.error') ?>', '<?= __('admin.invalid_notification_url') ?>', 'error', 3000);
		} else {
            alert('<?= __('admin.invalid_notification_url') ?>');
        }
        return;
    }
    
    // Check if URL is complete
    if (!url.startsWith('http')) {
        url = window.location.origin + url;
    }
    
    // Show loading state briefly then redirect
    if(typeof showToast === 'function') {
        showToast('<?= __('admin.loading') ?>', '<?= __('admin.redirecting_to_details') ?>', 'info', 2000);
    }
    
    setTimeout(function() {
        window.location.href = url;
    }, 1000);
}

// Refresh notifications
function refreshNotifications() {
    location.reload();
}

// Select all notifications
function selectAllNotifications() {
    $('.notification_id').prop('checked', true);
    $('#selectAllCheckbox, #selectAllSwitch').prop('checked', true);
}
</script>