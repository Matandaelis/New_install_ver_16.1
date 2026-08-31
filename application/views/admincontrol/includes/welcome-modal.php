<?php
// Welcome Modal HTML Template
// This file contains the HTML structure for the welcome modal system
?>

<!-- Welcome Modal System -->
<div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h4 class="modal-title fw-bold">
                    <i class="fas fa-rocket me-2"></i><?= __('admin.welcome_modal_title') ?>
                </h4>
            </div>
            <div class="modal-body p-0">
                
                <!-- Business Model Selection Screen -->
                <div id="businessModelSelection" class="welcome-screen">
                    <div class="text-center py-5">
                        <div class="container">
                            <h5 class="text-muted mb-4"><?= __('admin.welcome_modal_subtitle') ?></h5>
                            <h3 class="fw-bold mb-5 text-dark"><?= __('admin.welcome_business_model_question') ?></h3>
                            
                            <div class="row justify-content-center">
                                <!-- External Store Option -->
                                <div class="col-lg-5 mb-4">
                                    <div class="card border-2 border-primary h-100 business-model-card" data-model="external">
                                        <div class="card-body p-4 text-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                                                <i class="fas fa-link text-primary" style="font-size: 2rem;"></i>
                                            </div>
                                            <h4 class="fw-bold text-dark mb-3"><?= __('admin.business_model_external_title') ?></h4>
                                            <p class="text-muted mb-3"><?= __('admin.business_model_external_desc') ?></p>
                                            <div class="bg-light rounded p-3 mb-4">
                                                <small class="text-muted"><?= __('admin.business_model_external_features') ?></small>
                                            </div>
                                            <button class="btn btn-primary btn-lg w-100 select-model-btn" data-model="external">
                                                <?= __('admin.business_model_external_button') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Built-in Store Option -->
                                <div class="col-lg-5 mb-4">
                                    <div class="card border-2 border-success h-100 business-model-card" data-model="builtin">
                                        <div class="card-body p-4 text-center">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-flex mb-3">
                                                <i class="fas fa-store text-success" style="font-size: 2rem;"></i>
                                            </div>
                                            <h4 class="fw-bold text-dark mb-3"><?= __('admin.business_model_builtin_title') ?></h4>
                                            <p class="text-muted mb-3"><?= __('admin.business_model_builtin_desc') ?></p>
                                            <div class="bg-light rounded p-3 mb-4">
                                                <small class="text-muted"><?= __('admin.business_model_builtin_features') ?></small>
                                            </div>
                                            <button class="btn btn-success btn-lg w-100 select-model-btn" data-model="builtin">
                                                <?= __('admin.business_model_builtin_button') ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button class="btn btn-outline-secondary" id="skipWelcome">
                                    <?= __('admin.business_model_skip') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Interactive Demo Screen -->
                <div id="interactiveDemo" class="welcome-screen" style="display: none;">
                    <div class="container py-4">
                        <!-- Demo Header -->
                        <div class="text-center mb-4">
                            <h4 class="fw-bold mb-2" id="demoTitle"></h4>
                            <div class="progress mx-auto" style="width: 300px; height: 8px;">
                                <div class="progress-bar bg-primary" id="demoProgress" style="width: 25%"></div>
                            </div>
                            <small class="text-muted mt-2 d-block" id="demoStepCounter"></small>
                        </div>
                        
                        <!-- Demo Content -->
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="demo-content">
                                    <div class="demo-step" id="demoStep1">
                                        <div class="bg-light rounded-4 p-4 mb-4">
                                            <h5 class="fw-bold mb-3" id="stepTitle1"></h5>
                                            <p class="text-muted mb-0" id="stepDesc1"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="demo-visual text-center">
                                    <div class="bg-gradient-primary rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="fas fa-play-circle" style="font-size: 4rem; opacity: 0.7;"></i>
                                        <h6 class="mt-3 mb-0">Interactive Preview</h6>
                                        <small class="opacity-75">See how it works in real-time</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Demo Navigation -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <button class="btn btn-outline-secondary" id="demoPrevious" style="display: none;">
                                <i class="fas fa-arrow-left me-2"></i><?= __('admin.demo_previous_step') ?>
                            </button>
                            <button class="btn btn-outline-secondary" id="demoBack">
                                <i class="fas fa-arrow-left me-2"></i><?= __('admin.business_model_back') ?>
                            </button>
                            <div class="ms-auto">
                                <button class="btn btn-outline-secondary me-2" id="demoSkip">
                                    <?= __('admin.demo_skip_to_dashboard') ?>
                                </button>
                                <button class="btn btn-primary" id="demoNext">
                                    <?= __('admin.demo_next_step') ?> <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <button class="btn btn-success" id="demoFinish" style="display: none;">
                                    <?= __('admin.demo_finish') ?> <i class="fas fa-rocket ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
