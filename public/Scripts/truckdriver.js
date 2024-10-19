document.getElementById('addDriver').addEventListener('click', () => {
   const api = getApi('truckdriver', 'add', 'post');
   
   let validity = 0;

   validity += isEmptyInput('addName', 'addName_e', 'addName_g');
   validity += isEmptyInput('addUsername', 'addUsername_e', 'addUsername_g');
   validity += isEmptyInput('addPassword', 'addPassword_e', 'addPassword_g');
   validity += isEmptyInput('addLicense', 'addLicense_e', 'addLicense_g');
   validity += isEmptyInput('addContact', 'addContact_e', 'addContact_g');
   validity += isEmptyInput('addAddress', 'addAddress_e', 'addAddress_g');

   if(validity == 6){
     load.on();

     $.ajax({
      type: 'post',
      url: api,
      data: $('form#addDriverForm').serialize(),
      success: res=> {
         load.off();
         getTruckDriverList();
         parseResult(res);
         clicked('closeButton');
      }, error: xhr => {
        load.off();
        parseResult(JSON.parse(xhr.responseText));
      }
     })
   }
});

function getTruckDriverList() {
  if ($.fn.DataTable.isDataTable('#driverList')) {
    $('#driverList').DataTable().clear().destroy();
  }

  $.ajax({
    type: "get",
    url: getApi('truckdriver', 'list', 'get'),
    dataType: "json",
    success: res => {
      $("#driverList").DataTable({
        data: res.result.data,
        columns: [
          { title: "Name", data: "name" },
          { title: "Username", data: "username" },
          { title: "License", data: "license" },
          { title: "Contact", data: "contact" },
          { title: "Address", data: "address" },
          { 
            title: "Action", 
            data: null, 
            render: data => {
              return `<div class="d-flex gap-1">
                <button data-bs-toggle="modal" data-bs-target="#updateDriverModal" onclick="UpdateDriver(
                  '${data.td_id}',
                  '${data.name}',
                  '${data.username}',
                  '${data.license}',
                  '${data.contact}',
                  '${data.address}'
                )" class="btn btn-outline-primary"><i class="fas fa-edit"></i></button>
                <button onclick="ViewDriver('${data.td_id}')" class="btn btn-outline-success"><i class="fas fa-eye"></i></button>
                <button onclick="RemoveDriver('${data.td_id}')" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
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
    error: xhr =>{
      load.off();
      parseResult(JSON.parse(xhr.responseText));
    }
  });
}


async function ViewDriver(id){
  isVisible('', 'closeView');
  isVisible('', 'driverView');
  window.location.href="#driverView";
  
  const csrfToken = await getCSRF();

  $.ajax({
    type: "post",
    url: getApi('truckdriver', 'details', 'post'),
    data: {"_token": csrfToken, "id": id},
    success: res=> {
      if(res.status == 'success'){
        const driver = res.result.data;
        const truck = driver.truck;
        setText('truckDriverAddress', driver.address);
        setText('truckDriverLicense', driver.license);
        setText('truckDriverName', driver.name);

        setText('dumpTruckModel', truck.model);
        setText('dumpTruckCanCarry', truck.can_carry);
        setText('dumpTruckDriver', driver.name);
      
        const fallback_image = getAsset('assets/img/logo.png');

        document.getElementById('viewDriverButton').href = "/viewdriver?id=" + driver.td_id;
        document.getElementById('viewTruckButton').href = "/viewtruck?id=" + truck.dt_id;

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
  isVisible('none', 'driverView');
}

function UpdateDriver(id, name, username, license, contact, address){
  setValue('updateName', name);
  setValue('updateUsername', username);
  setValue('updateLicense', license);
  setValue('updateContact', contact);
  setValue('updateAddress', address);
  setValue('updateId', id);
}


function ConfirmedUpdateDriver(){
  let validity = 0;

  validity += isEmptyInput('updateName', 'updateName_e', 'updateName_g');
  validity += isEmptyInput('updateUsername', 'updateUsername_e', 'updateUsername_g');
  validity += isEmptyInput('updateLicense', 'updateLicense_e', 'updateLicense_g');
  validity += isEmptyInput('updateContact', 'updateContact_e', 'updateContact_g');
  validity += isEmptyInput('updateAddress', 'updateAddress_e', 'updateAddress_g');

  if(validity == 5){
    load.on();

    $.ajax({
      type:"post",
      url: getApi('truckdriver', 'update', 'post'),
      data: $('form#updateDriverForm').serialize(),
      success: res=> {
        load.off();
        parseResult(res);
        clicked('closeButtonUpdate');
        getTruckDriverList();
      }, error: xhr=>{
        load.off();
        parseResult(JSON.parse(xhr.responseText));
      }
    })
  }
}
function RemoveDriver(id){
  confirmAction().then((confirmed) => {
      if(confirmed) {
        document.getElementById('deleteDriver').value = id;
        load.on();
         $.ajax({
            type:"post",
            url: getApi('truckdriver', 'delete', 'post'),
            data: $('form#deleteDriverForm').serialize(),
            success: res=>{
              load.off();
              parseResult(res);
              getTruckDriverList();
            },error: xhr=> {
              load.off();
              parseResult(JSON.parse(xhr.responseText));
            }
         });
      }
  });
}


document.getElementById('updateDriver').addEventListener('click', ()=> {
  ConfirmedUpdateDriver();
})

document.addEventListener('DOMContentLoaded', ()=> {
   getTruckDriverList();
});
