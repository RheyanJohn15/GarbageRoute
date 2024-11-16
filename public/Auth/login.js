document.getElementById('loginForm').addEventListener('submit', (e)=>{
    e.preventDefault();
    let validity = 0;

    validity += helper.checkvalidity('username');
    validity += helper.checkvalidity('password');

    if(validity == 2){
        load.on();
        $.ajax({
           type: 'post',
           url: LoginApi,
           data: $('form#loginForm').serialize(),
           success: res=> {
             load.off();
             if(res.status == 'success'){
                toastr.success('Login Successfully', 'Success')
                window.location.href= dashboard;
             }else{
                toastr.error(res.result, 'Error');
             }
           },error: xhr =>console.log(xhr.responseText, 'Error')
        });

    }else{
        toastr.error('Please complete necessary credentials', 'Error')
    }
});

document.getElementById('showPass').addEventListener('click', ()=> {
    const pass = document.getElementById('password');

    if(pass.type == 'password'){
        pass.type = 'text';
    }else{
        pass.type = 'password';
    }
});
