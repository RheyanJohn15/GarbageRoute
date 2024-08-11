const helper = {
    loadingOn: () => {
       document.getElementById('loader').style.display = 'grid';
    },
    loadingOff: ()=> {
        document.getElementById('loader').style.display = 'none';
    },
    checkvalidity: id => {
      const inp = document.getElementById(id);
      if(inp.value == ''){
        return 0;
      }else{
        return 1;
      }
    }
}