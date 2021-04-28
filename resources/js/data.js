import { getBiddingStatus } from "./bidFunc.js";
import {
    toAddress,
    getOwner,
    getSingles,
    getMultiples,
    getCollectible,
    collectionURI,
    getOwnedCollections,
    getCollection,
    getCollectionType,
    getOwnersOf,
    getAnOwner,
    getCreated,
    checkOrder,
    generateOrderId,
    availableToBuy
} from "./etherFunc";
import {
    hps721Address,
    hps1155Address,
    hpsAddress,
    bhcAddress
} from "./addresses/constants";
import axios from "axios";

//////////////getMaxUsers/////////
window.myTokens = {
    "on-sale": [],
    collectibles: [],
    liked: [],
    created: []
};
window.loaded = {
    "on-sale": false,
    created: false,
    collectibles: false,
    liked: false,
    sales: false,
    searches: false
};
window.proPageLoading = true;
window.sales = [];
window.searches = [];
async function getMaxBuyers(time_filter) {
    let res = {};
    await axios.get("/getData/" + time_filter).then(function(response) {
        res = response.data;
    });
    console.log(res);
    let output = {};
    for (let i = 0; i < res.length; i++) {
        let user = res[i].user_id;
        let details = await getUserDetails(user);
        output[user] = {};
        output[user].buy_amount = 0;
        output[user].propic = details.display_photo;
        output[user].username = details.name;
        output[user].currency = res[i].currency;
    }
    for (let i = 0; i < res.length; i++) {
        let user = res[i].user_id;
        output[user].buy_amount += res[i].buy_amount;
    }
    return output;
}
/////////////////getMaxSellers//////
async function getMaxSellers(time_filter) {
    let res = {};

    await axios.get("/getData/" + time_filter).then(function(response) {
        res = response.data;
    });
    let output = {};
    for (let i = 0; i < res.length; i++) {
        if (res[i].type != "follow" && res[i].type != "create") {
            let user = res[i].user_id;
            let details = await getUserDetails(user);
            output[user] = {};
            output[user].sell_amount = 0;
            output[user].propic = details.display_photo;
            output[user].username = details.name;
            output[user].currency = res[i].currency;
            output[user].user_id = res[i].user_id;
        }
    }
    for (let i = 0; i < res.length; i++) {
        if (res[i].type != "follow" && res[i].type != "create") {
            let user = res[i].user_id;
            output[user].sell_amount += res[i].sell_amount;
        }
    }
    return output;
}
////////get///////////////////
function tempUserData(addressString) {
    var address = toAddress(addressString);
    return {
        cover_photo:
            "https://www.shutterstock.com/blog/wp-content/uploads/sites/5/2017/08/nature-design.jpg",
        display_photo:
            "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg",
        name: "Empty User",
        bio: "Empty Bio",
        wallet: address,
        short_url: "#"
    };
}

function tempCollectionData() {
    //var address = toAddress(addressString);
    return {
        icon:
            "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg",
        name: "Empty Name",
        Symbol: "Empty Symbol",
        description: "Empty Description",
        owner: "",
        address: "",
        owner: ""
    };
}

async function getUserDetails(addressString) {
    var address = toAddress(addressString);
    var user = tempUserData(address);
    try {
        var res = await axios.get("/api/profile/" + address);
        var response;
        try {
            response = await axios.get(
                //"https://gateway.pinata.cloud/ipfs/" + res.data.ipfs_hash
                "/data/" + res.data.ipfs_hash
            );
        } catch (e) {
            response = await axios.get(
                "https://gateway.pinata.cloud/ipfs/" + res.data.ipfs_hash
                //"/ipfs/ipfs/" + res.data.ipfs_hash
            );
        }
        //console.log(response)

        try {
            user.cover_photo =
                /*response.data.cover.replace(
                "ipfs.io",
                "gateway.pinata.cloud"
            ) */ response.data.cover.replace(
                    "https://ipfs.io",
                    "/ipfs"
                ) || user.cover_photo;
        } catch (e) {
            user.cover_photo =
                response.data.cover.replace(
                    "ipfs.io",
                    "gateway.pinata.cloud"
                ) || user.cover_photo;
        }
        try {
            user.display_photo =
                /*response.data.dp.replace(
                "ipfs.io",
                "gateway.pinata.cloud"
            )*/ response.data.dp.replace(
                    "https://ipfs.io",
                    "/ipfs"
                ) || user.display_photo;
        } catch (e) {
            user.display_photo =
                response.data.dp.replace("ipfs.io", "gateway.pinata.cloud") ||
                user.display_photo;
        }
        user.name = response.data.name;
        user.bio = response.data.description;
        user.short_url = response.data.short_url;
    } catch (e) {}
    return user;
}

