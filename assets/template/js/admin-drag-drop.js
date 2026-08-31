/**
 * Shared Drag and Drop Functionality for Admin Users Management
 * Used by: userslist table and userslisttree
 */

// Global drag and drop configuration
window.AdminDragDrop = {
    dragSrcId: null,
    
    // Initialize drag and drop for table rows (userslist)
    initTableDragDrop: function() {
        const table = document.querySelector('.user-table tbody');
        if(!table) return;

        table.addEventListener('dragstart', this.handleTableDragStart.bind(this));
        table.addEventListener('dragend', this.handleTableDragEnd.bind(this));
        table.addEventListener('dragover', this.handleTableDragOver.bind(this));
        table.addEventListener('dragleave', this.handleTableDragLeave.bind(this));
        table.addEventListener('drop', this.handleTableDrop.bind(this));
    },

    // Initialize drag and drop for tree nodes (userslisttree)
    initTreeDragDrop: function() {
        const treeContainer = document.querySelector('.usertree');
        if(!treeContainer) return;

        treeContainer.addEventListener('dragstart', this.handleTreeDragStart.bind(this));
        treeContainer.addEventListener('dragend', this.handleTreeDragEnd.bind(this));
        treeContainer.addEventListener('dragover', this.handleTreeDragOver.bind(this));
        treeContainer.addEventListener('dragleave', this.handleTreeDragLeave.bind(this));
        treeContainer.addEventListener('drop', this.handleTreeDrop.bind(this));
    },

    // Table drag handlers
    handleTableDragStart: function(e) {
        const row = e.target.closest('tr[data-user-id]');
        if(!row) return;
        this.dragSrcId = row.getAttribute('data-user-id');
        e.dataTransfer.effectAllowed = 'move';
        row.classList.add('table-active', 'dragging');
        const dragHandle = row.querySelector('.drag-handle');
        if(dragHandle) dragHandle.style.cursor = 'grabbing';
    },

    handleTableDragEnd: function(e) {
        const row = e.target.closest('tr[data-user-id]');
        if(row) {
            row.classList.remove('table-active', 'dragging');
            const dragHandle = row.querySelector('.drag-handle');
            if(dragHandle) dragHandle.style.cursor = 'grab';
        }
        this.dragSrcId = null;
        document.querySelectorAll('tr.drop-target').forEach(r => {
            r.classList.remove('drop-target', 'table-warning');
        });
    },

    handleTableDragOver: function(e) {
        const row = e.target.closest('tr[data-user-id]');
        if(!row) return;
        e.preventDefault();
        row.classList.add('drop-target', 'table-warning');
        e.dataTransfer.dropEffect = 'move';
    },

    handleTableDragLeave: function(e) {
        const row = e.target.closest('tr[data-user-id]');
        if(row) {
            row.classList.remove('drop-target', 'table-warning');
        }
    },

    handleTableDrop: function(e) {
        e.preventDefault();
        const targetRow = e.target.closest('tr[data-user-id]');
        if(!targetRow) return;
        
        const newParentId = targetRow.getAttribute('data-user-id');
        const childId = this.dragSrcId;
        
        document.querySelectorAll('tr.drop-target').forEach(r => { 
            r.classList.remove('drop-target', 'table-warning'); 
        });
        
        if(!childId || childId === newParentId) return;
        
        const sourceRow = document.querySelector('tr[data-user-id="'+childId+'"]');
        const sourceLabel = sourceRow ? (sourceRow.getAttribute('data-user-display') || ('#'+childId)) : ('#'+childId);
        const targetLabel = targetRow ? (targetRow.getAttribute('data-user-display') || ('#'+newParentId)) : ('#'+newParentId);
        
        this.showConfirmationModal(childId, newParentId, sourceLabel, targetLabel);
    },

    // Tree drag handlers
    handleTreeDragStart: function(e) {
        const node = e.target.closest('.tree-user-node[data-user-id]');
        if(!node) return;
        this.dragSrcId = node.getAttribute('data-user-id');
        e.dataTransfer.effectAllowed = 'move';
        node.classList.add('dragging');
        // Prevent modal from opening during drag
        node.onclick = null;
    },

    handleTreeDragEnd: function(e) {
        const node = e.target.closest('.tree-user-node[data-user-id]');
        if(node) {
            node.classList.remove('dragging');
            // Restore modal functionality
            const userId = node.getAttribute('data-user-id');
            node.onclick = function() { 
                if(typeof showUserModal === 'function') {
                    showUserModal(userId); 
                }
            };
        }
        this.dragSrcId = null;
        document.querySelectorAll('.tree-user-node.drop-target').forEach(n => {
            n.classList.remove('drop-target');
        });
    },

    handleTreeDragOver: function(e) {
        const node = e.target.closest('.tree-user-node[data-user-id]');
        if(!node) return;
        e.preventDefault();
        node.classList.add('drop-target');
        e.dataTransfer.dropEffect = 'move';
    },

    handleTreeDragLeave: function(e) {
        const node = e.target.closest('.tree-user-node[data-user-id]');
        if(node) {
            node.classList.remove('drop-target');
        }
    },

    handleTreeDrop: function(e) {
        e.preventDefault();
        const targetNode = e.target.closest('.tree-user-node[data-user-id]');
        if(!targetNode) return;
        
        const newParentId = targetNode.getAttribute('data-user-id');
        const childId = this.dragSrcId;
        
        document.querySelectorAll('.tree-user-node.drop-target').forEach(n => { 
            n.classList.remove('drop-target'); 
        });
        
        if(!childId || childId === newParentId) return;
        
        const sourceNode = document.querySelector('.tree-user-node[data-user-id="'+childId+'"]');
        const sourceLabel = sourceNode ? sourceNode.getAttribute('data-user-display') : ('#'+childId);
        const targetLabel = targetNode ? targetNode.getAttribute('data-user-display') : ('#'+newParentId);
        
        this.showConfirmationModal(childId, newParentId, sourceLabel, targetLabel);
    },

    // Shared confirmation modal
    showConfirmationModal: function(childId, newParentId, sourceLabel, targetLabel) {
        // Get translation strings from global variables (set by PHP)
        const translations = window.AdminDragDropTranslations || {};
        
        const modalHtml = `
        <div class="modal fade" id="dndConfirmModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-question-circle text-warning me-2"></i>${translations.confirm_hierarchy_change || 'Confirm Hierarchy Change'}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="alert alert-warning">
                  <i class="fa fa-exclamation-triangle me-2"></i>
                  <strong>${translations.hierarchy_change_warning || 'This will change the user hierarchy!'}</strong>
                </div>
                <p>${translations.move_user_under_new_parent || 'Move user under new parent?'}</p>
                <div class="row">
                  <div class="col-6">
                    <div class="card bg-light">
                      <div class="card-body text-center">
                        <i class="fa fa-user text-primary mb-2"></i>
                        <div class="fw-bold">${sourceLabel}</div>
                        <small class="text-muted">${translations.user_to_move || 'User to move'}</small>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="card bg-light">
                      <div class="card-body text-center">
                        <i class="fa fa-crown text-warning mb-2"></i>
                        <div class="fw-bold">${targetLabel}</div>
                        <small class="text-muted">${translations.new_parent || 'New parent'}</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">${translations.cancel || 'Cancel'}</button>
                <button type="button" class="btn btn-warning" onclick="AdminDragDrop.executeHierarchyChange('${childId}', '${newParentId}')">${translations.confirm_change || 'Confirm Change'}</button>
              </div>
            </div>
          </div>
        </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = new bootstrap.Modal(document.getElementById('dndConfirmModal'));
        modal.show();
        
        // Clean up modal after hide
        document.getElementById('dndConfirmModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    },

    // Execute the hierarchy change via AJAX
    executeHierarchyChange: function(childId, newParentId) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('dndConfirmModal'));
        modal.hide();
        
        const translations = window.AdminDragDropTranslations || {};
        const baseUrl = window.AdminDragDropConfig?.baseUrl || '';
        
        $.ajax({
            url: baseUrl + 'admincontrol/change_user_parent',
            type: 'POST',
            data: {
                child_id: childId,
                new_parent_id: newParentId
            },
            success: function(response) {
                if(response.success) {
                    if(typeof showToast === 'function') {
                        showToast(
                            translations.success || 'Success', 
                            translations.hierarchy_changed_successfully || 'Hierarchy changed successfully', 
                            'success', 
                            3000
                        );
                    }
                    // Reload the page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    if(typeof showToast === 'function') {
                        showToast(
                            translations.error || 'Error', 
                            response.message || translations.hierarchy_change_failed || 'Failed to change hierarchy', 
                            'error', 
                            5000
                        );
                    }
                }
            },
            error: function() {
                if(typeof showToast === 'function') {
                    showToast(
                        translations.error || 'Error', 
                        translations.hierarchy_change_failed || 'Failed to change hierarchy', 
                        'error', 
                        5000
                    );
                }
            }
        });
    },

    // Auto-dismiss drag hint
    autoDismissDragHint: function(hintId, delay = 8000) {
        setTimeout(function(){
            const dragHint = document.getElementById(hintId);
            if(dragHint && dragHint.classList.contains('show')){
                const bsAlert = bootstrap.Alert.getOrCreateInstance(dragHint);
                bsAlert.close();
            }
        }, delay);
    }
};
