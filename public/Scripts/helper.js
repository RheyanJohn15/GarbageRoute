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

    if(input.value === '' || inp.value == '0'){
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

function parseResult(res){
    if(res.status == 'success'){
        $.notify({'message': res.result.response,'icon': 'icon-check' }, {
       type: 'success',
       placement: {
         from: 'top',
         align: 'right',
       },
       time: 1000,
       delay: 0,
       });
      }else{
       $.notify(res.message, {
          type: 'error',
          placement: {
            from: 'top',
            align: 'right',
          },
          time: 1000,
          delay: 0,
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


