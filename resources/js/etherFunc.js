import axios from "axios";
import { BigNumber, ethers } from "ethers";
import { start } from "single-spa";
import {
    hps721Address,
    hps1155Address,
    transferProxyAddress,
    erc20TransferProxyAddress,
    orderStorageAddress,
    exchangeAddress,
    hpsAddress,
    bhcAddress,
    minterAddress,
    contractFactoryAddress,
    NFTStorageAddress,
    WBNB_tokenAddress,
    julswap_routerAddress
} from "./addresses/constants";
import { getTokenData } from "./data";
//console.log(ethers.utils.splitSignature("0x32d9e9324ca4d87e0aa56837cf0929bc49f7cf8db3f2ca734e1e50a6b982aadc403dfa3f3d79edfddd68814efca3168cf63f0d3a4c25511b23d0782c824927571b"))
/////////abis///////////////////
const bhc721 = require("../js/abis/bhc_721.json");
const bhc1155 = require("../js/abis/bhc_1155.json");
const nftStorageABI = require("../js/abis/nft_storage.json");
const exchangeABI = require("../js/abis/new_exchange.json");
const bep20ABI = require("./abis/bep20.json");
const minterABI = require("./abis/minter.json");
const factoryABI = require("./abis/factory.json");

window.loadedCreated = false;

if (typeof window.ethereum == "undefined") {
    window.provider = new ethers.providers.JsonRpcProvider(
        "https://bsc-dataseed.binance.org/"
    );
    window.rpcprovider = new ethers.providers.JsonRpcProvider(
        "https://bsc-dataseed.binance.org/"
    );
    window.rpcprovider1 = window.provider;
} else {
    window.provider = new ethers.providers.Web3Provider(window.ethereum);
    window.rpcprovider = window.provider;
    window.rpcprovider1 = window.provider; //new ethers.providers.Web3Provider(window.ethereum);
}

/*window.rpcprovider = new ethers.providers.JsonRpcProvider(
    //"https://apis.ankr.com/90ca2e28d7af47eea5a0d41b1236d19d/10acafa95fd982713d5972bad68960fc/binance/full/main"
    //"https://data-seed-prebsc-1-s1.binance.org:8545"
    "http://162.0.210.42/rpc"
);
window.rpcprovider = new ethers.providers.JsonRpcProvider(
    //"https://apis.ankr.com/90ca2e28d7af47eea5a0d41b1236d19d/10acafa95fd982713d5972bad68960fc/binance/full/main"
    //"https://data-seed-prebsc-1-s1.binance.org:8545"
    "http://162.0.210.42/rpc"
);*/

//const selectedAddress = provider.provider.selectedAddress;

///////Get function//////////

async function syncCollections(collectionAddess) {
    var latest = await axios.get("/transfers?collection=" + collectionAddess);
    startBlock = latest.data.length == 0 ? 6494200 : latest.data.block + 1;
    var endBlock = await rpcprovider.getBlockNumber();
    var evts = [];
    for (var i = startBlock; i <= endBlock; i = i + 4000) {
        var transfers = [];
        startBlock = i;
        var evtsCr = await contract.queryFilter(
            "TransferSingle",
            i,
            i + 4000 <= endBlock ? i + 4000 : endBlock
        );

        for (var x = 0; x < evtsCr.length; x++) {
            var event = evtsCr[x];
            transfers.push([
                event.blockNumber,
                collectionAddess,
                event.args.to,
                Number(event.args.id)
            ]);
        }
        if (transfers.length > 0) {
            await axios.post("/transfers", { transfers: transfers });
        }
        startBlock = i + 4000 <= endBlock ? i + 4000 : endBlock;
        evts = evtsCr;
    }
    if (startBlock != 0) {
        await axios.patch("/transfers/" + collectionAddess, {
            block: startBlock - 1
        });
    }
}

function toAddress(addressString) {
    return ethers.utils.isAddress(addressString)
        ? ethers.utils.getAddress(addressString)
        : ethers.utils.getAddress("0x0000000000000000000000000000000000000000");
}

async function checkConnection() {
    // const account = await provider.listAccounts();
    // const account = account[0];
    /* provider.getNetwork().then(data => {
        this.account_check = data.chainId;
    });
*/

    var acc = "";
    try {
        var network = await provider.getNetwork();
        console.log(network);
        if (network.chainId == 56) {
            const accs = await provider.listAccounts();
            acc = toAddress(accs[0]);
        } else {
            acc = toAddress("");
        }
    } catch (e) {
        acc = "";
    }
    return acc;
}

