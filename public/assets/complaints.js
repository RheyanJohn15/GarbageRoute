document.getElementById('contact').addEventListener('submit', (e) => {
    e.preventDefault();

    load.on();

    $.ajax({
        type: "POST",
        url: "/api/post/complaints/submit",
        data: $('form#contact').serialize(),
        success: res => {
            load.off();
            const form = document.getElementById('contact');
            const inputs = form.querySelectorAll('input, textarea, select');
            toastr["success"](res.result.response)
            inputs.forEach(input => input.value = '');
        }, error: xhr => console.log(xhr.responseText)
    });
});
