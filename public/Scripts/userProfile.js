window.onload = ()=> {
    getGlobalUser().then(data=> {
        const d = data.data;
        console.log(d);
        setText('pageName',d.name)
        setText('pageUsername', d.username);
        setText('pageLicense', d.username);
        setText('pageContact', d.contact);
        setText('pageAddress', d.address);

        setText('pageModel', d.truck.model);
        setText('pageCapacity', d.truck.can_carry + "(Ton/s)");
        setText('pagePlateNum', d.truck.plate_num);

        setImage('pageProfile', d.profile_pic != null ? '/UserPics/Driver/' + d.profile_pic : '/assets/img/logo.png');
    })
}
