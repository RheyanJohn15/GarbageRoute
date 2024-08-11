document.getElementById('loginButton').addEventListener('click', ()=>{
    let validity = 0;

    validity += helper.checkvalidity('username');
    validity += helper.checkvalidity('password');

    if(validity == 2){
        helper.loadingOn();
        $.ajax({
           type: 'post',
           url: LoginApi,
           data: $('form#loginForm').serialize(),
           success: res=> {
             helper.loadingOff();
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