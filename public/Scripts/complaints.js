window.onload = () => {

    loadAllComplaints();
   
}


function stats(data){
    switch(data){
        case 0:
            return ['primary', 'New'];
        case 1:
            return ['info', 'In Progress'];
        case 2:
            return ['success', 'Resolved'];
        case 3:
            return ['black', 'Closed'];
        case 4:
            return ['danger', 'Cancelled'];
        case 5:
            return ['warning', 'pending'];
    }
}

function  loadAllComplaints(){
    if ($.fn.DataTable.isDataTable('#complaint-list')) {
        $('#complaint-list').DataTable().clear().destroy();
      }

    $.ajax({
        type: "GET",
        url: "/api/get/complaints/list",
        dataType: "json",
        success: res=> {
           const data = res.result.data;
            console.log(data);
           $('#complaint-list').DataTable({
            data:data,
            columns: [
                {title: 'Complainant', data: "comp_name"},
                {title: 'Email', data: null, 
                    render: data=> {
                        return `<a href="mailto:${data.comp_email}">${data.comp_email}</a>`
                    }
                },
                {title: 'Date', data: null, render: data => parseDate(data.created_at) },
                {title: 'Nature of Complaint', data: "comp_nature"},
                {title: 'Remarks', data: null,
                    render: data=>{
                        return data.comp_remarks.length > 30 ? data.comp_remarks.substring(0, 30) + "......" : data.comp_remarks;
                    }
                },
                {title: 'Status', data: null,
                    render: data => {
                        const status = stats(data.comp_status);
                        return `<span class="badge badge-${status[0]}">${status[1]}</span>`;
                    }
                },
                {title: 'Action', data: null, 
                    render: data=> {
        
                        return `<div class="d-flex gap-2">
                        <button onclick="updateComplaint('${data.comp_id}')" data-bs-toggle="modal" data-bs-target="#editComplaint" class="btn btn-icon btn-round btn-primary"><i class="fa fa-edit"></i></button>
                        <button onclick="viewDetails(
                        '${data.comp_name}',
                        '${data.comp_email}',
                        '${data.comp_contact}',
                        '${data.comp_nature}',
                        '${data.comp_remarks}',
                        '${data.comp_status}',
                        '${data.created_at}'
                        )" data-bs-toggle="modal" data-bs-target="#viewComplaint" class="btn btn-icon btn-round btn-info"><i class="fa fa-eye"></i></button>
                        <button onclick="removeComplaint('${data.comp_id}')" class="btn btn-icon btn-round btn-danger"><i class="fa fa-trash"></i></button>
                        </div>`;
                    }
                },
            ]
           })
       
        }, error: xhr => console.log(xhr.responseText)
    });
}

function removeComplaint(id){
    confirmAction().then(async confirm => {
        if(confirm){
            load.on();
            const csrf = await getCSRF();
            $.ajax({
                type: "POST",
                url: "/api/post/complaints/remove",
                data: {"_token": csrf, "comp_id": id},
                success: res=> {
                    load.off();
                    parseResult(res);
                    loadAllComplaints();
                }, error: xhr => console.log(xhr.responseText)
            });
        }
    });
}

function updateComplaint(id){
    setValue('comp_id', id);
}


document.getElementById('updateComplaint').addEventListener('submit', (e)=> {
  e.preventDefault();

  load.on();
  $.ajax({
    type: "POST",
    url: "/api/post/complaints/update",
    data: $('#updateComplaint').serialize(),
    success: res=> {
        load.off();
        parseResult(res);
        loadAllComplaints();
        clicked('closeButton');
    }, error: xhr=> console.log(xhr.responseText)
  })
});

function viewDetails(name, email, contact, nature, remarks, status, date){
    setText('complainant_span', name);
    setText('email_span', email);
    setText('contact_span', contact);
    setText('nature_span', nature);
    setText('date_span', date);
    setText('status_span', stats(+status)[1]);
    setText('remarks_content', remarks);
}

