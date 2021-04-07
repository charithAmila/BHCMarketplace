import { BigNumber, ethers } from "ethers";
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
    NFTStorageAddress
} from "./addresses/constants";
//console.log(ethers.utils.splitSignature("0x32d9e9324ca4d87e0aa56837cf0929bc49f7cf8db3f2ca734e1e50a6b982aadc403dfa3f3d79edfddd68814efca3168cf63f0d3a4c25511b23d0782c824927571b"))
/////////abis///////////////////
const bhc721 = require("../js/abis/bhc_721.json");
const bhc1155 = require("../js/abis/bhc_1155.json");
const nftStorageABI = require("../js/abis/nft_storage.json");
const exchangeABI = require("../js/abis/new_exchange.json");
const bep20ABI = require("./abis/bep20.json");
const minterABI = require("./abis/minter.json");
const factoryABI = require("./abis/factory.json");

if (typeof window.ethereum == "undefined") {
    window.provider = new ethers.providers.JsonRpcProvider(
        "https://data-seed-prebsc-1-s1.binance.org:8545"
    );
} else {
    window.provider = new ethers.providers.Web3Provider(window.ethereum);
}

//const selectedAddress = provider.provider.selectedAddress;

///////Get function//////////

function toAddress(addressString) {
    return ethers.utils.isAddress(addressString) ?
        ethers.utils.getAddress(addressString) :
        ethers.utils.getAddress("0x0000000000000000000000000000000000000000");
}

function checkConnection() {
    try {
        var acc = toAddress(provider.provider.selectedAddress);
        window.connected_account = acc;
    } catch (e) {
        var acc = null;
    }
    return acc;
}

function redirectToConnect() {
    if (checkConnection() == null) {
        window.location.href = "/connect";
    }
}

async function getBNBBalance(address) {
    try {
        var balance = await provider.getBalance(toAddress(address));
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
    var res = await provider.waitForTransaction(tx);
    return res;
}

async function collectionURI(contractAddress) {
    try {
        const contract = new ethers.Contract(contractAddress, bhc721, provider);
        const uri = contract.contract_URI();
        return uri;
    } catch (e) {
        return "";
    }
}

async function getOwner(addressString, ABI) {
    try {
        var contractAddress = toAddress(addressString);
        var contract = new ethers.Contract(contractAddress, ABI, provider);
        var data = await contract.owner();
        return owner;
    } catch (e) {
        return toAddress("");
    }
}

async function getCollection(collectionAddess) {
    var owners = [];
    try {
        const ERC1155Interface = "0x0e89341c";
        const ERC721Interface = "0x80ac58cd";
        var filters = {};

        var contract = new ethers.Contract(
            toAddress(collectionAddess),
            bhc721,
            provider
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
                provider
            );
            var evts = await contract.queryFilter(
                "TransferSingle",
                0,
                "latest"
            );
            var ownerById = {};
            for (var i = 0; i < evts.length; i++) {
                var tokenId = Number(evts[i].args.id);
                var owner = evts[i].args.to;
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
    } catch (e) {}
    return owners;
}

async function getCreated(owner) {
    const tokens = [];

    try {
        const nftStorage = new ethers.Contract(
            NFTStorageAddress,
            nftStorageABI,
            provider
        );
        var evts = await nftStorage.queryFilter("NFTAdded", 0, "latest");

        for (var i = 0; i < evts.length; i++) {
            var event = evts[i];
            var creator = toAddress(event.args._creator);
            if (owner == creator) {
                var type = getCollectionType(event.args._collection);
                tokens.push({
                    contract: event.args._collection,
                    token_id: Number(event.args._id)
                });
            }
        }
        console.log(tokens);
    } catch (e) {}
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
            provider
        );
        var is721 = await contract.supportsInterface(ERC721Interface);
        var is1155 = await contract.supportsInterface(ERC1155Interface);

        return is721 ? 721 : is1155 ? 1155 : null;
    } catch (e) {
        return null;
    }
}