async function getCollections(type, me, forDetails) {
    var collections = [];

    ///delete///

    var colIpfs = await collectionURI(hps721Address);
    var res = null;
    //colIpfs = colIpfs.replace("ipfs.io", "gateway.pinata.cloud");
    try {
        colIpfs = colIpfs.replace("https://ipfs.io/ipfs/", "/data/");
        res = await axios.get(colIpfs);
    } catch (e) {
        //colIpfs = colIpfs.replace("ipfs.io", "gateway.pinata.cloud");
        res = await axios.get(colIpfs);
    }

    var t = res.data;

    t.address = hps721Address;
    type == 721 ? collections.push(t) : null;
    var colIpfs = await collectionURI(hps1155Address);
    res = null;
    //colIpfs = colIpfs.replace("ipfs.io", "gateway.pinata.cloud");
    try {
        colIpfs = colIpfs.replace("https://ipfs.io/ipfs/", "/data/");
        res = await axios.get(colIpfs);
    } catch (e) {
        res = await axios.get(colIpfs);
    }
    var t = res.data;
    t.address = hps1155Address;

    type == 1155 ? collections.push(t) : null;
    var myCols = await getOwnedCollections(me, type, forDetails);
    console.log(myCols);
    collections = [...collections, ...myCols];
    return collections;
}

async function checkFollowing(user, address) {
    return false;
}

async function getTokens(owner) {
    var cl721 = await getCollections(721, owner, true);
    var cl1155 = await getCollections(1155, owner, true);
    var tokens721f = [];
    var tokens1155f = [];
    for (var i = 0; i < cl721.length; i++) {
        var contract = cl721[i].address;
        var collection = cl721[i];
        var singles = await getSingles(contract, owner, collection);
        tokens721f = [...tokens721f, ...singles];
    }
    for (var i = 0; i < cl1155.length; i++) {
        var contract = cl1155[i].address;
        var collection = cl1155[i];
        var multiples = await getMultiples(contract, owner, collection);

        tokens1155f = [...tokens1155f, ...multiples];
    }

    return [tokens721f, tokens1155f];
}

