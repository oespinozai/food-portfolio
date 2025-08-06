document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    const projectTypeSelect = document.getElementById('project-type');
    const budgetSelect = document.getElementById('budget');
    
    // Enhanced form validation
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Sending...';
            submitButton.disabled = true;
            
            // Re-enable after 3 seconds (in case form submission fails)
            setTimeout(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            }, 3000);
        });
    }
    
    // Form validation
    function validateForm() {
        let isValid = true;
        const requiredFields = contactForm.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                clearFieldError(field);
            }
        });
        
        // Email validation
        const emailField = document.getElementById('email');
        if (emailField && emailField.value && !isValidEmail(emailField.value)) {
            showFieldError(emailField, 'Please enter a valid email address');
            isValid = false;
        }
        
        return isValid;
    }
    
    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Show field error
    function showFieldError(field, message) {
        clearFieldError(field);
        field.classList.add('error');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    // Clear field error
    function clearFieldError(field) {
        field.classList.remove('error');
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }
    
    // Real-time validation
    const formFields = contactForm.querySelectorAll('input, select, textarea');
    formFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                showFieldError(this, 'This field is required');
            } else if (this.type === 'email' && this.value && !isValidEmail(this.value)) {
                showFieldError(this, 'Please enter a valid email address');
            } else {
                clearFieldError(this);
            }
        });
        
        field.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                clearFieldError(this);
            }
        });
    });
    
    // Project type change handler
    if (projectTypeSelect) {
        projectTypeSelect.addEventListener('change', function() {
            updateBudgetOptions(this.value);
        });
    }
    
    // Update budget options based on project type
    function updateBudgetOptions(projectType) {
        if (!budgetSelect) return;
        
        const budgetRanges = {
            'restaurant': [
                { value: '400-800', text: '£400 - £800' },
                { value: '800-1500', text: '£800 - £1,500' },
                { value: '1500-plus', text: '£1,500+' }
            ],
            'product': [
                { value: '50-200', text: '£50 - £200' },
                { value: '200-500', text: '£200 - £500' },
                { value: '500-plus', text: '£500+' }
            ],
            'editorial': [
                { value: '600-1200', text: '£600 - £1,200' },
                { value: '1200-2500', text: '£1,200 - £2,500' },
                { value: '2500-plus', text: '£2,500+' }
            ]
        };
        
        if (budgetRanges[projectType]) {
            // Clear existing options except the first one
            budgetSelect.innerHTML = '<option value="">Select budget range</option>';
            
            // Add project-specific options
            budgetRanges[projectType].forEach(range => {
                const option = document.createElement('option');
                option.value = range.value;
                option.textContent = range.text;
                budgetSelect.appendChild(option);
            });
            
            // Add generic options
            const genericOptions = [
                { value: 'discuss', text: 'Prefer to discuss' },
                { value: 'other', text: 'Other budget' }
            ];
            
            genericOptions.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.text;
                budgetSelect.appendChild(optionElement);
            });
        }
    }
});