async function redirectToConnect() {
    if (awaitcheckConnection() == null) {
        window.location.href = "/connect";
    }
}
async function getBalanceInUSDT() {
    var address = await checkConnection();
    const bhcContract = new ethers.Contract(bhcAddress, token_ABI, provider);
    const BHC_balance = await bhcContract.balanceOf(address);
    const BNB_balance = await provider.getBalance(address);
    //return parseFloat(balance.toString()) / 10 ** 18;
    const julswap_router = ethers.utils.getAddress(julswap_routerAddress);
    const julswap_Read = new ethers.Contract(
        julswap_router,
        julswap_ABI,
        provider
    );

    const priceBHC = await julswap_Read.getAmountsIn(
        ethers.BigNumber.from("1000000000000000000"),
        ["0x55d398326f99059ff775485246999027b3197955", bhcAddress]
    );
    const priceBNB = await julswap_Read.getAmountsIn(
        ethers.BigNumber.from("1000000000000000000"),
        ["0x55d398326f99059ff775485246999027b3197955", WBNB_tokenAddress]
    );
    console.log("Price of BHC");
    console.log(priceBHC);
    console.log("Price of BNB");
    console.log(priceBNB);
    //return parseFloat(price[0].toString());
}
async function getBNBBalance(address) {
    try {
        var balance = await rpcprovider.getBalance(toAddress(address));
        return balance / 10 ** 18;
    } catch (error) {
        return 0;
    }
}
async function signMessage(message) {
    const signer = provider.getSigner();
    return await signer.signMessage(message);
}

function splitSign(signature) {
    return ethers.utils.splitSignature(signature);
}

async function waitForTransaction(tx) {
    var res = await rpcprovider.waitForTransaction(tx);
    return res;
}

async function collectionURI(contractAddress) {
    try {
        const contract = new ethers.Contract(
            contractAddress,
            bhc721,
            rpcprovider
        );
        const uri = await contract.contract_URI();
        return uri;
    } catch (e) {
        return "";
    }
}

async function getOwner(addressString, ABI) {
    try {
        var contractAddress = toAddress(addressString);
        var contract = new ethers.Contract(contractAddress, ABI, rpcprovider);
        var data = await contract.owner();
        return owner;
    } catch (e) {
        return toAddress("");
    }
}

async function getCollection(collectionAddess) {
    window.myTokens.created = [];
    var owners = [];
    var startBlock = 0;
    try {
        const ERC1155Interface = "0x0e89341c";
        const ERC721Interface = "0x80ac58cd";
        var filters = {};

        var contract = new ethers.Contract(
            toAddress(collectionAddess),
            bhc721,
            rpcprovider
        );
        var is721 = await contract.supportsInterface(ERC721Interface);
        var is1155 = await contract.supportsInterface(ERC1155Interface);
        if (is721) {
            var supply = await contract.totalSupply();
            for (var i = 0; i < Number(supply); i++) {
                var tokenId = await contract.tokenByIndex(i);
                var owner = await contract.ownerOf(tokenId);
                var tk = { id: Number(tokenId), owner: owner };

                !owners.includes(tk) ? owners.push(tk) : null;
            }
        } else {
            contract = new ethers.Contract(
                toAddress(collectionAddess),
                bhc1155,
                rpcprovider
            );
            var res = await axios.get("/transfers/" + collectionAddess);
            var evts = res.data;
            for (var i = 0; i < evts.length; i++) {
                var tokenId = Number(evts[i].token_id);
                var owner = evts[i].owner;
                var tk = { id: tokenId, owner: owner };
                var obj = owners.filter(function(element) {
                    if (element.id == tokenId && element.owner == owner)
                        return true;
                });
                if (obj.length == 0) {
                    owners.push(tk);
                }
                //console.log(owners);
            }

            var latest = await axios.get(
                "/transfers?collection=" + collectionAddess
            );
            startBlock =
                latest.data.length == 0 ? 6494200 : latest.data.block + 1;
            var endBlock = await rpcprovider.getBlockNumber();
            var evts = [];
            for (var i = startBlock; i <= endBlock; i = i + 4000) {
                var transfers = [];
                startBlock = i;
                var evtsCr = await contract.queryFilter(
                    "TransferSingle",
                    i,
                    i + 4000 <= endBlock ? i + 4000 : endBlock
                );

                for (var x = 0; x < evtsCr.length; x++) {
                    var event = evtsCr[x];
                    transfers.push([
                        event.blockNumber,
                        collectionAddess,
                        event.args.to,
                        Number(event.args.id)
                    ]);
                }
                if (transfers.length > 0) {
                    await axios.post("/transfers", { transfers: transfers });
                }
                startBlock = i + 4000 <= endBlock ? i + 4000 : endBlock;
                evts = evtsCr;
                for (var j = 0; j < evts.length; j++) {
                    var tokenId = Number(evts[j].args.id);
                    var owner = evts[j].args.to;
                    var tk = { id: tokenId, owner: owner };
                    var obj = owners.filter(function(element) {
                        if (element.id == tokenId && element.owner == owner)
                            return true;
                    });
                    if (obj.length == 0) {
                        owners.push(tk);
                    }
                }
            }

            var ownerById = {};
            if (startBlock != 0) {
                await axios.patch("/transfers/" + collectionAddess, {
                    block: startBlock - 1
                });
            }
        }
    } catch (e) {
        if (startBlock != 0) {
            await axios.patch("/transfers/" + collectionAddess, {
                block: startBlock - 1
            });
        }
        console.log(e);
    }
    console.log(owners);
    return owners;
}

