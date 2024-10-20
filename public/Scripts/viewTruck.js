window.onload = () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    loadTruck(id).then(data => {
        setText('model', data.model);
        setText('capacity', data.can_carry);
        setText('plate_num', data.plate_num);
        setText('driver', data.driver.name);

        setImage('truckImage', data.profile_pic != null ? '/UserPics/Truck/' + data.profile_pic : '/assets/img/logo.png');
    });
}

async function loadTruck(id){
    const response = await fetch('/api/get/dumptruck/details?id='+ id, {
        method: "GET",
        headers: {"Content-Type": "application/json"}
    });

    const result = await response.json();

    return result.result.data;
}