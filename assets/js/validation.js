// Form Validation Module
const FormValidator = {
    // Validate email format
    validateEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    },

    // Validate phone number (international format)
    validatePhone: function(phone) {
        const re = /^[\+]?[(]?[0-9]{1,3}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/;
        return re.test(String(phone));
    },

    // Validate password strength
    validatePasswordStrength: function(password) {
        let strength = 0;
        const feedback = [];
        
        if (password.length < 8) {
            feedback.push("Password must be at least 8 characters long");
        } else {
            strength += 1;
        }
        
        if (password.match(/[a-z]+/)) {
            strength += 1;
        } else {
            feedback.push("Add at least one lowercase letter");
        }
        
        if (password.match(/[A-Z]+/)) {
            strength += 1;
        } else {
            feedback.push("Add at least one uppercase letter");
        }
        
        if (password.match(/[0-9]+/)) {
            strength += 1;
        } else {
            feedback.push("Add at least one number");
        }
        
        if (password.match(/[$@#&!]+/)) {
            strength += 1;
        } else {
            feedback.push("Add at least one special character ($@#&!)");
        }
        
        return {
            strength: strength,
            feedback: feedback,
            isStrong: strength >= 4
        };
    },

    // Validate date format
    validateDate: function(date) {
        const re = /^\d{4}-\d{2}-\d{2}$/;
        if (!re.test(date)) return false;
        
        const [year, month, day] = date.split('-').map(Number);
        const dateObj = new Date(year, month - 1, day);
        return dateObj.getFullYear() === year && 
               dateObj.getMonth() === month - 1 && 
               dateObj.getDate() === day;
    },

    // Validate age (must be at least minAge)
    validateAge: function(birthDate, minAge = 5) {
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
        return age >= minAge;
    },

    // Show validation message
    showMessage: function(input, message, isValid) {
        const feedbackDiv = input.nextElementSibling;
        if (feedbackDiv && feedbackDiv.classList.contains('validation-feedback')) {
            if (isValid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedbackDiv.innerHTML = '<span class="text-success">✓ ' + message + '</span>';
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                feedbackDiv.innerHTML = '<span class="text-danger">✗ ' + message + '</span>';
            }
        }
    }
};

// Real-time validation for student form
document.addEventListener('DOMContentLoaded', function() {
    // Student form validation
    const studentForm = document.getElementById('studentForm');
    if (studentForm) {
        // Name validation
        const nameInput = document.getElementById('name');
        if (nameInput) {
            nameInput.addEventListener('input', function() {
                const value = this.value.trim();
                if (value.length < 3) {
                    FormValidator.showMessage(this, 'Name must be at least 3 characters', false);
                } else if (value.length > 100) {
                    FormValidator.showMessage(this, 'Name must be less than 100 characters', false);
                } else {
                    FormValidator.showMessage(this, 'Valid name', true);
                }
            });
        }

        // Email validation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                if (FormValidator.validateEmail(this.value)) {
                    FormValidator.showMessage(this, 'Valid email address', true);
                } else {
                    FormValidator.showMessage(this, 'Please enter a valid email', false);
                }
            });
        }

        // Phone validation
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                if (this.value === '' || FormValidator.validatePhone(this.value)) {
                    FormValidator.showMessage(this, 'Valid phone number', true);
                } else {
                    FormValidator.showMessage(this, 'Please enter a valid phone number', false);
                }
            });
        }

        // Birth date validation
        const birthDateInput = document.getElementById('birth_date');
        if (birthDateInput) {
            birthDateInput.addEventListener('change', function() {
                if (!FormValidator.validateDate(this.value)) {
                    FormValidator.showMessage(this, 'Please enter a valid date', false);
                } else if (!FormValidator.validateAge(this.value)) {
                    FormValidator.showMessage(this, 'Student must be at least 5 years old', false);
                } else {
                    FormValidator.showMessage(this, 'Valid date', true);
                }
            });
        }
    }

    // Password strength meter
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        const strengthMeter = document.createElement('div');
        strengthMeter.className = 'password-strength-meter mt-2';
        passwordInput.parentNode.appendChild(strengthMeter);

        passwordInput.addEventListener('input', function() {
            const result = FormValidator.validatePasswordStrength(this.value);
            let strengthHtml = '<div class="progress" style="height: 5px;">';
            
            if (result.strength === 0) {
                strengthHtml += '<div class="progress-bar bg-danger" style="width: 20%"></div>';
            } else if (result.strength === 1) {
                strengthHtml += '<div class="progress-bar bg-danger" style="width: 40%"></div>';
            } else if (result.strength === 2) {
                strengthHtml += '<div class="progress-bar bg-warning" style="width: 60%"></div>';
            } else if (result.strength === 3) {
                strengthHtml += '<div class="progress-bar bg-info" style="width: 80%"></div>';
            } else if (result.strength >= 4) {
                strengthHtml += '<div class="progress-bar bg-success" style="width: 100%"></div>';
            }
            
            strengthHtml += '</div>';
            
            if (result.feedback.length > 0) {
                strengthHtml += '<small class="text-muted">' + result.feedback.join(', ') + '</small>';
            }
            
            strengthMeter.innerHTML = strengthHtml;
        });
    }

    // Confirm password validation
    const confirmPasswordInput = document.getElementById('confirm_password');
    if (confirmPasswordInput && passwordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value === passwordInput.value) {
                FormValidator.showMessage(this, 'Passwords match', true);
            } else {
                FormValidator.showMessage(this, 'Passwords do not match', false);
            }
        });
    }
});