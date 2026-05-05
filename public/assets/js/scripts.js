document.addEventListener("DOMContentLoaded", function() {
    


    // 2. Hide/Show Balance Toggle
    const toggleEye = document.querySelector('.bi-eye-slash-fill, .bi-eye-fill');
    if (toggleEye) {
        toggleEye.addEventListener('click', function() {
            const balanceElement = this.closest('.balance-card').querySelector('h1');
            
            if (this.classList.contains('bi-eye-slash-fill')) {
                // Hide balance
                this.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                balanceElement.setAttribute('data-original', balanceElement.innerText);
                balanceElement.innerText = 'Rp ••••••';
            } else {
                // Show balance
                this.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                balanceElement.innerText = balanceElement.getAttribute('data-original');
            }
        });
    }

    // 3. Dynamic Category Dropdown (Income vs Expense)
    const expenseTab = document.getElementById('expense-tab');
    const incomeTab = document.getElementById('income-tab');
    const categorySelect = document.getElementById('transactionCategory');

    const expenseCategories = [
        {value: '1', text: 'Food & Drink'},
        {value: '2', text: 'Transportation'},
        {value: '3', text: 'Entertainment'},
        {value: '4', text: 'Shopping'},
        {value: '5', text: 'Bills'}
    ];

    const incomeCategories = [
        {value: '6', text: 'Salary'},
        {value: '7', text: 'Side Job'},
        {value: '8', text: 'Investment'},
        {value: '9', text: 'Gift'},
        {value: '10', text: 'Refund'}
    ];

    function updateCategoryDropdown(categories) {
        if (!categorySelect) return;
        categorySelect.innerHTML = '<option selected value="">Select Category</option>';
        categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.value;
            option.textContent = cat.text;
            categorySelect.appendChild(option);
        });
    }

    if (expenseTab && incomeTab) {
        expenseTab.addEventListener('click', () => updateCategoryDropdown(expenseCategories));
        incomeTab.addEventListener('click', () => updateCategoryDropdown(incomeCategories));
        
        // Initial load
        updateCategoryDropdown(expenseCategories);
    }

});