async function getCreated(owner, _startingBlock) {
    const tokens = [];
    //window.myTokens.created = [];
    var startBlock = 0;

    try {
        const nftStorage = new ethers.Contract(
            NFTStorageAddress,
            nftStorageABI,
            rpcprovider1
        );
        var res = await axios.get("/minted/" + owner);
        var savedEvents = res.data;
        console.log(savedEvents);
        for (var j = 0; j < savedEvents.length; j++) {
            var event = savedEvents[j];
            var type = getCollectionType(event.collection);
            tokens.push({
                contract: event.collection,
                token_id: Number(event.token_id)
            });
            console.log({ tok: tokens });

            //for (var i = 0; i < tokens.length; i++) {
            window.proPageLoading = true;
            var owners = await getAnOwner(
                event.collection,
                event.token_id,
                6494200,
                owner
            );
            //for (var n = 0; n < owners.length; n++) {
            try {
                getTokenData(
                    event.collection,
                    owners[0].owner,
                    event.token_id
                ).then(token => {
                    window.myTokens.created.push(token);
                });

                //data.push(token);
            } catch (e) {
                console.log(e);
            }
            //}

            //}
        }
        //var evts = await nftStorage.queryFilter("NFTAdded", 6494200, "latest");
        var latest = await axios.get("/minted");
        startBlock = latest.data.length == 0 ? 6494200 : latest.data.block + 1;
        var endBlock = await rpcprovider1.getBlockNumber();
        var evts = [];
        console.log(startBlock);

        for (var i = startBlock; i <= endBlock; i = i + 4000) {
            var st = i;
            var startBlock = i;
            var evtsCr = await nftStorage.queryFilter(
                "NFTAdded",
                i,
                i + 4000 <= endBlock ? i + 4000 : endBlock
            );
            console.log(evtsCr);
            var mints = [];
            for (var x = 0; x < evtsCr.length; x++) {
                var event = evtsCr[x];
                mints.push([
                    event.blockNumber,
                    event.args._creator,
                    event.args._collection,
                    Number(event.args._id)
                ]);
            }

            if (mints.length > 0) {
                await axios.post("/minted", { mints: mints });
            }
            startBlock = i + 4000 <= endBlock ? i + 4000 : endBlock;
            for (var j = 0; j < evtsCr.length; j++) {
                var event = evtsCr[j];
                var creator = toAddress(event.args._creator);
                if (owner == creator) {
                    var type = getCollectionType(event.args._collection);
                    tokens.push({
                        contract: event.args._collection,
                        token_id: Number(event.args._id)
                    });
                    console.log({ tok: tokens });

                    //for (var i = 0; i < tokens.length; i++) {
                    window.proPageLoading = true;
                    var owners = await getAnOwner(
                        event.args._collection,
                        event.args._id,
                        6494200,
                        owner
                    );
                    //for (var n = 0; n < owners.length; n++) {
                    try {
                        getTokenData(
                            event.args._collection,
                            owners[0].owner,
                            event.args._id
                        )
                            .then(token => {
                                window.myTokens.created.push(token);
                            })
                            .catch(err => {
                                if (i + 4000 >= endBlock) {
                                    window.loaded["created"] = true;
                                }
                            })
                            .finally(data => {
                                if (i + 4000 >= endBlock) {
                                    window.loaded["created"] = true;
                                }
                            });
                        //data.push(token);
                    } catch (e) {
                        console.log(e);
                    }
                    //}

                    //}
                }
            }
        }
        if (startBlock != 0) {
            window.loaded["created"] = true;
            await axios.patch("/minted/" + owner, {
                block: startBlock - 1
            });
        }
    } catch (e) {
        //await getCreated(owner, st);
        if (startBlock != 0) {
            await axios.patch("/minted/" + owner, { block: startBlock - 1 });
        }
        window.loaded["created"] = true;
        console.log(e);
    }

    return tokens;
}

async function getCollectionType(collectionAddress) {
    try {
        const ERC1155Interface = "0x0e89341c";
        const ERC721Interface = "0x80ac58cd";
        var filters = {};
        var owners = [];
        var contract = new ethers.Contract(
            toAddress(collectionAddress),
            bhc721,
            rpcprovider
        );
        var is721 = await contract.supportsInterface(ERC721Interface);
        var is1155 = await contract.supportsInterface(ERC1155Interface);

        return is721 ? 721 : is1155 ? 1155 : null;
    } catch (e) {
        return null;
    }
}

