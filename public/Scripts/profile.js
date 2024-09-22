window.onload = ()=> {
    loadAllAdmins();
}

async function loadAllAdmins(){
    const user = await getGlobalUser();
    const userInfo = user.data;
    setText('profileName', userInfo.acc_name);
    setText('profileType', userInfo.acc_type);

    setImage('profileAvatar', profilePic)
    $.ajax({
        type: "GET",
        url: `/api/get/adminaccount/getalladmin?type=${userInfo.acc_type}`,
        dataType:"json",
        success: res=> {
            if($.fn.DataTable.isDataTable('#admin-list')){
                $('#admin-list').DataTable().destroy();
            }

            $('#admin-list').DataTable({
                data: res.result.data,
                columns: [
                    {title: "Name", data: 'acc_name'},
                    {title: "Username", data: 'acc_username'},
                    {title: "Type", data: "acc_type"},
                    {title: "Action", data: null, render: data=> {
                        return  `<div class="d-flex align-items-center gap-2">
                        <button onclick="updateAdmin('${data.acc_id}')" data-bs-toggle="modal" data-bs-target="#updateAdminModal" class="btn btn-info btn-round">
                        <span class="btn-label"><i class="fa fa-edit"></i></span>
                        </button>
                         <button onclick="removeAdmin('${data.acc_id}')" class="btn btn-danger btn-round">
                        <span class="btn-label"><i class="fa fa-trash"></i></span>
                        </button>

                         <button data-bs-toggle="modal" data-bs-target="#changePassModal" onclick="changePass('${data.acc_id}')" class="btn btn-warning btn-round">
                            <span class="btn-label"><i class="fa fa-asterisk"></i></span>
                        </button>
                                </div>`
                    }}
                ]
            })
        }, error: xhr=> console.log(xhr.responseText)
    });
}

document.getElementById('addAdminBtn').addEventListener('click', ()=> {
    document.getElementById('addAdminForm').requestSubmit();
});

document.getElementById('addAdminForm').addEventListener('submit', (e)=> {
    e.preventDefault();

    load.on();

    $.ajax({
        type: "POST",
        url: "/api/post/adminaccount/add",
        data: $('#addAdminForm').serialize(),
        success: res=> {
            load.off();
            parseResult(res);
            loadAllAdmins();
            clicked('closeAddAdminModal');
        }, error: xhr=> {
            load.off();
            console.log(xhr.responseText);
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeAddAdminModal');
        }
    });
});

function removeAdmin(id){
    confirmAction().then( async confirm=> {
        if(confirm){
            load.on();

            const csrf = await getCSRF();

            $.ajax({
                type: "POST",
                url: "/api/post/adminaccount/delete",
                data: {"_token": csrf, "id": id},
                success: res=> {
                    load.off();
                    parseResult(res);
                    loadAllAdmins();
                }, error: xhr=> {
                    load.off();
                    parseResult(JSON.parse(xhr.responseText))
                }
            })
        }
    })
}

function updateAdmin(id){
    $.ajax({
        type: "GET",
        url: `/api/get/adminaccount/details?id=${id}`,
        dataType: "json",
        success: res=> {
            setValue('updateAdminId', id);
            setValue('updateAdminName', res.result.data.acc_name);
            setValue('updateAdminUsername', res.result.data.acc_username);
            
            reactive('updateAdminName', false);
            reactive('updateAdminUsername', false);
        }, error: xhr=> console.log(xhr.responseText)
    })
}

document.getElementById('updateAdminBtn').addEventListener('click', ()=> {
    document.getElementById('updateAdminForm').requestSubmit(); 
});
document.getElementById('updateAdminForm').addEventListener('submit', e=> {
    e.preventDefault();

    load.on();

    $.ajax({
        type: "POST",
        url: "/api/post/adminaccount/update",
        data: $("#updateAdminForm").serialize(),
        success: res=> {
            load.off();
            parseResult(res);
            clicked('closeUpdateAdminModal');
            loadAllAdmins();
        },error: xhr=> {
            load.off();
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeUpdateAdminModal');
        }
    })
});

function changePass(id){
    setValue('changePassAdminId',id);
}

document.getElementById('changePassBtn').addEventListener('click', ()=> {
    document.getElementById('changePassForm').requestSubmit();
});

document.getElementById('changePassForm').addEventListener('submit', e=> {
    e.preventDefault();

    load.on();
    $.ajax({
        type: "POST",
        url: "/api/post/adminaccount/changepassadmin",
        data: $('#changePassForm').serialize(),
        success: res=> {
            load.off();
            parseResult(res);
            clicked('closeChangePassModal');
        },error: xhr => {
            load.off();
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeChangePassModal');
        }
    })
})