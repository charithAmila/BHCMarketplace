import { toAddress, getOwner, getSingles, getMultiples, getCollectible } from './etherFunc';
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

function tempCollectionData() {
    //var address = toAddress(addressString);
    return {
        'image': "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg",
        'display_name': "Empty Name",
        'Symbol': "Empty Symbol",
        'description': "Empty Description",
        'owner': "",
        'address': "",
        'owner': ""
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

async function getCollections(type, me) {

    var collections = [];
    var res = await axios.get("/api/collections");
    var collectionsList = res.data;
    for (var i = 0; i < collectionsList.length; i++) {
        var selected = collectionsList[i];
        if (selected.type == type) {
            var owner;
            try {
                //owner = getOwner(selected.owner);
            }
            catch (e) { }
            if (/*owner==me||*/selected.id == 1 || selected.id == 2) {
                var t = tempCollectionData()
                t["address"] = selected.address
                collections.push(t)
            }
        }
    }
    return collections;
}

async function checkFollowing(user, address) {
    return false;
}

async function updateUserDetails(addressString, data) {
    var address = toAddress(addressString);
    await axios.patch(`/api/profile/${address}`, data);
}

async function getTokens(owner) {
    var cl721 = await getCollections(721, "");
    var cl1155 = await getCollections(1155, "");
    var tokens721f = [];
    var tokens1155f = [];
    for (var i = 0; i < cl721.length; i++) {

        var contract = cl721[i].address
        var singles = await getSingles(contract, owner);
        tokens721f = [...tokens721f, ...singles];
    }
    for (var i = 0; i < cl1155.length; i++) {

        var contract = cl1155[i].address
        var multiples = await getMultiples(contract, owner);
        tokens1155f = [...tokens1155f, ...multiples];
    }

    return [tokens721f, tokens1155f];
}

async function getOwnedTokensData(owner, base_url) {
    var data = [];
    var tokens = await getTokens(owner);
    var tokens721 = tokens[0];
    var tokens1155 = tokens[1];
    for (var i = 0; i < tokens721.length; i++) {
        var selectedToken = tokens721[i];
        var res = await axios.get(selectedToken.uri);
        var dat = res.data;
        dat.ownedCopies = 1;
        dat.contract = selectedToken.contract;
        dat.id = selectedToken.id;
        dat.owner_id = owner;
        dat.icon = dat.icon || base_url + "/images/logo.png"
        dat.legend = dat.legend || "normal"
        data.push(dat);
    }
    for (var i = 0; i < tokens1155.length; i++) {
        var selectedToken = tokens1155[i];
        var res = await axios.get(selectedToken.uri);
        var dat = res.data;
        dat.ownedCopies = selectedToken.count;
        dat.contract = selectedToken.contract;
        dat.id = selectedToken.id;
        dat.owner_id = owner;
        dat.icon = dat.icon || base_url + "/images/logo.png"
        dat.legend = dat.legend || "normal"
        data.push(dat);
    }
    return data;
}

async function getLikedTokens(owner, base_url) {
    var data = [];
    return data;
}

async function getCreatedTokens(owner, base_url) {
    var data = [];
    return data;
}

async function getOnSaleokens(owner, base_url) {
    var data = [];
    return data;
}

async function getTokensData(owner, base_url) {
    var ownedTokens = await getOwnedTokensData(owner, base_url)
    var likedTokens = await getLikedTokens(owner, base_url);
    var createdTokens = await getCreatedTokens(owner, base_url);
    var onSaleTokens = await getOnSaleokens(owner, base_url);

    var data = {
        "on-sale": onSaleTokens,
        "liked": likedTokens,
        "created": createdTokens,
        "collectibles": ownedTokens
    }
    return data;
}

async function getTokenData(contract, owner, id) {
    var data = {}
    var res = await axios.get("/api/collections/" + contract);
    var type = res.data.type
    var isPrivate = res.data.id > 2 ? true : false;
    var collectible = await getCollectible(contract, type, isPrivate, owner, id);
    var colData = await axios.get(collectible.uri);
    data = colData.data;
    data.creator = owner;
    data.current_owner = owner;
    data.is_selling = 1;
    return data;

}

export { getUserDetails, checkFollowing, tempUserData, getCollections, tempCollectionData, getTokens, getTokensData, getTokenData }