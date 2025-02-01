function validateForm() {

    const form = document.getElementById('myForm');
    const inputs = form.querySelectorAll('input');
    let isValid = true;
    const existingErrorSpans = form.querySelectorAll('.price-error');
    existingErrorSpans.forEach(span => span.remove());


    inputs.forEach(input => {

        const trimmedValue = input.value.trim();
        

        if (input.required && trimmedValue === '') {
            isValid = false;
            
            const errorSpan = document.createElement('span');
            errorSpan.className = 'price-error';
            errorSpan.textContent = `Empty Value is not Valid`;

            input.parentNode.insertBefore(errorSpan, input.nextSibling);
        }
    });

    return isValid;
}