async function getOwnersOf(collectionAddess, tokenId, _startBlock) {
    const ERC1155Interface = "0x0e89341c";
    const ERC721Interface = "0x80ac58cd";

    var filters = {};
    var owners = [];
    var startBlock = 0;

    try {
        var contract = new ethers.Contract(
            toAddress(collectionAddess),
            bhc721,
            rpcprovider
        );
        var is721 = await contract.supportsInterface(ERC721Interface);
        var is1155 = await contract.supportsInterface(ERC1155Interface);
        if (is721) {
            var owner = await contract.ownerOf(tokenId);
            owners.push({ owner: owner, ownedCopies: 1 });
        } else {
            contract = new ethers.Contract(
                toAddress(collectionAddess),
                bhc1155,
                rpcprovider1
            );
            var res = await axios.get("/transfers/" + collectionAddess);
            var evtsCr = res.data;
            for (var j = 0; j < evtsCr.length; j++) {
                if (Number(evtsCr[j].token_id) == tokenId) {
                    var owner = evtsCr[j].owner;
                    var copies = await contract.balanceOf(
                        owner,
                        evtsCr[j].token_id
                    );
                    var tk = { owner: owner, ownedCopies: copies };
                    var obj = owners.filter(function(element) {
                        if (element.owner == owner) return true;
                    });
                    if (obj.length == 0) {
                        owners.push(tk);
                    }
                }
            }
            var latest = await axios.get(
                "/transfers?collection=" + collectionAddess
            );
            startBlock =
                latest.data.length == 0 ? 6494200 : latest.data.block + 1;
            console.log(startBlock);
            var endBlock = await rpcprovider1.getBlockNumber();
            var evts = [];
            var filter = contract.filters.TransferSingle(
                null,
                null,
                null,
                null,
                null
            );
            for (var i = startBlock; i <= endBlock; i = i + 4000) {
                var st = i;
                var startBlock = i;
                var transfers = [];
                var evtsCr = await contract.queryFilter(
                    "TransferSingle",
                    i,
                    endBlock >= i + 4000 ? i + 4000 : endBlock
                );
                for (var x = 0; x < evtsCr.length; x++) {
                    var event = evtsCr[x];
                    transfers.push([
                        event.blockNumber,
                        collectionAddess,
                        event.args.to,
                        Number(event.args.id)
                    ]);
                }
                if (transfers.length > 0) {
                    await axios.post("/transfers", { transfers: transfers });
                }
                startBlock = i + 4000 <= endBlock ? i + 4000 : endBlock;
                console.log(evtsCr);

                var ownerById = {};
                for (var j = 0; j < evtsCr.length; j++) {
                    if (Number(evtsCr[j].args.id) == tokenId) {
                        var owner = evtsCr[j].args.to;
                        var copies = await contract.balanceOf(
                            owner,
                            evtsCr[j].args.id
                        );
                        var tk = { owner: owner, ownedCopies: copies };
                        var obj = owners.filter(function(element) {
                            if (element.owner == owner) return true;
                        });
                        if (obj.length == 0) {
                            owners.push(tk);
                        }
                    }
                }
            }
            if (startBlock != 0) {
                await axios.patch("/transfers/" + collectionAddess, {
                    block: startBlock - 1
                });
            }
        }
    } catch (e) {
        if (startBlock != 0) {
            await axios.patch("/transfers/" + collectionAddess, {
                block: startBlock - 1
            });
        }
        console.log(e);
    }
    return owners;
}

async function getAnOwner(collectionAddess, tokenId, _startBlock, _owner) {
    const ERC1155Interface = "0x0e89341c";
    const ERC721Interface = "0x80ac58cd";

    var filters = {};
    var owners = [];
    var allCopies = 0;

    try {
        var contract = new ethers.Contract(
            toAddress(collectionAddess),
            bhc721,
            rpcprovider
        );
        var is721 = await contract.supportsInterface(ERC721Interface);
        var is1155 = await contract.supportsInterface(ERC1155Interface);
        if (is721) {
            var owner = await contract.ownerOf(tokenId);
            owners.push({ owner: owner, ownedCopies: 1 });
        } else {
            contract = new ethers.Contract(
                toAddress(collectionAddess),
                bhc1155,
                rpcprovider1
            );
            var copies = await contract.balanceOf(_owner, tokenId);
            if (Number(copies) > 0) {
                var tk = { owner: _owner, ownedCopies: copies };
                owners.push(tk);
                return owners;
            }
            var syncedOwners = await getOwnersOf(
                collectionAddess,
                tokenId,
                _startBlock
            );
            console.log(syncedOwners);
            for (var ow = syncedOwners.length - 1; ow > -1; ow--) {
                var copies = await contract.balanceOf(
                    syncedOwners[ow].owner,
                    tokenId
                );
                if (Number(copies) > 0) {
                    owners.push(syncedOwners[ow]);
                    return owners;
                }
            }
        }
    } catch (e) {
        //console.log(e);
    }
    return owners;
}

