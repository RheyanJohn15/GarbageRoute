let driverId;

window.onload = ()=> {
   const id = localStorage.getItem('driverId');
   driverId = id;

   loadAllRoute();
}

async function loadAllRoute(){
    const csrf = await getCSRF()
    $.ajax({
        type: "POST",
        url: `/api/post/drivers/getroute`,
        data: {"_token": csrf, "driverid": driverId},
        success: res=> {
          const routeList = document.getElementById('routeList');
          routeList.innerHTML = '';
        
          res.result.data.forEach((data)=> {
            routeList.innerHTML += `<a href="/user/driver/routejourney?id=${data.r_id}" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                <img src="${getAsset('assets/img/marker.png')}" alt="twbs" width="32" height="32" class="rounded-circle flex-shrink-0">
                <div class="d-flex gap-2 w-100 justify-content-between">
                  <div>
                    <h6 class="mb-0">${data.r_name}</h6>
                    <p class="mb-0 opacity-75">${parseDate(data.r_schedule)}</p>
                  </div>
                  <small class="opacity-50 text-nowrap">now</small>
                </div>
              </a>
           `;
          });
        }, error: xhr=> console.log(xhr.responseText)
    });
}