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

            data.forEach( d => {
                zones.innerHTML += `<option value="${d.zone_id}">${d.zone_name}</option>`
            });
        }, error: xhr=> console.log(xhr.responseText)
    });


    $.ajax({
        type: "GET",
        url: "/api/get/landing/dashboard",
        dataType: "json",
        success: res=> {
            const data = res.result.data;
            
           setText('pendingCounter', data[0]);
           setText('progressCounter', data[1]);
           setText('resolveCounter', data[2]);
           isShow('compLoader', false);
           if(data[3].length == 0){
            isShow('emptyCpl', true);
            return;
           }
           
           isShow('complaintList', true, 'block');
           const compList = document.getElementById('complaintList');

           compList.innerHTML = '';
           let listNum = 1;
           data[3].forEach(d=> {
            compList.innerHTML += `<div class="item">
                            <h4>Complaint #${listNum}</h4>
                            <img src="ComplaintAssets/${d.comp_image}" alt="Complaint Image" class="img-fluid"
                                style="width: 350px; height: 250px; ">
                            <p class="text-start m-0">
                                <strong>Name:</strong>${d.comp_name}<br>
                                <strong>Nature:</strong>${d.comp_nature}<br>
                                <strong>Status:</strong> ${checkStatus(d.comp_status)}<br>
                            </p>
                        </div>`;

                        listNum++;
           });
        }, error: xhr=> console.log(xhr.responseText)
    });


    
}

function checkStatus(status){
    switch(status){
        case 0:
            return 'Pending';
        case 1:
            return 'In Action';
        default:
            return 'Resolved';
    }
}
window.addEventListener('keydown', function(event) {
    // Check if the 'Shift' key is pressed along with the 'A' key
    if (event.shiftKey && event.key === 'A') {
            window.location.href = "/auth/login";
    }
});