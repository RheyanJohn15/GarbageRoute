function getApi(data, method, type){
    var url = window.location.origin;

    if(type == 'post'){
        return `${url}/api/post/${data}/${method}`;
    }
    
    return `${url}/api/get/${data}/${method}`;
}

function getAsset(asset){
    var url = window.location.origin;

    return `${url}/${asset}`;
}

function setText(id, value){
   document.getElementById(id).textContent = value;
}

function setHtml(id, value){
    document.getElementById(id).innerHTML = value;
}
function setImage(id, value){
    document.getElementById(id).src = value;
}

async function getCSRF() {
    try {
        const url = window.location.origin;
        const response = await fetch(`${url}/csrf-token`);
        const token = await response.text();
        return token;
    } catch (error) {
        console.error('Error fetching CSRF token:', error);
        return null;
    }
}

function isEmptyInput(inp, text, group){
    const input = document.getElementById(inp);
    const text_error = document.getElementById(text);
    const div_group = document.getElementById(group);

    if(input.value === '' || input.value == '0'){
        text_error.style.display = '';
        div_group.classList.add('border', 'border-danger');
        return 0;
    }

    text_error.style.display = 'none';
    div_group.classList.remove('border', 'border-danger');

    return 1;
}

const load = {
    off: ()=> {
        document.getElementById('mainloader').style.display = 'none';
    },
    on: ()=> {
        document.getElementById('mainloader').style.display = 'grid';
    }
}

function clicked(id){
    document.getElementById(id).click();
}

function isVisible(visible, id){
    document.getElementById(id).style.display = visible;
}

function setValue(id, value){
    document.getElementById(id).value = value;
}

function parseDate(dateString) {
  const date = new Date(dateString);
  const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  const hours = date.getHours();
  const minutes = date.getMinutes();
  const ampm = hours >= 12 ? "PM" : "AM";
  const hours12 = hours % 12 || 12;

  return `${monthNames[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()} ${hours12}:${minutes.toString().padStart(2, "0")} ${ampm}`;
}

function parseResult(res){
    if(res.status == 'success'){
        $.notify({'message': res.result.response,'icon': 'icon-check' }, {
       type: 'success',
       placement: {
         from: 'top',
         align: 'right',
       },
       time: 1000,
       delay: 2000,
       });
      }else{
       $.notify({'message' : res.message, 'icon' : 'fas fa-exclamation-triangle'}, {
          type: 'danger',
          placement: {
            from: 'top',
            align: 'right',
          },
          time: 1000,
          delay: 2000,
          });
      }
}

function confirmAction(){
    return swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        buttons: {
            cancel: {
                visible: true,
                text: "No, cancel!",
                className: "btn btn-danger",
            },
            confirm: {
                text: "Yes, delete it!",
                className: "btn btn-success",
            },
        },
    }).then((willDelete) => {
        return willDelete; 
    });
}

function getVal(id){
    return document.getElementById(id).value;
}

function isShow(id, status, type = 'flex'){
    const div = document.getElementById(id);

    switch(type){
        case 'flex':
            div.classList.remove(status ? 'd-none' : 'd-flex');
            div.classList.add(status ? 'd-flex': 'd-none');
            break;
        default:
            status ? div.classList.remove('d-none') : null;
            status ? null : div.classList.add('d-none');
            break;
    }
}

function showError(message){
    $.notify({'message' : message, 'icon' : 'fas fa-exclamation-triangle'}, {
        type: 'danger',
        placement: {
          from: 'top',
          align: 'right',
        },
        time: 1000,
        delay: 2000,
        });
}


//Logout Function
document.getElementById('logout').addEventListener('click', async ()=> {
    load.on();

    const csrf = await getCSRF();
    $.ajax({
        type: "POST",
        url: "/api/post/user/logout",
        data: {"_token": csrf},
        success: res=> {
            load.off();
            parseResult(res);
            window.location.href="/auth/login";
        }, error: xhr=> console.log(xhr.responseText)
    })
});