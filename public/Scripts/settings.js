window.onload = async () => {
    const user = await getGlobalUser(); // Wait for globalUser to be available

    console.log(user);

    const data = user.data;
    reactive('accountNameSettings', false);
    reactive('accountUsernameSettings', false);
    setValue('accountNameSettings', data.acc_name);
    setValue('accountUsernameSettings', data.acc_username);
    setValue('accountIdSettings', data.acc_id);
    setValue('changePassAccountId', data.acc_id);
};

function detectChange(){
    document.getElementById('updateAccount').classList.remove('d-none');
}

document.getElementById('updateAccount').addEventListener('click', ()=> {
    document.getElementById('accountDetails').requestSubmit();
});

document.getElementById('accountDetails').addEventListener('submit', (e)=> {
    e.preventDefault();

    load.on();

    $.ajax({
        type: "POST",
        url: "/api/post/adminaccount/update",
        data: $('#accountDetails').serialize(),
        success: res=> {
            load.off();
            parseResult(res);
            document.getElementById('updateAccount').classList.add('d-none');
        }, error: xhr=> console.log(xhr.responseText)
    })
});

document.getElementById('updatePassword').addEventListener('click',()=> {
    const newPass = document.getElementById('newPass');
    const confirmPass = document.getElementById('confirmPass');

    const newPassE  = document.getElementById('newPassE');
    const confirmPassE = document.getElementById('confirmPassE');

    if(newPass.value === confirmPass.value){
        newPass.classList.remove('border', 'border-danger');
        confirmPass.classList.remove('border', 'border-danger');
        confirmPassE.classList.add('d-none');
        newPassE.classList.add('d-none');

        document.getElementById('changePassForm').requestSubmit(); 
    }else{
        newPass.classList.add('border', 'border-danger');
        confirmPass.classList.add('border', 'border-danger');
        confirmPassE.classList.remove('d-none');
        newPassE.classList.remove('d-none')
    }
    
});

document.getElementById('changePassForm').addEventListener('submit', (e)=> {
    e.preventDefault();

    load.on();

    $.ajax({
        type:"POST",
        url: "/api/post/adminaccount/changepass",
        data: $('#changePassForm').serialize(),
        success: res=> {
            load.off();
            parseResult(res);
            if(res.status == 'success'){
                clicked('closeChangePassword');
            }
        }, error: xhr=> console.log(xhr.responseText)
    })
});