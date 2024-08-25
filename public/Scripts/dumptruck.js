function getDumpTruckList() {
    if ($.fn.DataTable.isDataTable('#truckList')) {
      $('#truckList').DataTable().clear().destroy();
    }
  
    $.ajax({
      type: "get",
      url: getApi('dumptruck', 'list', 'get'),
      dataType: "json",
      success: res => {
        $("#truckList").DataTable({
          data: res.result.data,
          columns: [
            { title: "Truck Model", data: "model" },
            { title: "Can Carry", data: "can_carry" },
            { title: "Driver", data: "driver" },
            { 
              title: "Action", 
              data: null, 
              render: data => {
                return `<div class="d-flex gap-1">
                  <button data-bs-toggle="modal" data-bs-target="#updateTruckModal" onclick="UpdateTruck(
                    '${data.dt_id}',
                    '${data.model}',
                    '${data.can_carry}',
                    '${data.driver_id}'
                  )" class="btn btn-outline-primary"><i class="fas fa-edit"></i></button>
                  <button onclick="ViewDumpTruck('${data.dt_id}')" class="btn btn-outline-success"><i class="fas fa-eye"></i></button>
                  <button onclick="RemoveTruck('${data.dt_id}')" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                </div>`;
              },
              orderable: false,  // Disable sorting for the Action column
              searchable: false  // Disable searching for the Action column
            }
          ],
          pageLength: 5,
          initComplete: function () {
            this.api().columns().every(function (index) {
              // Apply the select filter only to columns that are not the Action column
              if (this.header().textContent !== 'Action') {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());
  
                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });
  
                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              }
            });
          },
        });
      },
      error: xhr => {
        load.off();
        parseResult(JSON.parse(xhr.responseText));
      }
    });
  }


  document.getElementById('addTruck').addEventListener('click', () => {
     let validity = 0;

     validity += isEmptyInput('addModel', 'addModel_e', 'addModel_g');
     validity += isEmptyInput('addCanCarry', 'addCanCarry_e', 'addCanCarry_g');
     validity += isEmptyInput('addDriver', 'addDriver_e', 'addDriver_g');

     if(validity == 3){
      load.on();

      $.ajax({
        type: "POST",
        url: getApi('dumptruck', 'add', 'post'),
        data: $('form#addTruckForm').serialize(),
        success: res=>{
         load.off();
         parseResult(res);
         clicked('closeAddModal');
         getDumpTruckList();
        }, error: xhr => {
          load.off();
          parseResult(JSON.parse(xhr.responseText));
        }
      });
     }
  });

  function UpdateTruck(id, model, can_carry, driver_id){
       setValue('updateModel', model);
       setValue('updateCanCarry', can_carry);
       setValue('updateDriver', driver_id);
       setValue('updateId', id);
  }

 function RemoveTruck(id){
    confirmAction().then( async confirm => {
       if(confirm){
        load.on();
        const csrfToken = await getCSRF();
        $.ajax({
           type:"POST",
           url: getApi('dumptruck', 'delete', 'post'),
           data: {"_token": csrfToken, "id" : id},
           success: res=> {
             load.off();
             parseResult(res);
             getDumpTruckList();
           }, error: xhr => {
            load.off();
            parseResult(JSON.parse(xhr.responseText));
           }
        });
       }
    });
  }

  
async function ViewDumpTruck(id){
  isVisible('', 'closeView');
  isVisible('', 'truckView');
  window.location.href="#truckView";
  
  const csrfToken = await getCSRF();

  $.ajax({
    type: "post",
    url: getApi('dumptruck', 'details', 'post'),
    data: {"_token": csrfToken, "id": id},
    success: res=> {
      if(res.status == 'success'){
        const truck = res.result.data;
        const driver = truck.driver;
        setText('truckDriverAddress', driver.address);
        setText('truckDriverLicense', driver.license);
        setText('truckDriverName', driver.name);

        setText('dumpTruckModel', truck.model);
        setText('dumpTruckCanCarry', truck.can_carry);
        setText('dumpTruckDriver', driver.name);
      
        const fallback_image = getAsset('assets/img/logo.png');

        setImage('dumpTruckProfile', truck.profile_pic == null ? fallback_image : getAsset(`assets/user/${truck.profile_pic}`));
        setImage('truckDriverProfile', driver.profile_pic == null ? fallback_image : getAsset(`assets/user/${driver.profile_pic}`));
      }
    }, error: xhr => {
      load.off();
      parseResult(JSON.parse(xhr.responseText));
    }
  });
}

function CloseView(){
  isVisible('none', 'closeView');
  isVisible('none', 'truckView');
}

  document.getElementById('updateTruck').addEventListener('click', ()=> {
    let validity = 0;

    validity += isEmptyInput('updateModel', 'updateModel_e', 'updateModel_g');
    validity += isEmptyInput('updateCanCarry', 'updateCanCarry_e', 'updateCanCarry_g');
    validity += isEmptyInput('updateDriver', 'updateDriver_e', 'updateDriver_g');

    if(validity == 3){
      load.on();

      $.ajax({
        type: "POST",
        url: getApi('dumptruck', 'update', 'post'),
        data: $('form#updateTruckForm').serialize(),
        success: res=> {
            load.off();
            parseResult(res);
            getDumpTruckList();
            clicked('closeUpdateModal');
        }, error: xhr => {
          load.off();
          parseResult(JSON.parse(xhr.responseText));
        }
      });
    }
  });


  document.addEventListener('DOMContentLoaded', () => {
    getDumpTruckList();
  });