async function getOwnedCollections(me, type, forDetails) {
    var collections = [];
    const address = toAddress(me);
    const contract = new ethers.Contract(
        contractFactoryAddress,
        factoryABI,
        rpcprovider
    );
    try {
        var num = 0;
        while (true) {
            var col = null;
            var ABI;

            type == 721
                ? (col = await contract.ERC721contracts(num))
                : (col = await contract.ERC1155contracts(num));
            type == 721 ? (ABI = bhc721) : (ABI = bhc1155);
            var colCon = new ethers.Contract(col, ABI, rpcprovider);
            var owner = await colCon.owner();

            console.log(col);
            try {
                if (toAddress(owner) == toAddress(me)) {
                    var uri = await colCon.contract_URI();

                    var res = null;
                    try {
                        res = await axios.get(
                            //uri.replace("https://ipfs.io", "gateway.pinata.io")
                            uri.replace("https://ipfs.io/ipfs/", "/data/")
                        );
                    } catch (e) {
                        res = await axios.get(
                            uri.replace("ipfs.io", "gateway.pinata.io")
                            //uri.replace("https://ipfs.io", "/ipfs")
                        );
                    }
                    var collection = res.data;
                    console.log(collection);
                    collection.address = col;
                    collections.push(collection);
                } else if (forDetails) {
                    if (type == 1155) {
                        var res = await axios.get(
                            "/transfers/" + toAddress(col)
                        );
                        var transfers = res.data;
                        var filtered = transfers.filter(function(element) {
                            if (element.owner == toAddress(owner)) return true;
                        });
                        if (filtered.length > 0) {
                            var uri = await colCon.contract_URI();

                            var res = null;
                            try {
                                res = await axios.get(
                                    //uri.replace("https://ipfs.io", "gateway.pinata.io")
                                    uri.replace(
                                        "https://ipfs.io/ipfs/",
                                        "/data/"
                                    )
                                );
                            } catch (e) {
                                res = await axios.get(
                                    uri.replace("ipfs.io", "gateway.pinata.io")
                                    //uri.replace("https://ipfs.io", "/ipfs")
                                );
                            }
                            var collection = res.data;
                            console.log(collection);
                            collection.address = col;
                            collections.push(collection);
                        }
                    } else {
                        var uri = await colCon.contract_URI();

                        var res = null;
                        try {
                            res = await axios.get(
                                //uri.replace("https://ipfs.io", "gateway.pinata.io")
                                uri.replace("https://ipfs.io/ipfs/", "/data/")
                            );
                        } catch (e) {
                            res = await axios.get(
                                uri.replace("ipfs.io", "gateway.pinata.io")
                                //uri.replace("https://ipfs.io", "/ipfs")
                            );
                        }
                        var collection = res.data;
                        console.log(collection);
                        collection.address = col;
                        collections.push(collection);
                    }
                }
            } catch (e) {}
            num = num + 1;
        }
    } catch (e) {
        //console.log(e)
    }
    console.log({ COLS: collections });
    return collections;
}

async function get721Token(contract, collection, tokenId, owner) {
    try {
        const res = await axios.get(
            `/nftdata/${contract.address}?token_id=${tokenId}`
        );
        var tokenURI = null;
        if (res.data.length == 0) {
            tokenURI = await contract.tokenURI(tokenId);
            const res = await axios.post("/nftdata", {
                collection: contract.address,
                token_id: tokenId,
                uri: tokenURI
            });
        } else {
            tokenURI = res.data.uri;
        }

        const tokenData = {
            contract: contract.address,
            collection: collection,
            id: tokenId,
            tokenType: 721,
            ownedCopies: 1,
            tokenOwner: owner,
            URI: tokenURI //.replace("ipfs.io", "gateway.pinata.io")
        };
        return tokenData;
    } catch (e) {
        return {};
    }
}

async function get1155Token(contract, collection, tokenId, owner) {
    //const tokenCount = await contract.tokenCount(tokenId);
    try {
        const res = await axios.get(
            `/nftdata/${contract.address}?token_id=${tokenId}`
        );
        var tokenURI = null;
        if (res.data.length == 0) {
            tokenURI = await contract.tokenURI(tokenId);
            const res = await axios.post("/nftdata", {
                collection: contract.address,
                token_id: tokenId,
                uri: tokenURI
            });
        } else {
            tokenURI = res.data.uri;
        }
        const ownedCount = await contract.balanceOf(owner, tokenId);

        const tokenData = {
            contract: contract.address,
            collection: collection,
            id: tokenId,
            tokenType: 1155,
            ownedCopies: ownedCount,
            tokenOwner: owner,
            URI: tokenURI //.replace("ipfs.io", "gateway.pinata.io")
        };
        return tokenData;
    } catch (e) {
        return {};
    }
}

