document.getElementById('login-form').addEventListener('submit', (e)=> {
    e.preventDefault();

    load.on();

    $.ajax({
        type: "POST",
        url: "/api/post/userdriver/login",
        data: $('#login-form').serialize(),
        success: res => {
            load.off();
            const id = res.result.split('-')[1];

            localStorage.setItem('driverId', id);
            if(res.status == 'success'){
                toastr["success"]("Login Successfully");
                window.location.href = '/user/driver/dashboard';
            }else{
                toastr['error'](res.result);
            }
        }, error: xhr => console.log(xhr.responseText)
    });
});
