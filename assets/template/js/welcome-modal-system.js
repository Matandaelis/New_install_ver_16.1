class WelcomeModalSystem {
    constructor() {
        this.currentStep = 0;
        this.selectedModel = null;
        this.steps = [];
        this.modal = null;
        this.isInitialized = false;
    }

    init() {
        return new Promise((resolve) => {
            if (this.isInitialized) {
                resolve();
                return;
            }

            const modalEl = document.getElementById('welcomeModal');
            if (modalEl) {
                this.modal = new bootstrap.Modal(modalEl);
                this.setupEventListeners();
            }
            
            this.isInitialized = true;
            resolve();
        });
    }

    reopenWelcome() {
        if (this.modal) {
            this.resetState();
            this.modal.show();
        }
    }

    setupEventListeners() {
        // Model Selection
        document.querySelectorAll('.select-model-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const model = e.target.closest('.select-model-btn').dataset.model;
                this.startDemo(model);
            });
        });

        // Skip Welcome
        const skipBtn = document.getElementById('skipWelcome');
        if(skipBtn) skipBtn.addEventListener('click', () => this.modal.hide());

        // Demo Navigation
        const nextBtn = document.getElementById('demoNext');
        if(nextBtn) nextBtn.addEventListener('click', () => this.nextStep());

        const prevBtn = document.getElementById('demoPrevious');
        if(prevBtn) prevBtn.addEventListener('click', () => this.prevStep());

        const backBtn = document.getElementById('demoBack');
        if(backBtn) backBtn.addEventListener('click', () => this.showSelectionScreen());

        const skipDemoBtn = document.getElementById('demoSkip');
        if(skipDemoBtn) skipDemoBtn.addEventListener('click', () => this.finishDemo(true));

        const finishBtn = document.getElementById('demoFinish');
        if(finishBtn) finishBtn.addEventListener('click', () => this.finishDemo(false));
    }

    startDemo(model) {
        this.selectedModel = model;
        this.steps = window.welcomeDemoSteps[model];
        this.currentStep = 0;
        
        // Hide selection, show demo
        const selectionScreen = document.getElementById('businessModelSelection');
        const demoScreen = document.getElementById('interactiveDemo');
        
        if (selectionScreen) selectionScreen.style.display = 'none';
        if (demoScreen) demoScreen.style.display = 'block';
        
        // Set Title
        const titleEl = document.getElementById('demoTitle');
        if (titleEl) {
            titleEl.innerText = model === 'external' ? window.welcomeTranslations.demo_external_title : window.welcomeTranslations.demo_builtin_title;
        }

        this.updateDemoStep();
    }

    showSelectionScreen() {
        const selectionScreen = document.getElementById('businessModelSelection');
        const demoScreen = document.getElementById('interactiveDemo');
        
        if (selectionScreen) selectionScreen.style.display = 'block';
        if (demoScreen) demoScreen.style.display = 'none';
    }

    resetState() {
        this.showSelectionScreen();
        this.currentStep = 0;
        this.selectedModel = null;
    }

    updateDemoStep() {
        if (!this.steps || !this.steps[this.currentStep]) return;

        const step = this.steps[this.currentStep];
        const totalSteps = this.steps.length;
        
        // Update Progress
        const progressEl = document.getElementById('demoProgress');
        if (progressEl) {
            const progress = ((this.currentStep + 1) / totalSteps) * 100;
            progressEl.style.width = `${progress}%`;
        }
        
        // Update Counter
        const counterEl = document.getElementById('demoStepCounter');
        if (counterEl) {
            counterEl.innerText = `Step ${this.currentStep + 1} of ${totalSteps}`;
        }
        
        // Update Content
        const titleEl = document.getElementById('stepTitle1');
        const descEl = document.getElementById('stepDesc1');
        
        if (titleEl) titleEl.innerText = step.title;
        if (descEl) descEl.innerHTML = step.desc; // Use innerHTML as descriptions might contain simple formatting
        
        // Update Buttons
        const prevBtn = document.getElementById('demoPrevious');
        const nextBtn = document.getElementById('demoNext');
        const finishBtn = document.getElementById('demoFinish');
        const backBtn = document.getElementById('demoBack');

        if (prevBtn && backBtn) {
            if (this.currentStep === 0) {
                prevBtn.style.display = 'none';
                backBtn.style.display = 'inline-block';
            } else {
                prevBtn.style.display = 'inline-block';
                backBtn.style.display = 'none';
            }
        }

        if (nextBtn && finishBtn) {
            if (this.currentStep === totalSteps - 1) {
                nextBtn.style.display = 'none';
                finishBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                finishBtn.style.display = 'none';
            }
        }
    }

    nextStep() {
        if (this.currentStep < this.steps.length - 1) {
            this.currentStep++;
            this.updateDemoStep();
        }
    }

    prevStep() {
        if (this.currentStep > 0) {
            this.currentStep--;
            this.updateDemoStep();
        }
    }

    finishDemo(skipped) {
        if (this.modal) this.modal.hide();
        
        if (!skipped) {
             const pathParts = window.location.pathname.split('admincontrol');
             const baseUrl = window.location.origin + pathParts[0]; 
             
             let redirectPath = 'admincontrol/dashboard'; // default
             
             if (this.selectedModel === 'external') {
                 // Redirect to integration tools
                 redirectPath = 'admincontrol/integration_tools';
                 if (typeof showToast === 'function') {
                    showToast(window.welcomeTranslations.welcome_demo_completed, window.welcomeTranslations.redirecting_to_marketing, 'success');
                 }
             } else {
                 // Redirect to store settings
                 redirectPath = 'admincontrol/store_setting';
                 if (typeof showToast === 'function') {
                    showToast(window.welcomeTranslations.welcome_demo_completed, window.welcomeTranslations.redirecting_to_store, 'success');
                 }
             }
             
             setTimeout(() => {
                 window.location.href = baseUrl + redirectPath;
             }, 1500);
        }
    }
}

// Assign to window so it can be instantiated by the footer script
window.WelcomeModalSystem = WelcomeModalSystem;