async function getSingles(contractAddress, owner, collection) {
    var tokens = [];
    try {
        const contract = new ethers.Contract(
            contractAddress,
            bhc721,
            rpcprovider
        );
        const nftCount = await contract.balanceOf(owner);
        for (var i = 0; i < Number(nftCount); i++) {
            var tokenId = await contract.tokenOfOwnerByIndex(owner, i);
            var nft = await get721Token(
                contract,
                collection,
                Number(tokenId),
                owner
            );
            tokens.push(nft);
        }
    } catch (e) {}
    return tokens;
}

async function getMultiples(contractAddress, owner, collection) {
    var tokens = [];
    var transfers = [];
    var lastSyncedId = 1;
    try {
        const contract = new ethers.Contract(
            contractAddress,
            bhc1155,
            rpcprovider
        );
        const currentId = await contract.current_id();
        var transfersRes = await axios.get("/transfers/" + contractAddress);
        var filteredTransfers = transfersRes.data.filter(function(element) {
            if (element.owner == owner) return true;
        });
        for (var i = 0; i < filteredTransfers.length; i++) {
            var col = filteredTransfers[i].collection;
            var id = filteredTransfers[i].token_id;
            if (id > lastSyncedId) {
                lastSyncedId = id;
            }
            var existing = transfers.filter(function(element) {
                if (element.collection == col && element.token_id == id)
                    return true;
            });
            if (existing.length == 0) {
                transfers.push(filteredTransfers[i]);
            }
        }
        for (var i = 0; i < transfers.length; i++) {
            try {
                var ownedCount = await contract.balanceOf(
                    owner,
                    transfers[i].token_id
                );
                if (ownedCount > 0) {
                    var nft = await get1155Token(
                        contract,
                        collection,
                        transfers[i].token_id,
                        owner
                    );
                    tokens.push(nft);
                }
            } catch (e) {
                console.log(e);
            }
        }
        /*for (var i = Number(lastSyncedId) + 1; i < currentId + 1; i++) {
            try {
                var ownedCount = await contract.balanceOf(owner, i);
                if (ownedCount > 0) {
                    var nft = await get1155Token(
                        contract,
                        collection,
                        i,
                        owner
                    );
                    tokens.push(nft);
                }
            } catch (e) {}
        }*/
    } catch (e) {}
    return tokens;
}

async function getCollectible(contractAddress, type, isPrivate, owner, id) {
    var collectible = {};
    var contractAddress = toAddress(contractAddress);
    var owner = toAddress(owner);
    try {
        if (type == 721) {
            var contract = new ethers.Contract(
                contractAddress,
                isPrivate ? bhc721 : bhc721,
                rpcprovider
            );
            var realOwner = await contract.ownerOf(id);
            var col = await contract.contract_URI();
            //var res = await axios.get(col);
            var collection = { URI: col }; //res.data;
            collection.address = contractAddress;
            if (owner == toAddress(realOwner)) {
                var collectible = await get721Token(
                    contract,
                    collection,
                    id,
                    owner
                );
            }
        } else if (type == 1155) {
            var contract = new ethers.Contract(
                contractAddress,
                isPrivate ? bhc1155 : bhc1155,
                rpcprovider
            );
            var ownerHave = await contract.balanceOf(owner, id);
            var col = await contract.contract_URI();
            //var res = await axios.get(col);
            var collection = { URI: col }; //res.data;
            collection.address = contractAddress;
            if (Number(ownerHave) > 0) {
                var collectible = await get1155Token(
                    contract,
                    collection,
                    id,
                    owner
                );
            }
        }
    } catch (e) {}
    return collectible;
}

async function generateOrderIdMessage(
    tokenAddress,
    tokenId,
    value,
    priceToken,
    price,
    salt
) {
    const signer = provider.getSigner();
    const exchange = new ethers.Contract(exchangeAddress, exchangeABI, signer);
    const order = await exchange.generateMessage(
        tokenAddress,
        tokenId,
        value,
        priceToken,
        ethers.utils.parseEther(`${price}`),
        salt
    );
    return order;
}

async function generateOrderId(
    tokenAddress,
    tokenId,
    value,
    priceToken,
    price,
    salt
) {
    const signer = provider.getSigner();
    const exchange = new ethers.Contract(exchangeAddress, exchangeABI, signer);
    const order = await exchange.generateKey(
        tokenAddress,
        tokenId,
        value,
        priceToken,
        ethers.utils.parseEther(`${price}`),
        salt
    );
    return order;
}