async function getOwnedTokensData(owner, base_url) {
    var listed = false;
    var data = [];
    window.proPageLoading = true;
    //var tokens = await getTokens(owner);
    var tokens721 = []; //tokens[0];
    var tokens1155 = []; //tokens[1];
    //console.log([tokens721, tokens1155]);
    try {
        var cl721 = await getCollections(721, owner, true);
        for (var n = 0; n < cl721.length; n++) {
            var contract = cl721[n].address;
            var collection = cl721[n];
            var singles = await getSingles(contract, owner, collection);
            tokens721 = singles;
            for (var i = 0; i < tokens721.length; i++) {
                window.proPageLoading = true;
                var selectedToken = tokens721[i];
                try {
                    var res = await axios.get(
                        //selectedToken.URI.replace("ipfs.io","gateway.pinata.cloud")
                        selectedToken.URI.replace(
                            "https://ipfs.io/ipfs/",
                            "/data/"
                        )
                    );
                    var nft = res.data;
                } catch (e) {
                    var res = await axios.get(selectedToken.URI);
                    var nft = res.data;
                }

                nft.copies = 1;
                nft.ownedCopies = selectedToken.ownedCopies;
                nft.id = selectedToken.id;
                nft.contract = selectedToken.contract;
                nft.owner_id = selectedToken.tokenOwner;

                nft.collection = selectedToken.collection;
                nft.legend = nft.legend || "normal";
                //nft.file = nft.file.replace("ipfs.io", "gateway.pinata.cloud");

                ////remove/////
                nft.isp = 1;
                var salesData = await checkSelling(
                    selectedToken.contract,
                    selectedToken.tokenOwner,
                    selectedToken.id
                );
                //nft.fileType = nft.fileType || "image";

                if (salesData.on_sale) {
                    nft.price = salesData.price;
                    nft.is_selling = true;
                    nft.currency = salesData.currency;
                    nft.currency == hpsAddress
                        ? (nft.currencyName = "HPS")
                        : nft.currency == bhcAddress
                        ? (nft.currencyName = "BHC")
                        : (nft.currencyName = "BNB");

                    nft.signed_to = salesData.signed_to;
                    nft.db_id = salesData.id;
                    nft.signature = salesData.signature;
                    nft.salt = salesData.salt;
                }

                data.push(nft);
                window.myTokens.collectibles.push(nft);
            }
        }

        var cl1155 = await getCollections(1155, owner, true);
        for (var m = 0; m < cl1155.length; m++) {
            var contract = cl1155[m].address;
            var collection = cl1155[m];
            var multiples = await getMultiples(contract, owner, collection);

            tokens1155 = multiples;
            for (var i = 0; i < tokens1155.length; i++) {
                window.proPageLoading = true;
                var selectedToken = tokens1155[i];
                var res = null;
                //selectedToken.URI = selectedToken.URI.replace("ipfs.io","gateway.pinata.cloud");
                try {
                    selectedToken.URI = selectedToken.URI.replace(
                        "https://ipfs.io/ipfs/",
                        "/data/"
                    );

                    res = await axios.get(selectedToken.URI);
                } catch (e) {
                    //selectedToken.URI = selectedToken.URI.replace("ipfs.io","gateway.pinata.cloud");

                    res = await axios.get(selectedToken.URI);
                }
                var nft = res.data;

                nft.copies = nft.count;
                nft.ownedCopies = selectedToken.ownedCopies;
                nft.id = selectedToken.id;
                nft.contract = selectedToken.contract;
                nft.owner_id = selectedToken.tokenOwner;

                nft.collection = selectedToken.collection;
                nft.legend = nft.legend || "normal";

                nft.isp = 1;
                var salesData = await checkSelling(
                    selectedToken.contract,
                    selectedToken.tokenOwner,
                    selectedToken.id
                );
                //nft.fileType = nft.fileType || "image";
                //nft.file = nft.file.replace("ipfs.io","gateway.pinata.cloud");
                nft.file = nft.file.replace("https://ipfs.io", "/ipfs");

                if (salesData.on_sale) {
                    nft.price = salesData.price;
                    nft.is_selling = true;
                    nft.currency = salesData.currency;
                    nft.currency == hpsAddress
                        ? (nft.currencyName = "HPS")
                        : nft.currency == bhcAddress
                        ? (nft.currencyName = "BHC")
                        : (nft.currencyName = "BNB");

                    nft.signed_to = salesData.signed_to;
                    nft.db_id = salesData.id;
                    nft.signature = salesData.signature;
                    nft.salt = salesData.salt;
                }
                data.push(nft);
                window.myTokens.collectibles.push(nft);
            }
        }
    } catch (e) {}
    window.proPageLoading = false;
    if (window.myTokens.collectibles.length == 0) {
        window.myTokens.collectibles = null;
    }
    window.loaded["collectibles"] = true;
    return data;
}

async function getLikedTokens(owner, base_url) {
    var data = [];
    var liked = await axios.get("/like");
    var likes = liked.data.likes.filter(function(like) {
        if (toAddress(like.address) == toAddress(owner)) return true;
    });
    console.log(likes);
    for (var i = 0; i < likes.length; i++) {
        window.proPageLoading = true;
        var owners = await getAnOwner(
            likes[i].contract,
            likes[i].token_id,
            6494200,
            owner
        );
        for (var j = 0; j < owners.length; j++) {
            var token = await getTokenData(
                likes[i].contract,
                owners[j].owner,
                likes[i].token_id
            );
            data.push(token);
            window.myTokens.liked.push(token);
        }
    }
    window.proPageLoading = false;
    if (window.myTokens.liked.length == 0) {
        window.myTokens.liked = null;
    }
    window.loaded["liked"] = true;
    return data;
}

