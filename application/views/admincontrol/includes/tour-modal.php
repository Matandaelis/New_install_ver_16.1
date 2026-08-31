<?php
// Guided Tour Modal HTML Template
// This file contains the HTML structure for the guided tour system
?>

<!-- Enhanced Side Panel Tour System -->
<div id="tourSidePanel" class="tour-side-panel" style="display: none; opacity: 0; visibility: hidden;">
    <div class="tour-panel-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="tour-panel-title mb-1">
                    <i class="fas fa-map-marked-alt me-2"></i><?= __('admin.help_tour') ?>
                </h5>
                <div class="tour-progress-info">
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" id="tourProgressBar" style="width: 0%"></div>
                    </div>
                    <small class="text-muted mt-1" id="tourProgressText">Step 1 of 5</small>
                </div>
            </div>
            <button type="button" class="btn-close" id="tourCloseBtn" aria-label="Close"></button>
        </div>
    </div>
    
    <div class="tour-panel-body">
        <div class="tour-step-content">
            <div class="tour-step-header mb-3">
                <h6 class="tour-step-title mb-2" id="tourStepTitle"></h6>
                <div class="tour-step-number">
                    <span class="badge bg-primary" id="tourStepNumber"></span>
                </div>
            </div>
            
            <div class="tour-message mb-3">
                <div id="tourMessage" class="tour-message-content"></div>
            </div>
            
            <div class="tour-tips mb-3" id="tourTips" style="display: none;">
                <div class="alert alert-info border-0">
                    <div class="d-flex">
                        <i class="fas fa-lightbulb me-2 mt-1"></i>
                        <div>
                            <strong>💡 Pro Tip:</strong>
                            <div id="tourTipText" class="mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tour-highlight-info" id="tourHighlightInfo" style="display: none;">
                <div class="alert alert-warning border-0">
                    <i class="fas fa-mouse-pointer me-2"></i>
                    <strong>Look for:</strong> <span id="tourHighlightText"></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="tour-panel-footer">
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="tourSkip">
                <i class="fas fa-times me-1"></i>Skip Tour
            </button>
            <div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="tourPrevious" style="display: none;">
                    <i class="fas fa-arrow-left me-1"></i>Previous
                </button>
                <button type="button" class="btn btn-primary btn-sm ms-2" id="tourNext">
                    Next <i class="fas fa-arrow-right ms-1"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm ms-2" id="tourFinish" style="display: none;">
                    <i class="fas fa-check me-1"></i>Finish
                </button>
            </div>
        </div>
    </div>
</div>

<!-- No highlight overlay needed - simple instruction-only tour -->