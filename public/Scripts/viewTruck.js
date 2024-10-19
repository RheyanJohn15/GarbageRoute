window.onload = () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');

    loadTruck(id).then(data => {
        console.log(data);
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