async function getCreatedTokens(owner, base_url) {
    if (window.myTokens.created != null) {
        if (window.myTokens.created.length == 0) {
            getCreated(owner, 6494200);
        }
    }
}

async function getOnSaleTokens(owner, base_url) {
    window.myTokens["on-sale"] = [];

    var data = [];
    var tokens721 = [];
    var tokens1155 = [];
    var res = await axios.get("/sales/" + owner);
    var tokens = res.data;

    for (var i = 0; i < tokens.length; i++) {
        window.proPageLoading = true;
        try {
            var nft = await getTokenData(
                tokens[i].collection,
                tokens[i].current_owner,
                tokens[i].token_id
            );
            nft.db_id = tokens[i].id;
            nft.is_isp = 1;
            nft.is_selling = 1;
            nft.price = tokens[i].price;
            nft.currency = tokens[i].currency;
            try {
                /*nft.file = nft.file.replace(
                    "ipfs.io",
                    "gateway.pinata.cloud"
                );*/
                nft.file = nft.file.replace("https://ipfs.io", "/ipfs");
            } catch (e) {}
            nft.on_sale = await availableToBuy(
                tokens[i].collection,
                tokens[i].token_id,
                tokens[i].signed_to,
                tokens[i].currency,
                tokens[i].price,
                tokens[i].salt
            );
            nft.collection = tokens[i].collection;
            nft.copies = nft.count;
            data.push(nft);
            window.myTokens["on-sale"].push(nft);
        } catch (e) {
            //console.log(e)
        }
    }
    window.proPageLoading = false;
    if (window.myTokens["on-sale"].length == 0) {
        window.myTokens["on-sale"] = null;
    }
    window.loaded["on-sale"] = true;
    return data;
}

async function getTokensData(owner, base_url) {
    //getOnSaleTokens(owner, base_url);
    getOwnedTokensData(owner, base_url);
    //getCreatedTokens(owner, base_url);
    getLikedTokens(owner, base_url);
    //var likedTokens = await getLikedTokens(owner, base_url);
    //var createdTokens = await getCreatedTokens(owner, base_url);

    /*var data = {
        "on-sale": onSaleTokens,
        liked: [], //likedTokens,
        created: [], //createdTokens,
        collectibles: ownedTokens
    };*/
    //console.log(data)
    //return data;
}