async function checkOrder(
    tokenAddress,
    tokenId,
    value,
    priceToken,
    price,
    salt
) {
    try {
        const exchange = new ethers.Contract(
            exchangeAddress,
            exchangeABI,
            rpcprovider
        );
        const nftStorage = new ethers.Contract(
            NFTStorageAddress,
            nftStorageABI,
            rpcprovider
        );
        const orderKey = await exchange.generateKey(
            tokenAddress,
            tokenId,
            value,
            priceToken,
            ethers.utils.parseEther(`${price}`),
            salt
        );
        const order = await nftStorage.getOrder(orderKey);
        return order;
    } catch (e) {
        return {};
    }
}

async function availableToBuy(
    tokenAddress,
    tokenId,
    value,
    priceToken,
    price,
    salt
) {
    var available = null;
    try {
        const order = await checkOrder(
            tokenAddress,
            tokenId,
            value,
            priceToken,
            price,
            salt
        );
        available = Number(order.total) - Number(order.sold);
        if (Number(order.total) == 0) {
            available = value;
        }
    } catch (e) {
        console.log(e);
    }
    return available;
}

async function checkNFTApproved(contractAddress, from) {
    const ABI = bhc721;
    try {
        const contract = new ethers.Contract(
            toAddress(contractAddress),
            ABI,
            rpcprovider
        );
        const res = await contract.isApprovedForAll(from, transferProxyAddress);
        return res;
    } catch (e) {
        return false;
    }
}
async function checkTokensApproved(contractAddress, from) {
    const ABI = bep20ABI;
    try {
        const contract = new ethers.Contract(
            toAddress(contractAddress),
            ABI,
            rpcprovider
        );
        const res = await contract.allowance(from, erc20TransferProxyAddress);
        //console.log(Number(res) / 10 ** 18);
        return Number(res) / 10 ** 18;
    } catch (e) {
        return 0;
    }
}

async function checkTokensBalance(contractAddress, from) {
    const ABI = bep20ABI;
    try {
        const contract = new ethers.Contract(
            toAddress(contractAddress),
            ABI,
            rpcprovider
        );
        const res = await contract.balanceOf(from);
        return Number(res) / 10 ** 18;
    } catch (e) {
        return 0;
    }
}

async function getMinted(log) {
    try {
        const contract = new ethers.utils.Interface(nftStorageABI);
        var data = await contract.parseLog(log);
        var collection = data.args[1];
        var tokenId = data.args[2];
        return { collection: toAddress(collection), tokenId: Number(tokenId) };
    } catch (e) {
        return {};
    }
}

async function getFees() {
    try {
        const minter = new ethers.Contract(
            minterAddress,
            minterABI,
            rpcprovider
        );
        const feeInHps = await minter.requiredFee(
            ethers.utils.parseEther("3"),
            ethers.utils.parseEther("5")
        );
        return feeInHps[0] / 10 ** 18;
    } catch (e) {
        return 0;
    }
}

async function serviceFee(currencyName) {
    const exchange = new ethers.Contract(
        exchangeAddress,
        exchangeABI,
        rpcprovider
    );
    const minter = new ethers.Contract(minterAddress, minterABI, rpcprovider);
    try {
        var fees = await exchange.requiredFee(
            ethers.utils.parseEther("5"),
            ethers.utils.parseEther("5")
        );
        if (currencyName == "BNB") {
            return Number(fees[1] / 10 ** 18); //.toFixed(3)
        } else if ("BHC") {
            return Number(fees[0] / 10 ** 18);
        } else if ("HPS") {
            fees = await minter.requiredFee(
                ethers.utils.parseEther("3"),
                ethers.utils.parseEther("5")
            );
            return Number(fees[0] / 10 ** 18);
        }
    } catch (e) {
        return 0;
    } //.toFixed(3);
}

//////Set functions/////////

async function createASingle(url, royalty, collection, isBNB) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    console.log(contract);
    var sFee = await serviceFee(isBNB ? "BNB" : "HPS");
    console.log(serviceFee);
    var tx = await contract.mint721(
        collection,
        url,
        BigNumber.from(Number(royalty)),
        isBNB,
        {
            value: isBNB
                ? ethers.utils.parseEther(`${sFee * 1.03}`)
                : ethers.utils.parseEther("0")
            //gasLimit: BigNumber.from("300000")
        }
    ); //, gasPrice:BigNumber.from(30000000000), //gasLimit: BigNumber.from(8500000)});
    return tx;
}

