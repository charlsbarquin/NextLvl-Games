// Select elements
const paymentMethodSelect = document.getElementById('payment_method');
const additionalFieldsContainer = document.getElementById('additional-fields');

// Event listener for payment method change
paymentMethodSelect.addEventListener('change', function () {
    // Clear existing fields
    additionalFieldsContainer.innerHTML = '';
    additionalFieldsContainer.style.display = 'none';

    const selectedMethod = this.value;

    // Add specific fields based on the selected payment method
    if (selectedMethod === 'credit_card') {
        additionalFieldsContainer.innerHTML = `
            <label for="credit_card_number" class="form-label">Credit Card Number</label>
            <input type="text" id="credit_card_number" name="credit_card_number" class="form-control" placeholder="Enter your credit card number" required>
            <label for="credit_card_expiry" class="form-label mt-2">Expiry Date</label>
            <input type="month" id="credit_card_expiry" name="credit_card_expiry" class="form-control" required>
            <label for="credit_card_cvc" class="form-label mt-2">CVC</label>
            <input type="text" id="credit_card_cvc" name="credit_card_cvc" class="form-control" placeholder="Enter CVC" required>
        `;
    } else if (selectedMethod === 'bank_transfer') {
        additionalFieldsContainer.innerHTML = `
            <label for="bank_account_number" class="form-label">Bank Account Number</label>
            <input type="text" id="bank_account_number" name="bank_account_number" class="form-control" placeholder="Enter your bank account number" required>
            <label for="bank_name" class="form-label mt-2">Bank Name</label>
            <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="Enter your bank name" required>
        `;
    } else if (selectedMethod === 'gcash' || selectedMethod === 'grabpay' || selectedMethod === 'maya' || selectedMethod === 'paymaya') {
        additionalFieldsContainer.innerHTML = `
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" id="mobile_number" name="mobile_number" class="form-control" placeholder="Enter your mobile number" required>
        `;
    } else if (selectedMethod === 'paypal') {
        additionalFieldsContainer.innerHTML = `
            <label for="paypal_email" class="form-label">PayPal Email</label>
            <input type="email" id="paypal_email" name="paypal_email" class="form-control" placeholder="Enter your PayPal email" required>
        `;
    }

    // Show the additional fields container
    if (additionalFieldsContainer.innerHTML) {
        additionalFieldsContainer.style.display = 'block';
    }
});
