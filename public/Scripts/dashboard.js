window.onload = () => {
    $.ajax({
        type: "GET",
        url: "/api/get/adminaccount/dashboard",
        dataType: "json",
        success: res=> {
            const data = res.result.data;
            
            setText('truckNum', data[0].length);
            setText('driverNum', data[1].length);
            setText('routeNum', data[2].length);
            setText('complaintNum', data[3].length);
        }, error: xhr=> console.log(xhr.responseText)
    })
}