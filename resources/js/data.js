import { toAddress } from './etherFunc';
function tempUserData(addressString) {
    var address = toAddress(addressString);
    return {
        'cover_photo': "https://www.shutterstock.com/blog/wp-content/uploads/sites/5/2017/08/nature-design.jpg",
        'display_photo': "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg",
        'name': "Empty User",
        'bio': "Empty Bio",
        'wallet': address,
        'short_url': "#",
    }
};
async function getUserDetails(addressString) {
    var address = toAddress(addressString);
    var user = tempUserData(address);
    try {
        var res = await axios.get("/api/profile/" + address);
        var response = await axios.get("https://ipfs.io/ipfs/" + res.data)
        //console.log(response)
        user.cover_photo = response.data.cover || user.cover_photo;
        user.display_photo = response.data.dp || user.display_photo;
        user.name = response.data.name;
        user.bio = response.data.description;
        user.short_url = response.data.short_url;
    }
    catch (e) { }
    return user;
};

async function checkFollowing(user, address) {
    return false;
}

async function updateUserDetails(addressString, data) {
    var address = toAddress(addressString);
    await axios.patch(`/api/profile/${address}`, data);
}

export { getUserDetails, checkFollowing, tempUserData }