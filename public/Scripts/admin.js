let profilePic;
let superAdminTypeStatus = true;
let globalUserPromise = new Promise(async (resolve, reject) => {
    try {
        const getToken = await fetch('/api/get/request/accesstoken?userType=admin', {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const token = await getToken.json();

        const getUserInfo = await fetch(`/api/get/auth/info?token=${token.access_token}&type=admin`, {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        const userInfo = await getUserInfo.json();

        const globalUser = userInfo; // Assign the globalUser
        resolve(globalUser); // Resolve the promise when data is ready

        const user = userInfo.data;
        setText('adminNameHeaderNav', user.acc_name);

        const color = getRandomColor(profileBackgrounds);

        const splitText = user.acc_name.split(' ');
        let initials = '';

        splitText.forEach(name => {
            initials += name[0].toUpperCase();
        });

        profilePic = user.acc_profile !== null ? `/assets/img/avatars/${user.acc_profile}` : `https://via.placeholder.com/150/${color}/000000/?text=${initials}`;

        setImage('userProfilePic', profilePic);
        setImage('userProfilePicMobile', profilePic);
        setImage('accountAvatar', profilePic);
        setImage('changeProfileSelected', profilePic);
        setText('userNameDrop', user.acc_name);
        setText('userType', user.acc_type);

        if(user.acc_type != 'Super Admin'){
            reactive('addDriverOpenModalBtn', true);
            reactive('addTruckOpenModalBtn', true);
            setText('addTruckOpenModalBtn', "Not Available");
            setText('addDriverOpenModalBtn', "Not Available");
            superAdminTypeStatus = false;
        }

    } catch (error) {
        reject(error); 
    }
});

// Function to access globalUser when ready
async function getGlobalUser() {
    return await globalUserPromise;
}

async function getSuperAdminStatus() {
    const check = await globalUserPromise;

    return superAdminTypeStatus;
}


const profileBackgrounds = [
    "e6e6fa", // Lavender
    "d3d3e0", // Light Slate Blue
    "b0e0e6", // Powder Blue
    "add8e6", // Light Blue
    "b0e0e6", // Light Blue
    "87cefa", // Light Sky Blue
    "d8bfd8", // Thistle
    "d9f0d9", // Light Green
    "98fb98", // Pale Green
    "f0fff0", // Honeydew
    "b2d3c2", // Light Sea Green
    "b0e0b0", // Light Green
    "cce5ff", // Light Blue
    "e0ffff", // Light Cyan
    "f0e68c", // Khaki
];