async function getTokenData(contract, owner, id) {
    var listed = false;
    //var res = await axios.get("/api/collections/" + contract);
    var biddingStatus = await getBiddingStatus(owner, contract, id);
    var isPrivate =
        contract != hps721Address
            ? true
            : contract != hps1155Address
            ? true
            : false;

    var type = await getCollectionType(contract);
    //var collection = await getCollection(contract);

    if (type == 721) {
        var selectedToken = await getCollectible(
            contract,
            721,
            isPrivate,
            owner,
            id
        );
        type = 721;
    } else if (type == 1155) {
        var selectedToken = await getCollectible(
            contract,
            1155,
            isPrivate,
            owner,
            id
        );
        type = 1155;
    }
    var colData = null;
    var uri = null;
    try {
        //selectedToken.URI = selectedToken.URI.replace( "ipfs.io","gateway.pinata.cloud");
        uri = selectedToken.URI.replace("https://ipfs.io/ipfs/", "/data/");
        colData = await axios.get(uri);
    } catch (e) {
        uri = selectedToken.URI.replace("ipfs.io", "gateway.pinata.cloud");

        colData = await axios.get(uri);
    }

    //colData = await axios.get(selectedToken.URI);
    var nft = colData.data;
    /*data.current_owner = collectible.tokenOwner;
    data.is_selling = 1;
    data.collection = collectible.collection || tempCollectionData()
    data.ownedCopies = collectible.ownedCopies;
    data.type = type;

    listed ? data.price = data.instant_sale_price : data.price = data.instant_sale_price
    //////remove////
    data.fileType = data.fileType || "image";
    data.file = data.image || data.file
    data.creator = owner;
    data.count = collectible.availableCopies*/
    //console.log(selectedToken)
    nft.biddingStatus = biddingStatus;
    nft.count = nft.count || 1;
    nft.copies = nft.count || 1;
    nft.ownedCopies = selectedToken.ownedCopies;
    nft.id = selectedToken.id;
    nft.contract = selectedToken.contract;
    nft.owner_id = selectedToken.tokenOwner;

    try {
        //selectedToken.URI = selectedToken.URI.replace( "ipfs.io","gateway.pinata.cloud");
        uri = selectedToken.collection.URI.replace(
            "https://ipfs.io/ipfs/",
            "/data/"
        );
        nft.collection = await axios(uri);
    } catch (e) {
        uri = selectedToken.collection.URI.replace(
            "ipfs.io",
            "gateway.pinata.cloud"
        );

        nft.collection = await axios(uri);
    }
    nft.collection = nft.collection.data;
    nft.legend = nft.legend || "normal";

    nft.type = type;
    try {
        nft.file = nft.file.replace("https://ipfs.io", "/ipfs");
    } catch (e) {}
    ////remove/////
    nft.isp = 1;
    var salesData = await checkSelling(
        selectedToken.contract,
        selectedToken.tokenOwner,
        selectedToken.id
    );

    //nft.fileType = nft.fileType || "image";

    if (salesData.on_sale) {
        nft.price = salesData.price;
        nft.is_selling = true;
        nft.currency = salesData.currency;
        nft.currency == hpsAddress
            ? (nft.currencyName = "HPS")
            : nft.currency == bhcAddress
            ? (nft.currencyName = "BHC")
            : (nft.currencyName = "BNB");

        nft.signed_to = salesData.signed_to;
        nft.on_sale = await availableToBuy(
            salesData.collection,
            salesData.token_id,
            salesData.signed_to,
            salesData.currency,
            salesData.price,
            salesData.salt
        );
        nft.db_id = salesData.id;
        nft.signature = salesData.signature;
        nft.salt = salesData.salt;
        nft.created_at = moment(salesData.created_at).format(
            "MM/DD/YYYY hh:mm"
        );
    }
    // nft.file = nft.image || nft.file
    //nft.creator = owner;

    return nft;
}

async function collectiblesOfCollection(collection) {
    var collects = await getCollection(collection);
    var collectibles = [];
    for (var i = 0; i < collects.length; i++) {
        var id = collects[i].id;
        var owner = collects[i].owner;
        try {
            var nft = await getTokenData(collection, owner, id);
            collectibles.push(nft);
        } catch (e) {
            //console.log(e);
        }
    }
    //console.log(collectibles);
    return collectibles;
}

async function getAllSales(current_user) {
    window.sales = [];
    var data = [];
    var tokens721 = [];
    var tokens1155 = [];
    var res = await axios.get("/sales");
    var tokens = res.data;

    for (var i = 0; i < tokens.length; i++) {
        if (tokens[i].current_owner != current_user) {
            try {
                getTokenData(
                    tokens[i].collection,
                    tokens[i].current_owner,
                    tokens[i].token_id
                )
                    .then(nft => {
                        console.log(nft);
                        nft.copies = nft.count;
                        //nft.collection = tokens[i].collection;

                        //nft.signed_to = tokens[i].signed_to;
                        //nft.db_id = tokens[i].id;
                        //nft.price = tokens[i].price;

                        //nft.ownedCopies = tokens[i].signed_to - tokens[i].sold;
                        nft.isp = 1;
                        nft.is_selling = 1;
                        //nft.signature = tokens[i].signature;
                        //nft.salt = tokens[i].salt;
                        nft.currency = nft.currency;
                        nft.currencyName =
                            nft.currency == hpsAddress
                                ? "HPS"
                                : nft.currency == bhcAddress
                                ? "BHC"
                                : "BNB";
                        /*nft.file = nft.file.replace(
                    "ipfs.io",
                    "gateway.pinata.cloud"
                );*/
                        //nft.file = nft.file.replace("https://ipfs.io", "/ipfs");
                        data.push(nft);
                        //if (data.length % 4 == 0) {
                        //for (var x = data.length - 1; x > data.length - 5; x++) {
                        window.sales.push(nft);
                        //}
                        //}
                    })
                    .catch(err => {
                        if (i == tokens.length) {
                            window.loaded["sales"] = true;
                        }
                    })
                    .finally(data => {
                        if (i == tokens.length) {
                            window.loaded["sales"] = true;
                        }
                    });
            } catch (e) {}
        }
    }

    //return data;
}

