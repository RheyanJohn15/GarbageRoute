window.onload = () => {
    $('#tableLogs').DataTable( {
        ajax: {
            url: '/api/get/adminaccount/getallactivity',
            dataSrc: 'result.data'
        },
        columns: [
            {title: "User Account", data: "acc_name"},
            {title: "Action", data: "action"},
            {title: "Result", data: "result"},
            {title: "Routes", data: "route"},
            {title: "Timestamp", data: null,
                render: data => {
                    return formatDateTime(data.created_at)
                }
            }
         ]
    } );
}

function formatDateTime(timestamp) {
    const date = new Date(timestamp);

    // Define options for date formatting
    const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = date.toLocaleDateString('en-US', dateOptions);

    // Format the time in 12-hour format with AM/PM
    const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: true };
    const formattedTime = date.toLocaleTimeString('en-US', timeOptions);

    // Combine date and time with the desired format
    return `${formattedDate} / ${formattedTime}`;
}