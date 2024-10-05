document.getElementById('start').addEventListener('click', ()=> {
fetch('/AutomationScripts/SilayBoundaries.json')
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json(); // Parse the JSON data
  })
  .then(data => {
    console.log(data);
    let FinalData = [];
    let i = 0;

    let brgyList = [];
    data.forEach(d => {
        let brgy = {};

        let geometry = {};
        let properties = {};
        brgy.type = "Feature";

        let coordinates = [];

        d.geometry.coordinates[0][0].forEach(c => {
            coordinates.push([c[0], c[1]]);
        });

        brgyList.push(d.properties.NAME_3);

        properties.name = d.properties.NAME_3;
        properties.color = colors[i];

        geometry.type = "MultiPolygon";
        geometry.coordinates = [[coordinates]]

        brgy.geometry = geometry;
        brgy.properties = properties;

        i++;

        FinalData.push(brgy);
        console.log(brgy);


    });

    FinalData.push(brgyList);


    downloadJSON(FinalData, 'finalData.json');
  })
  .catch(error => {
    console.error('There has been a problem with your fetch operation:', error);
  });
});

function downloadJSON(data, filename) {
    const jsonStr = JSON.stringify(data, null, 2); // Convert to JSON string
    const blob = new Blob([jsonStr], { type: 'application/json' }); // Create a Blob
    const url = URL.createObjectURL(blob); // Create a URL for the Blob

    const a = document.createElement('a'); // Create a temporary link element
    a.href = url; // Set the href to the Blob URL
    a.download = filename; // Set the download attribute with the filename
    document.body.appendChild(a); // Append the link to the body
    a.click(); // Programmatically click the link to trigger the download
    document.body.removeChild(a); // Remove the link from the document
    URL.revokeObjectURL(url); // Clean up the URL object
  }

const colors = [
    '#FF5733',
    '#33FF57',
    '#3357FF',
    '#FF33A6',
    '#FFD633',
    '#33FFD6',
    '#9B33FF',
    '#FF8C33',
    '#8CFF33',
    '#337BFF',
    '#FF33B5',
    '#FFDB33',
    '#33FFAC',
    '#FF5733',
    '#A833FF',
    '#33FFF5'
]
