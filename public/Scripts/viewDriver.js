window.onload = () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    loadDriver(id).then(data=> {
        setText('name', data.name);
        setText('username', data.username);
        setText('license', data.license);
        setText('contact', data.contact);
        setText('address', data.address);

        setImage('driverImage', data.profile_pic != null ? '/UserPics/Driver/' + data.profile_pic : '/assets/img/logo.png');
    });
}

async function loadDriver(id){
    const response = await fetch('/api/get/truckdriver/details?id='+ id, {
        method: "GET",
        headers: {"Content-Type": "application/json"}
    });

    const result = await response.json();

    return result.result.data;
}