window.onload = () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    loadDriver(id).then(data=> {
        console.log(data);
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