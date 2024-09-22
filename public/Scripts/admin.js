let globalUserPromise = new Promise(async (resolve, reject) => {
    try {
        const getToken = await fetch('/api/get/request/accesstoken', {
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
        setImage('userProfilePic', `https://via.placeholder.com/150/${color}/000000/?text=${user.acc_name[0]}`);
        setImage('userProfilePicMobile', `https://via.placeholder.com/150/${color}/000000/?text=${user.acc_name[0]}`);

        setText('userNameDrop', user.acc_name);
        setText('userType', user.acc_type);
    } catch (error) {
        reject(error); 
    }
});

// Function to access globalUser when ready
async function getGlobalUser() {
    return await globalUserPromise;
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