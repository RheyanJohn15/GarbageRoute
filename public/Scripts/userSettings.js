function changeDetect(){
    document.getElementById('updateDetailsDiv').classList.remove('d-none');
}

window.onload = async () => {
    const driverData = await getGlobalUser();
    loadDetails(driverData);
}


function loadDetails(driverData){
    const data = driverData.data;
    setText('headerAssigned', data.truck == 'null' ? "No Truck Assigned" : "Assigned Truck Details")
    setValue('driverId', data.td_id);
    setValue('driverName', data.name);
    setValue('driverLicenseInp', data.license);
    setValue('driverAddress', data.address);
    setValue('driverContact', data.contact);
    setValue('driverUsername', data.username);
    setValue('changePassId', data.td_id);
    setValue('changeProfileId', data.td_id);
 
    if(data.truck != "null"){
        setValue('truckModel', data.truck.model);
        setValue('truckCapacity', data.truck.can_carry);  
        setValue('truckUpdateId', data.truck.dt_id);
        setValue('uploadTruckId', data.truck.dt_id);
        setValue('truckPlateNumber', data.truck.plate_num)
        setImage('truckImage', `/UserPics/Truck/${data.truck.profile_pic}`);

        reactive('truckModel', false);
        reactive('truckCapacity', false);
        reactive('saveTruckChanges', false);
        reactive('truckPlateNumber', false);    
    }else{
        setText('truckModel', "Unavailable");
        setText('truckCapacity',"Unavailable");
    
    }

    reactive('driverName', false);
    reactive('driverLicenseInp', false);
    reactive('driverAddress', false);
    reactive('driverContact', false);
    reactive('driverUsername', false);
}

document.getElementById('driverDetailsForm').addEventListener('submit', (e)=> {
    e.preventDefault();
    let validity = 0;

    const inputs = [
        ['driverName', 'driverNameE'],
        ['driverAddress', 'driverAddressE'],
        ['driverLicenseInp', 'driverLicenseE'],
        ['driverUsername', 'driverUsername'],
        ['driverContact', 'driverContactE']
    ];

    inputs.forEach(data => {
        validity += checkInp(data[0], data[1]);
    });

    if(validity == 5){
        load.on();

        $.ajax({
            type: "POST",
            url: "/api/post/drivers/update",
            data: $('#driverDetailsForm').serialize(),
            success: res=> {
                load.off();
                parseResult(res);
            }, error: xhr => {
                load.off();
                parseResult(JSON.parse(xhr.responseText));
            }
        });
    }
});


document.getElementById('changePassBtn').addEventListener('click', ()=> {
    document.getElementById('changePassForm').requestSubmit();
});

document.getElementById('changePassForm').addEventListener('submit', e => {
    e.preventDefault();

    const inputs = [
        ['currentPassword', 'currentPassE'],
        ['newPassword', 'newPassE'],
        ['confirmPassword', 'confirmPassE']
    ];

    let validity = 0;

    inputs.forEach(data => {
        validity += checkInp(data[0], data[1]);
    });

    if(validity == 3){
        if(getVal('newPassword') == getVal('confirmPassword')){
            load.on();

            $.ajax({    
                type: "POST",
                url: "/api/post/drivers/changepass",
                data: $('#changePassForm').serialize(),
                success: res=> {
                    load.off();
                    parseResult(res);
                    clicked('closeChangePass');
                },error: xhr => {
                    console.log(xhr.responseText);
                    load.off();
                    parseResult(JSON.parse(xhr.responseText));
                    clicked('closeChangePass');
                }
            });
        }else{
            document.getElementById('confirmPassE').classList.remove('d-none');
            document.getElementById('newPassE').classList.remove('d-none');
            setText('confirmPassE', "New Password does not match");
            setText('newPassE', "New Password does not match");
        }
    }
});

document.getElementById('changeProfilePicBtn').addEventListener('click', ()=> {
    document.getElementById('changeProfilePicForm').requestSubmit();
});

document.getElementById('changeProfilePicForm').addEventListener('submit', e=> {
    e.preventDefault();

    load.on();
    const formData = new FormData($('#changeProfilePicForm')[0]);
    $.ajax({
        type:"POST",
        url: "/api/post/drivers/changeprofilepic",
        contentType: false,
        processData: false,
        data: formData,
        success: res=> {
            load.off();
            parseResult(res);
            clicked('closeChangeProfilePic');
            setImage('profilePicSettings', `/UserPics/Driver/${res.result.data}`);
            setImage('userProfilePic', `/UserPics/Driver/${res.result.data}`)
        },error: xhr => {
            console.log(xhr.responseText);
            load.off();
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeChangeProfilePic');
        }
    })
});

document.getElementById('uploadTruckImageBtn').addEventListener('click', ()=> {
    document.getElementById('uploadTruckImageForm').requestSubmit();
});

document.getElementById('uploadTruckImageForm').addEventListener('submit', e=>{
    e.preventDefault();

    load.on();
    const formData = new FormData($('#uploadTruckImageForm')[0]);
    $.ajax({
        type:"POST",
        data: formData,
        processData: false,
        contentType: false,
        url: "/api/post/drivers/changetruckimage",
        success: res=> {
            load.off();
            parseResult(res);
            clicked('closeUploadTruckImage');
            setImage('truckImage', `/UserPics/Truck/${res.result.data}`);
        }, error: xhr=> {
            console.log(xhr.responseText);
            load.off();
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeUploadTruckImage');
        }
    })
});

document.getElementById('truckUpdateForm').addEventListener('submit', e=> {
    e.preventDefault();
    
    let validity = 0;
    const inputs = [
        ['truckModel', 'truckModelE'],
        ['truckCapacity', 'truckCapacityE'],
        ['truckPlateNumber', 'truckPlateNumberE']
    ];

    inputs.forEach(data=> {
        validity += checkInp(data[0], data[1]);
    });

    if(validity == 3){
        load.on();

        $.ajax({
            type: "POST",
            data: $('#truckUpdateForm').serialize(),
            url: "/api/post/drivers/updatetruck",
            success: res=> {
                load.off();
                parseResult(res);
            }, error: xhr=> {
                console.log(xhr.responseText);
                load.off();
                parseResult(JSON.parse(xhr.parseResult));
            }
        })
    }
});