async function createABatch(url, count, royalty, collection, isBNB) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    console.log(contract);
    var sFee = await serviceFee(isBNB ? "BNB" : "HPS");
    var tx = await contract.mint1155(
        collection,
        url,
        BigNumber.from(Number(count)),
        BigNumber.from(Number(royalty)),

        isBNB,
        {
            value: isBNB
                ? ethers.utils.parseEther(`${sFee * 1.03}`)
                : ethers.utils.parseEther("0")
            //gasLimit: BigNumber.from(300000)
        }
    );

    return tx;
}

async function createCollection(type, uri, isBNB) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    var sFee = await serviceFee(isBNB ? "BNB" : "HPS");
    if (type == 721) {
        var tx = await contract.generate721(
            contractFactoryAddress,
            uri,

            isBNB,
            {
                value: isBNB
                    ? ethers.utils.parseEther(`${sFee * 1.03}`)
                    : ethers.utils.parseEther("0")
                //gasLimit: BigNumber.from(3000000)
            }
        );
    } else {
        var tx = await contract.generate1155(
            contractFactoryAddress,
            "https://ipfs.io/ipfs/",
            uri,

            isBNB,
            {
                value: isBNB
                    ? ethers.utils.parseEther(`${sFee * 1.03}`)
                    : ethers.utils.parseEther("0")
                //gasLimit: BigNumber.from(3000000)
            }
        );
    }
    return tx.hash;
}

async function approveNFT(contractAddress) {
    const signer = provider.getSigner();
    const ABI = bhc721;
    const contract = new ethers.Contract(
        toAddress(contractAddress),
        ABI,
        signer
    );
    const tx = await contract.setApprovalForAll(transferProxyAddress, true);
    return tx;
}

async function approveTokens(contractAddress, price) {
    const signer = provider.getSigner();
    const ABI = bep20ABI;
    const contract = new ethers.Contract(
        toAddress(contractAddress),
        ABI,
        signer
    );
    const tx = await contract.approve(
        erc20TransferProxyAddress,
        ethers.utils.parseEther(`${Number(price) * 1.03}`)
    );
    return tx.hash;
}
async function transfer(contractAddress, owner, receiver, type, id, quantity) {
    const signer = provider.getSigner();
    let ABI;
    if (type == 721) {
        ABI = bhc721;
    } else {
        ABI = bhc1155;
    }
    const token_contract = new ethers.Contract(
        toAddress(contractAddress),
        ABI,
        signer
    );
    const tx = await contract.safeTransferFrom(
        owner,
        receiver,
        id,
        quantity,
        ""
    );
    const res = tx.wait();
    if (res.status == 1) {
        return true;
    } else {
        return false;
    }
}
async function burn(contractAddress, owner, type, id, quantity) {
    const signer = provider.getSigner();
    let ABI;
    if (type == 721) {
        ABI = bhc721;
    } else {
        ABI = bhc1155;
    }
    const token_contract = new ethers.Contract(
        toAddress(contractAddress),
        ABI,
        signer
    );
    const tx = await contract.burnToken(id, quantity);
    const res = tx.wait();
    if (res.status == 1) {
        return true;
    } else {
        return false;
    }
}

async function buy(
    collection,
    is721,
    tokenId,
    total,
    value,
    buyWith,
    price,
    salt,
    owner,
    signature,
    totalPayment
) {
    const signer = provider.getSigner();
    const exchange = new ethers.Contract(exchangeAddress, exchangeABI, signer);
    const sig = ethers.utils.splitSignature(signature);
    console.log([
        is721,
        collection,
        tokenId,
        total,
        value,
        buyWith,
        price,
        owner,
        salt,
        sig
    ]);
    const tx = await exchange.exchange(
        [
            is721,
            collection,
            tokenId,
            total,
            value,
            buyWith,
            ethers.utils.parseEther(`${price}`),
            owner,
            salt,

            sig.v,
            sig.r,
            sig.s
        ],
        {
            ////gasLimit: BigNumber.from(300000),
            value:
                buyWith == toAddress("")
                    ? ethers.utils.parseEther(`${Number(totalPayment) * 1.03}`)
                    : "0"
        }
    );
    return tx.hash;
}

export {
    transfer,
    burn,
    checkConnection,
    getOwner,
    signMessage,
    toAddress,
    createASingle,
    getSingles,
    createABatch,
    getMultiples,
    waitForTransaction,
    getCollectible,
    generateOrderIdMessage,
    generateOrderId,
    checkNFTApproved,
    approveNFT,
    checkTokensApproved,
    approveTokens,
    checkTokensBalance,
    buy,
    splitSign,
    getBNBBalance,
    createCollection,
    collectionURI,
    getOwnedCollections,
    getCollection,
    getCollectionType,
    getOwnersOf,
    getAnOwner,
    getCreated,
    getMinted,
    getFees,
    serviceFee,
    checkOrder,
    availableToBuy,
    getBalanceInUSDT
};
