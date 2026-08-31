<div>
    <?php if (empty($populer_users)): ?>
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                <i class="fas fa-users text-muted fs-1"></i>
            </div>
            <h5 class="text-muted mb-2"><?= __('admin.no_affiliates_yet') ?></h5>
            <p class="text-muted mb-3"><?= __('admin.no_affiliates_description') ?></p>
            <a href="<?= base_url('admincontrol/userslist') ?>" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-user-plus me-1"></i>
                <?= __('admin.manage_users') ?>
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php $rank = 1; ?>
            <?php $user_count = count($populer_users); ?>
            <?php $total_slots = ($user_count >= 5) ? 8 : 4; ?>
            
            <?php foreach (array_slice($populer_users, 0, $total_slots) as $users): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 position-relative overflow-hidden <?= ($rank <= 3) ? 'border-warning border-2' : ''; ?>" 
                         data-rank="<?= $rank ?>" 
                         data-user-id="<?= $users['user_id'] ?>">
                        
                        <!-- Rank Badge for Top 3 -->
                        <?php if($rank <= 3): ?>
                            <div class="position-absolute top-0 end-0 m-2 z-3">
                                <div class="bg-<?= ($rank == 1) ? 'warning' : (($rank == 2) ? 'secondary' : 'success') ?> text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="fas fa-<?= ($rank == 1) ? 'crown' : 'medal' ?>"></i>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Performance Bar -->
                        <div class="position-absolute top-0 start-0 w-100" style="height: 4px;">
                            <div class="bg-<?= ($rank == 1) ? 'warning' : (($rank == 2) ? 'secondary' : 'success') ?> h-100" style="width: <?= (4 - $rank) * 25 ?>%;"></div>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- User Info -->
                            <div class="d-flex align-items-center mb-4">
                                <div class="position-relative me-3">
                                    <?php
                                    $db =& get_instance();
                                    $products = $db->Product_model;
                                    ?>
                                    <img class="rounded-circle border border-white shadow-sm" 
                                          src="<?= $products->getAvatar($users['avatar']); ?>" 
                                          alt="<?= $users['firstname'] . ' ' . $users['lastname']; ?>"
                                          style="width: 60px; height: 60px; object-fit: cover;" />
                                    <!-- Online Status Indicator -->
                                    <div class="position-absolute bottom-0 end-0">
                                        <span class="badge rounded-pill <?= (isset($users['last_ping']) && !empty($users['last_ping']) && (time() - strtotime($users['last_ping'])) < 900) ? 'bg-success' : 'bg-secondary' ?>" style="width: 12px; height: 12px; padding: 0;">
                                            <i class="fas fa-circle" ></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 text-dark"><?= $users['firstname'] . ' ' . $users['lastname']; ?></h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if(isset($users['sortname']) && $users['sortname']): ?>
                                            <div title="<?= $users['country_name'] ?>">
                                                <img src="<?= base_url('assets/template/images/flags/') . strtolower($users['sortname']) . '.png' ?>" 
                                                     alt="<?= $users['country_name'] ?>" 
                                                     class="rounded" style="width: 20px; height: 15px; object-fit: cover;"
                                                     onerror="this.onerror=null;this.style.display='none'" />
                                            </div>
                                        <?php endif; ?>
                                        <span class="badge <?= (isset($users['last_ping']) && !empty($users['last_ping']) && (time() - strtotime($users['last_ping'])) < 900) ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= (isset($users['last_ping']) && !empty($users['last_ping']) && (time() - strtotime($users['last_ping'])) < 900) ? __('admin.online') : __('admin.offline') ?>
                                        </span>
                                        <?php
                                        $hs = isset($users['health_score']) && $users['health_score'] !== null && $users['health_score'] !== '' ? (float)$users['health_score'] : null;
                                        $hsTitle = htmlspecialchars(__('admin.health_score_badge_title'), ENT_QUOTES, 'UTF-8');
                                        if ($hs === null) {
                                            echo '<span class="badge bg-light text-muted border" title="' . $hsTitle . '">' . __('admin.health_score_not_calculated_short') . '</span>';
                                        } elseif ($hs >= 80) {
                                            echo '<span class="badge bg-success fw-semibold" title="' . $hsTitle . '">' . number_format($hs, 1) . '</span>';
                                        } elseif ($hs >= 50) {
                                            echo '<span class="badge bg-warning text-dark fw-semibold" title="' . $hsTitle . '">' . number_format($hs, 1) . '</span>';
                                        } else {
                                            echo '<span class="badge bg-danger fw-semibold" title="' . $hsTitle . '">' . number_format($hs, 1) . '</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Performance Metrics -->
                            <div class="mb-4">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="bg-success bg-opacity-10 rounded-3 p-3 text-center">
                                            <div class="mb-2">
                                                <i class="fas fa-wallet text-success fs-5"></i>
                                            </div>
                                            <div class="fw-bold text-success mb-1">
                                                <?= $fun_c_format($users['all_commition']) ?>
                                            </div>
                                            <div class="text-muted small">
                                                <?= __('admin.balance') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center">
                                            <div class="mb-2">
                                                <i class="fas fa-shopping-cart text-primary fs-5"></i>
                                            </div>
                                            <div class="fw-bold text-primary mb-1">
                                                <?= isset($users['total_conversions']) ? number_format($users['total_conversions']) : '0' ?>
                                            </div>
                                            <div class="text-muted small">
                                                <?= __('admin.sales') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-info bg-opacity-10 rounded-3 p-3 text-center">
                                            <div class="mb-2">
                                                <i class="fas fa-mouse-pointer text-info fs-5"></i>
                                            </div>
                                            <div class="fw-bold text-info mb-1">
                                                <?= isset($users['total_clicks']) ? number_format($users['total_clicks']) : '0' ?>
                                            </div>
                                            <div class="text-muted small">
                                                <?= __('admin.clicks') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 text-center">
                                            <div class="mb-2">
                                                <i class="fas fa-calendar-alt text-warning fs-5"></i>
                                            </div>
                                            <div class="fw-bold text-warning mb-1">
                                                <?= isset($users['join_date']) ? date('M Y', strtotime($users['join_date'])) : 'N/A' ?>
                                            </div>
                                            <div class="text-muted small">
                                                <?= __('admin.joined') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-4 pt-3 border-top">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-success btn-sm flex-fill rounded-pill" 
                                            onclick="sendMessage(<?= $users['user_id'] ?>)" 
                                            title="<?= __('admin.send_message') ?>">
                                        <i class="fas fa-envelope me-1"></i>
                                        <?= __('admin.message') ?>
                                    </button>
                                    <a href="<?= base_url('admincontrol/all_transaction?user_id=' . $users['user_id']) ?>" 
                                       class="btn btn-outline-info btn-sm rounded-pill" 
                                       title="<?= __('admin.view_transactions') ?>"
                                       target="_blank">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $rank++; ?>
            <?php endforeach; ?>
            
            <?php 
            // Fill remaining slots with placeholder cards
            $remaining_slots = $total_slots - $user_count;
            for ($i = 0; $i < $remaining_slots; $i++): 
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm h-100 bg-light bg-opacity-50 position-relative" style="border: 2px dashed #dee2e6 !important;">
                        <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 400px;">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-plus text-muted fs-2"></i>
                            </div>
                            <h6 class="text-muted fw-bold mb-2"><?= __('admin.slot_available') ?></h6>
                            <p class="text-muted small mb-3"><?= __('admin.slot_available_desc') ?></p>
                            <span class="badge bg-secondary bg-opacity-25 text-secondary px-3 py-2">
                                #<?= $rank ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php $rank++; ?>
            <?php endfor; ?>
        </div>
         
         <!-- Performance Summary -->
         <div class="mt-4 p-4 bg-light rounded-4">
             <div class="row text-center">
                 <div class="col-md-3 col-6 mb-3">
                     <div>
                         <div class="fw-bold text-primary fs-4">
                             <?= count($populer_users) ?>
                         </div>
                         <div class="text-muted">
                             <?= __('admin.total_affiliates') ?>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-3 col-6 mb-3">
                     <div>
                         <div class="fw-bold text-success fs-4">
                             <?= $fun_c_format(array_sum(array_column($populer_users, 'all_commition'))) ?>
                         </div>
                         <div class="text-muted">
                             <?= __('admin.total_balance') ?>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-3 col-6 mb-3">
                     <div>
                         <div class="fw-bold text-info fs-4">
                             <?= number_format(array_sum(array_column($populer_users, 'total_conversions'))) ?>
                         </div>
                         <div class="text-muted">
                             <?= __('admin.total_sales') ?>
                         </div>
                     </div>
                 </div>
                 <div class="col-md-3 col-6 mb-3">
                     <div>
                         <div class="fw-bold text-warning fs-4">
                             <?= number_format(array_sum(array_column($populer_users, 'total_clicks'))) ?>
                         </div>
                         <div class="text-muted">
                             <?= __('admin.total_clicks') ?>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     <?php endif; ?>
</div> 