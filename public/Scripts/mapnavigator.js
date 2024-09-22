setTimeout(() => {
    window.Echo.channel('gps-update')
    .listen('GpsUpdate', (e)=> {
      console.log(e);
    })
  }, 2000);