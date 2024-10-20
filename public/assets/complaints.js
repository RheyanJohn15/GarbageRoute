document.getElementById('contact').addEventListener('submit', (e) => {
    e.preventDefault();

    load.on();
    const formData = new FormData($('form#contact')[0]);
    $.ajax({
        type: "POST",
        url: "/api/post/complaints/submit",
        data: formData,
        processData: false,
        contentType: false,
        success: res => {
            load.off();
            const form = document.getElementById('contact');
            const inputs = form.querySelectorAll('input, textarea, select');
            toastr["success"](res.result.response)
            inputs.forEach(input => input.value = '');
            document.getElementById('preview').src = '';
        }, error: xhr => console.log(xhr.responseText)
    });
});


window.onload = () =>{
    $.ajax({
        type: "GET",
        url: "/api/get/complaints/getzone",
        dataType: "json",
        success: res=> {
            const zones = document.getElementById('zonelist');
            zones.disabled = false;
            const data = res.result.data;
            zones.innerHTML = ` <option value="" disabled selected> Select Zone of Complaint
                                                    </option>`;

            console.log(res);
            data.forEach( d => {
                zones.innerHTML += `<option value="${d.zone_id}">${d.zone_name}</option>`
            });
        }, error: xhr=> console.log(xhr.responseText)
    });
    
}

window.addEventListener('keydown', function(event) {
    // Check if the 'Shift' key is pressed along with the 'A' key
    if (event.shiftKey && event.key === 'A') {
            window.location.href = "/auth/login";
    }
});