async function getAllSalesSearch(current_user, parameter) {
    var data = [];
    window.searches = [];
    var tokens721 = [];
    var tokens1155 = [];
    var res = await axios.get("/sales_search");
    var tokens = res.data;

    for (var i = 0; i < tokens.length; i++) {
        if (tokens[i].current_owner != current_user) {
            if (
                tokens[i].col_name
                    .toLowerCase()
                    .includes(parameter.toLowerCase()) ||
                tokens[i].owner_name
                    .toLowerCase()
                    .includes(parameter.toLowerCase()) ||
                tokens[i].nft_name
                    .toLowerCase()
                    .includes(parameter.toLowerCase())
            ) {
                try {
                    window.loaded[""];
                    getTokenData(
                        tokens[i].collection,
                        tokens[i].current_owner,
                        tokens[i].token_id
                    )
                        .then(async function(nft) {
                            nft.copies = nft.count;
                            //nft.collection = tokens[i].collection;

                            //nft.signed_to = tokens[i].signed_to;
                            //nft.db_id = tokens[i].id;
                            //nft.price = tokens[i].price;

                            nft.isp = 1;
                            nft.is_selling = 1;
                            //nft.signature = tokens[i].signature;
                            //nft.salt = tokens[i].salt;
                            //nft.currency = tokens[i].currency;
                            nft.currencyName =
                                nft.currency == hpsAddress
                                    ? "HPS"
                                    : nft.currency == bhcAddress
                                    ? "BHC"
                                    : "BNB";
                            //nft.creatorData = await getUserDetails(nft.creator);
                            /*nft.file = nft.file.replace(
                        "ipfs.io",
                        "gateway.pinata.cloud"
                    );*/
                            nft.file = nft.file.replace(
                                "ipfs.io",
                                "gateway.pinata.cloud"
                            );
                            nft.ownedCopies = nft.on_sale;
                            data.push(nft);
                            //if (data.length % 4 == 0) {
                            //for (var x = data.length - 1; x > data.lenth - 5; x++) {
                            window.searches.push(nft);
                            // }
                            //}
                            console.log("jgvhvjhvhvh");
                        })
                        .catch(e => {
                            if (i == tokens.length) {
                                window.loaded["searches"] = true;
                            }
                        })
                        .finally(data => {
                            if (i == tokens.length) {
                                window.loaded["searches"] = true;
                            }
                        });
                } catch (e) {}
            }
        }
    }
    return data;
}

async function checkSelling(collection, owner, id) {
    var res = await axios.get(
        `/sales?collection=${collection}&current_owner=${owner}&token_id=${id}`
    );

    return res.data;
}

///////////////set//////////////////////
async function updateUserDetails(addressString, data) {
    var address = toAddress(addressString);
    await axios.patch(`/api/profile/${address}`, data);
}
async function addSale(data) {
    await axios.post(`/sales`, data);
}

async function removeSale(
    tokenAddress,
    tokenId,
    value,
    priceToken,
    price,
    salt,
    id
) {
    var orderData = await checkOrder(
        tokenAddress,
        tokenId,
        value,
        priceToken,
        price,
        salt
    );
    console.log(orderData);
    if (Number(orderData.total) == Number(orderData.sold)) {
        await axios.delete(`/sales/${id}`);
    }
}

async function forceRemoveSale(id) {
    await axios.delete(`/sales/${id}`);
}

async function bought(id, amount) {
    await axios.patch(`/sales/${id}`, { amount: amount });
}

export {
    getUserDetails,
    checkFollowing,
    tempUserData,
    getCollections,
    tempCollectionData,
    getTokens,
    getTokensData,
    getTokenData,
    addSale,
    updateUserDetails,
    getAllSales,
    removeSale,
    forceRemoveSale,
    collectiblesOfCollection,
    getOnSaleTokens,
    getLikedTokens,
    getCreatedTokens,
    getMaxBuyers,
    getMaxSellers,
    getAllSalesSearch,
    bought
};
