
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
    setValue('changeProfileAccId', data.acc_id);
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


document.getElementById('changeProfileModal').addEventListener('click', ()=> {
    const avatarList = document.getElementById('avatarList');
    avatarList.innerHTML = '';

    for(let i = 1; i <= 20; i++){
        avatarList.innerHTML += `<div onclick="selectAvatar('${i}', this)" class="avatar avatar-xl cursor-pointer position-relative allAvatars">
                            <img src="/assets/img/avatars/Avatar${i}.svg" alt="avatar" class="avatar-img rounded-circle">
                            <div class="bg-success text-muted d-none opacity-50 w-100 h-100 position-absolute top-0 left-0 d-flex justify-content-center align-items-center flex-column">
                            <i class="far fa-check-circle  fs-2"></i>
                            <small>selected</small>
                            </div>
                            </div>`
    }

});

function selectAvatar(num, element){

    const allAvatars = document.querySelectorAll('.allAvatars');

    allAvatars.forEach(data=> {
        const dataChild = data.children[1];

        dataChild.classList.add('d-none');
        data.classList.remove('border', 'border-2', 'border-success');
    });

   const select = element.children[1];
   select.classList.remove('d-none');
   element.classList.add('border', 'border-2', 'border-success');

   setValue('changeProfileSelectedAvatar', `Avatar${num}.svg`);
}

document.getElementById('updateProfilePictureBtn').addEventListener('click', ()=> {
    document.getElementById('changeProfileForm').requestSubmit();
});

document.getElementById('changeProfileForm').addEventListener('submit', (e)=> {
    e.preventDefault();

    load.on();

    $.ajax({
        type: "POST",
        url: "/api/post/adminaccount/changeavatar",
        data: $('#changeProfileForm').serialize(),
        success: res=> {
            load.off();
            parseResult(res);
            clicked('closeProfilePicture');
            const profilePic = `/assets/img/avatars/${res.result.data.acc_profile}`;
            
            setImage('userProfilePic', profilePic);
            setImage('userProfilePicMobile', profilePic);
            setImage('accountAvatar', profilePic);
            setImage('changeProfileSelected', profilePic);
        },error: xhr => console.log(xhr.responseText)
    })
});