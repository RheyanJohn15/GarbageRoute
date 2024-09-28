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

function reactive(id, value){
    const element = document.getElementById(id);
    if(element){
        element.disabled = value;
    }
    
}
function setText(id, value){
   const element = document.getElementById(id);
   if(element){
    element.textContent = value;
   }
 
}

function setHtml(id, value){
    const element = document.getElementById(id);
    if(element){
        element.innerHTML = value;
    }
 
}
function setImage(id, value){
    const element = document.getElementById(id);
    if(element){
        element.src = value;
    }
   
}

function getRandomColor(colors) {
    const randomIndex = Math.floor(Math.random() * colors.length);
    return colors[randomIndex];
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
        document.getElementById('mainloader').style.display = 'flex';
    }
}

function clicked(id){
    const element = document.getElementById(id);
    if(element){
        element.click();
    }
    
}

function isVisible(visible, id){
    const element = document.getElementById(id);
    if(element){
         element.style.display = visible;
    }
   
}

function setValue(id, value){
    const element = document.getElementById(id);
    if(element){
        element.value = value;
    }
    
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
    const element = document.getElementById(id);
    if(element){
        return element.value;
    }
    
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


function parseSchedule(type){
    switch(type){
        case "daily":
            const radios = document.querySelectorAll('input[name="duration"]');
            let selectedValue = null;
            radios.forEach(radio => {
                if (radio.checked) {
                    selectedValue = radio.value;
                }
            });

            let duration;
            if(selectedValue == 'noDuration'){
                duration = selectedValue;
            }else{
                const startDateDaily = getVal('startDateDaily');
                const endDateDaily = getVal('endDateDaily');
                if(startDateDaily == '' || endDateDaily == ''){
                    return ['error', 'Start Date or End Date Duration is Empty'];
                }
                duration = `${selectedValue}**${startDateDaily}**${endDateDaily}`;
            }
            
            return `daily**${duration}`;
        case "weekly":
            const checkboxes = document.querySelectorAll('input[name="weeklyDays"]');

            let selectedDays = [];
            checkboxes.forEach((checkbox) => {
                // If the checkbox is checked, push its value into the array
                if (checkbox.checked) {
                  selectedDays.push(checkbox.value);
                }
              });

              if(selectedDays.length == 0){
                return ['error', 'No Days Selected'];
              }
              
              let parseData = '';

              selectedDays.forEach((data)=>{
                parseData += `${data}**`;
              });
            return `weekly**${parseData}`;
        case "monthly":
            const monthlySelect = getVal('selectMonthlyOptions');
            
            let monthly;
            if(monthlySelect == 'setCustomDate'){
                const date = getVal('monthlyCustomDate');
                if(date == ''){
                    return ['error', 'No Date Selected'];
                }
                
                monthly = `setCustomDate**${date}`;
                
            }else{
                monthly = monthlySelect;
            }

            return `monthly**${monthly}`;
        default:
            const selectedData = getVal('selectWeekendHolidays');
            return `weekendholiday**${selectedData}`;
    }
}

function getUrlquery(id){
    const url = new URL(window.location.href);
    const queryParams = url.searchParams;

    return queryParams.get(id);
}



//Logout Function
const logoutBtn = document.getElementById('logout');
if(logoutBtn){
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
}


const driverLogoutBtn = document.getElementById('driverlogout');

if(driverLogoutBtn){
    driverLogoutBtn.addEventListener('click', async ()=> {
        load.on();
        const csrf = await getCSRF();
        $.ajax({
            type: "POST",
            url: "/api/post/drivers/logout",
            data: {"_token": csrf},
            success: res=> {
                load.off();
                parseResult(res);
                window.location.href="/auth/driver/login";
            }, error: xhr=> console.log(xhr.responseText)
        })
    });
}