async function getOwnersOf(collectionAddess, tokenId) {
    const ERC1155Interface = "0x0e89341c";
    const ERC721Interface = "0x80ac58cd";

    var filters = {};
    var owners = [];

    try {
        var contract = new ethers.Contract(
            toAddress(collectionAddess),
            bhc721,
            provider
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
                provider
            );
            var evts = await contract.queryFilter(
                "TransferSingle",
                0,
                "latest"
            );
            var ownerById = {};
            for (var i = 0; i < evts.length; i++) {
                if (Number(evts[i].args.id) == tokenId) {
                    var owner = evts[i].args.to;
                    var copies = await contract.balanceOf(
                        owner,
                        evts[i].args.id
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
    } catch (e) {}
    return owners;
}

async function getOwnedCollections(me, type, forDetails) {
    var collections = [];
    const address = toAddress(me);
    const contract = new ethers.Contract(
        contractFactoryAddress,
        factoryABI,
        provider
    );
    try {
        var num = 0;
        while (true) {
            var col = null;
            var ABI;

            type == 721 ?
                (col = await contract.ERC721contracts(num)) :
                (col = await contract.ERC1155contracts(num));
            type == 721 ? (ABI = bhc721) : (ABI = bhc1155);
            var colCon = new ethers.Contract(col, ABI, provider);
            var owner = await colCon.owner();

            console.log(col);
            if (toAddress(owner) == toAddress(me) || forDetails) {
                var uri = await colCon.contract_URI();

                var res = await axios.get(uri);
                var collection = res.data;
                collection.address = col;
                collections.push(collection);
            }
            num = num + 1;
        }
    } catch (e) {}
    return collections;
}

async function get721Token(contract, collection, tokenId, owner) {
    try {
        const tokenURI = await contract.tokenURI(tokenId);
        const tokenData = {
            contract: contract.address,
            collection: collection,
            id: tokenId,
            tokenType: 721,
            ownedCopies: 1,
            tokenOwner: owner,
            URI: tokenURI
        };
        return tokenData;
    } catch (e) {
        return {};
    }
}

async function get1155Token(contract, collection, tokenId, owner) {
    //const tokenCount = await contract.tokenCount(tokenId);
    try {
        const tokenURI = await contract.tokenURI(tokenId);
        const ownedCount = await contract.balanceOf(owner, tokenId);

        const tokenData = {
            contract: contract.address,
            collection: collection,
            id: tokenId,
            tokenType: 1155,
            ownedCopies: ownedCount,
            tokenOwner: owner,
            URI: tokenURI
        };
        return tokenData;
    } catch (e) {
        return {};
    }
}

async function getSingles(contractAddress, owner, collection) {
    var tokens = [];
    try {
        const contract = new ethers.Contract(contractAddress, bhc721, provider);
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
    try {
        const contract = new ethers.Contract(
            contractAddress,
            bhc1155,
            provider
        );
        const currentId = await contract.current_id();
        for (var i = 1; i < Number(currentId) + 1; i++) {
            var ownedCount = await contract.balanceOf(owner, i);
            if (ownedCount > 0) {
                var nft = await get1155Token(contract, collection, i, owner);
                tokens.push(nft);
            }
        }
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
                provider
            );
            var realOwner = await contract.ownerOf(id);
            var col = await contract.contract_URI();
            var res = await axios.get(col);
            var collection = res.data;
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
                provider
            );
            var ownerHave = await contract.balanceOf(owner, id);
            var col = await contract.contract_URI();
            var res = await axios.get(col);
            var collection = res.data;
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
            provider
        );
        const nftStorage = new ethers.Contract(
            NFTStorageAddress,
            nftStorageABI,
            provider
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

async function checkNFTApproved(contractAddress, from) {
    const ABI = bhc721;
    try {
        const contract = new ethers.Contract(
            toAddress(contractAddress),
            ABI,
            provider
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
            provider
        );
        const res = await contract.allowance(from, erc20TransferProxyAddress);
        console.log(Number(res) / 10 ** 18);
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
            provider
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
        const minter = new ethers.Contract(minterAddress, minterABI, provider);
        const feeInHps = await minter.requiredFee();
        return feeInHps / 10 ** 18;
    } catch (e) {
        return 0;
    }
}

//////Set functions/////////

async function createASingle(url, royalty, collection, isBNB) {
    try {
        const signer = provider.getSigner();
        var contract = new ethers.Contract(minterAddress, minterABI, signer);
        console.log(contract);
        var tx = await contract.mint721(
            collection,
            url,
            BigNumber.from(Number(royalty)),
            isBNB, {
                value: isBNB ?
                    ethers.utils.parseEther("0.025") :
                    ethers.utils.parseEther("0"),
                gasLimit: BigNumber.from("3000000")
            }
        ); //, gasPrice:BigNumber.from(30000000000), gasLimit: BigNumber.from(8500000)});
        return tx;
    } catch (e) {
        return null;
    }
}

async function createABatch(url, count, royalty, collection, isBNB) {
    try {
        const signer = provider.getSigner();
        var contract = new ethers.Contract(minterAddress, minterABI, signer);
        console.log(contract);
        var tx = await contract.mint1155(
            collection,
            url,
            BigNumber.from(Number(count)),
            BigNumber.from(Number(royalty)),

            isBNB, {
                value: isBNB ?
                    ethers.utils.parseEther("0.025") :
                    ethers.utils.parseEther("0"),
                gasLimit: BigNumber.from(3000000)
            }
        );

        return tx;
    } catch (e) {
        return null;
    }
}

async function createCollection(type, uri, isBNB) {
    try {
        const signer = provider.getSigner();
        var contract = new ethers.Contract(minterAddress, minterABI, signer);
        if (type == 721) {
            var tx = await contract.generate721(
                contractFactoryAddress,
                uri,

                isBNB, {
                    value: isBNB ?
                        ethers.utils.parseEther("0.025") :
                        ethers.utils.parseEther("0"),
                    gasLimit: BigNumber.from(3000000)
                }
            );
        } else {
            var tx = await contract.generate1155(
                contractFactoryAddress,
                "https://ipfs.io/ipfs/",
                uri,

                isBNB, {
                    value: isBNB ?
                        ethers.utils.parseEther("0.025") :
                        ethers.utils.parseEther("0"),
                    gasLimit: BigNumber.from(3000000)
                }
            );
        }
        return tx.hash;
    } catch (e) {
        return null;
    }
}

async function approveNFT(contractAddress) {
    try {
        const signer = provider.getSigner();
        const ABI = bhc721;
        const contract = new ethers.Contract(
            toAddress(contractAddress),
            ABI,
            signer
        );
        const tx = await contract.setApprovalForAll(transferProxyAddress, true);
        return tx;
    } catch (e) {
        return null;
    }
}

async function approveTokens(contractAddress, price) {
    try {
        const signer = provider.getSigner();
        const ABI = bep20ABI;
        const contract = new ethers.Contract(
            toAddress(contractAddress),
            ABI,
            signer
        );
        const tx = await contract.approve(
            erc20TransferProxyAddress,
            ethers.utils.parseEther(price)
        );
        return tx.hash;
    } catch (e) {
        return null;
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
    signature
) {
    try {
        const signer = provider.getSigner();
        const exchange = new ethers.Contract(
            exchangeAddress,
            exchangeABI,
            signer
        );
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
            ], {
                gasLimit: BigNumber.from(3000000),
                value: buyWith == toAddress("") ?
                    ethers.utils.parseEther(`${Number(price) * 1.025}`) :
                    "0"
            }
        );
        return tx.hash;
    } catch (e) {
        return null;
    }
}

export {
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
    getCreated,
    getMinted,
    getFees,
    checkOrder
};