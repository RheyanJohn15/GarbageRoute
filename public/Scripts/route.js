mapboxgl.accessToken = 'pk.eyJ1IjoicmhleWFuIiwiYSI6ImNsenpydzA4eDFnajUyanB4M2V3NjZjdDUifQ.7cXuHyW86hXStq6Mh2kF8Q';
import { geoDataSilay } from "./SilayBrgyGeoData.js ";

const map = new mapboxgl.Map({
  container: 'map', // container ID
  style: 'mapbox://styles/mapbox/streets-v12', // style URL
  center: [123.0585, 10.8039], // center set to Silay City, Negros Occidental
  zoom: 11, // starting zoom
});
const zones = {
    type: 'FeatureCollection',
    features: geoDataSilay
  };

map.on('load', () => {
    map.addSource('zones', {
      type: 'geojson',
      data: zones,
    });

    map.addLayer({
      id: 'zones',
      type: 'fill',
      source: 'zones',
      layout: {},
      paint: {
        'fill-color': ['get', 'color'],
        'fill-opacity': 0.5,
      },
    });
  });

let silayBrgyTable;
document.getElementById('updateZonesBtn').addEventListener('click', ()=> {
    getAllBrgyList();
    loadZones();
})

function getAllBrgyList() {
    $.ajax({
        type: 'GET',
        url: "/api/get/brgy/list",
        dataType: "json",
        success: res=> {
           loadBrgy(res.result.data);

        }, error: xhr=> console.log(xhr.responseText)
    });
}

function loadBrgy(data){
    if(!$.fn.DataTable.isDataTable('#silayBrgy')){
        silayBrgyTable = $('#silayBrgy').DataTable({
            data: data,
            pageLength: 5,
            columns: [
                {'title': "Baranggay", data: "brgy_name"},
                {'title': "Assigned Zone", data: null,
                    render: data => {
                        return data.zone_id == null ? 'Not Set' : data.zone_id
                    }
                }
            ]
        });
    }else{
        silayBrgyTable.clear().rows.add(data).draw();
    }

}


function loadZones(){
    $.ajax({
        type: 'GET',
        url: "/api/get/zone/list",
        dataType: "json",
        success: res=> {
            const zones = document.getElementById('filterByZones');
            const addZones = document.getElementById('selectZones');
            zones.innerHTML = `<option value="all">All Baranggays</option>`;
            addZones.innerHTML = '<option value="" disabled selected>------Select a Zone-------</option>';

            res.result.data.forEach( z => {
                zones.innerHTML += `<option value="${z.zone_id}">${z.zone_name}</option>`;
                addZones.innerHTML += `<option value="${z.zone_id}">${z.zone_name}</option>`;
              });
        }, error: xhr=> console.log(xhr.responseText)
    })
};


document.getElementById('filterByZones').addEventListener('change', e => {
    $.ajax({
        type: "GET",
        url: `api/get/brgy/filterbyzone?zone_id=${e.target.value}`,
        dataType:"json",
        success: res=> {
            loadBrgy(res.result.data)
        }, error: xhr=> console.log(xhr.responseText)
    });
});

function show(){
    document.getElementById('filterByZones').style.display = 'block';
    console.log('as')
}

document.getElementById('selectZones').addEventListener('change', e => {
    $.ajax({
        type: "GET",
        url: `api/get/brgy/list`,
        dataType:"json",
        success: res=> {
            const brgyHolder = document.getElementById('assignBrgyHolder');
            brgyHolder.innerHTML = '';

            res.result.data.forEach(data=> {
                brgyHolder.innerHTML +=  `<div class="form-check">
                        <input class="form-check-input" name="brgy" ${data.zone_id == e.target.value ? 'checked' : ''}
                        type="checkbox" ${data.zone_id == null ? '' : (data.zone_id == e.target.value ? '' : 'disabled')}
                        value="${data.brgy_id}" id="addBrgyZone${data.brgy_id}">
                        <label class="form-check-label" for="addBrgyZone${data.brgy_id}">
                          ${data.brgy_name} ${data.zone_id == null  ? '' : '(' + data.zone.zone_name + ')'}
                        </label>
                      </div>`
            });
        }, error: xhr=> console.log(xhr.responseText)
    });
});


document.getElementById('addAssignBaranggayToZone').addEventListener('click', e=> {
        const parent = e.target.parentNode;
        parent.classList.remove('d-flex');
        parent.classList.add('d-none');

        const addDiv = document.getElementById('addAssignedBrgyDiv');
        const table = document.getElementById('brgyListDiv');
        const closeDiv = document.getElementById('closeAssignBaranggayToZone').parentNode;
        const filter = document.getElementById('filterByZones').parentNode;
        const submit = document.getElementById('addBrgyToZone');

        addDiv.classList.remove('d-none');
        table.classList.add('d-none');
        closeDiv.classList.remove('d-none');
        closeDiv.classList.add('d-flex');
        filter.classList.add('d-none');
        submit.classList.remove('d-none');
});

document.getElementById('closeAssignBaranggayToZone').addEventListener('click', e=> {
    const parent = e.target.parentNode;
    parent.classList.remove('d-flex');
    parent.classList.add('d-none');

    const addDiv = document.getElementById('addAssignedBrgyDiv');
    const table = document.getElementById('brgyListDiv');
    const addBtn = document.getElementById('addAssignBaranggayToZone').parentNode;
    const filter = document.getElementById('filterByZones').parentNode;
    const submit = document.getElementById('addBrgyToZone');

    addDiv.classList.add('d-none');
    table.classList.remove('d-none');
    addBtn.classList.remove('d-none');
    addBtn.classList.add('d-flex');
    submit.classList.add('d-none');
    filter.classList.remove('d-none');
});

document.getElementById('addBrgyToZone').addEventListener('click', async ()=> {
    const container = document.getElementById('assignBrgyHolder');

    const checkedCheckboxes = container.querySelectorAll('input[type="checkbox"]:checked');

    const checkedValues = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);

    const csrf = await getCSRF();
    const data = checkedValues.join(',');
    load.on();
    $.ajax({
        type: "POST",
        url: "/api/post/zone/addbrgy",
        data: {"_token": csrf, "brgy": data, "zone": getVal('selectZones')},
        success: res=> {
            load.off();
            parseResult(res);
            clicked('closeUpdateZoneModal');

            const addBtn = document.getElementById('addAssignBaranggayToZone').parentNode;
            const addDiv = document.getElementById('addAssignedBrgyDiv');
            const table = document.getElementById('brgyListDiv');
            const closeDiv = document.getElementById('closeAssignBaranggayToZone').parentNode;
            const filter = document.getElementById('filterByZones').parentNode;
            const submit = document.getElementById('addBrgyToZone');

            addDiv.classList.add('d-none');
            table.classList.remove('d-none');
            closeDiv.classList.add('d-none');
            closeDiv.classList.remove('d-flex');
            filter.classList.remove('d-none');
            submit.classList.add('d-none');
            addBtn.classList.remove('d-none');
            addBtn.classList.add('d-flex');

            const val = ` <div class="w-100 d-flex justify-content-center align-items-center flex-column">
                        <img width="50" height="50" src="https://img.icons8.com/ios/50/nothing-found.png" alt="nothing-found"/>
                        <h3 class="text-muted">Nothing to show</h3>
                        <small class="text-muted">Select Zone to show all baranggay list</small>
                    </div>`;

            setHtml('assignBrgyHolder', val);
        },error: xhr=> {
            console.log(xhr.responseText)
            load.off();
            parseResult(JSON.parse(xhr.responseText));
            clicked('closeUpdateZoneModal');
        }
    })
});

