import { BigNumber, ethers } from 'ethers';
import { hps721Address, hps1155Address, transferProxyAddress, erc20TransferProxyAddress, orderStorageAddress, exchangeAddress, hpsAddress, bhcAddress, minterAddress, contractFactoryAddress } from "./addresses/constants"
//console.log(ethers.utils.splitSignature("0x32d9e9324ca4d87e0aa56837cf0929bc49f7cf8db3f2ca734e1e50a6b982aadc403dfa3f3d79edfddd68814efca3168cf63f0d3a4c25511b23d0782c824927571b"))
/////////abis///////////////////
const bhc721 = require('../js/abis/bhc_721.json')
const bhc1155 = require('../js/abis/bhc_1155.json')
const orderStorageABI = require("../js/abis/order_storage.json")
const exchangeABI = require("../js/abis/new_exchange.json")
const bep20ABI = require("./abis/bep20.json")
const minterABI = require("./abis/minter.json")
const factoryABI = require("./abis/factory.json")

if (typeof window.ethereum == "undefined") {
    alert("no provider fround");
    window.provider = new ethers.getDefaultProvider();
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
    var acc = toAddress(provider.provider.selectedAddress);
    return acc;
}

function redirectToConnect() {
    if (checkConnection() == null) {
        window.location.href = "/connect";
    }
}


async function getBNBBalance(address) {
    var balance = await provider.getBalance(toAddress(address));
    return balance / (10 ** 18);
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
    console.log(res);
    return res.status;
}

async function collectionURI(contractAddress) {
    const contract = new ethers.Contract(contractAddress, bhc721, provider);
    const uri = contract.contract_URI();
    return uri;
}

async function getOwner(addressString, ABI) {
    var contractAddress = toAddress(addressString);
    var contract = new ethers.Contract(contractAddress, ABI, provider);
    var data = await contract.owner();
    return owner;
}

async function getCollection(collectionAddess) {
    const ERC1155Interface = "0x0e89341c";
    const ERC721Interface = "0x80ac58cd";
    var filters = {}
    var owners = [];

    var contract = new ethers.Contract(toAddress(collectionAddess), bhc721, provider);
    var is721 = await contract.supportsInterface(ERC721Interface)
    var is1155 = await contract.supportsInterface(ERC1155Interface)
    if (is721) {
        var supply = await contract.totalSupply();
        for (var i = 0; i < Number(supply); i++) {
            var tokenId = await contract.tokenByIndex(i);
            var owner = await contract.ownerOf(tokenId);
            var tk = { "id": Number(tokenId), "owner": owner }

            !owners.includes(tk) ? owners.push(tk) : null
        }
    }
    else {
        contract = new ethers.Contract(toAddress(collectionAddess), bhc1155, provider)
        var evts = await contract.queryFilter("TransferSingle", 0, "latest")
        var ownerById = {}
        for (var i = 0; i < evts.length; i++) {
            var tokenId = Number(evts[i].args.id);
            var owner = evts[i].args.to;
            var tk = { "id": tokenId, "owner": owner }
            var obj = owners.filter(function (element) {
                if (element.id == tokenId &&
                    element.owner == owner) return true;
            })
            if (obj.length == 0) {
                owners.push(tk)
            }
        }


    }
    console.log(owners)
    return owners;
}

async function getCollectionType(collectionAddress) {
    const ERC1155Interface = "0x0e89341c";
    const ERC721Interface = "0x80ac58cd";
    var filters = {}
    var owners = [];
    var contract = new ethers.Contract(toAddress(collectionAddress), bhc721, provider);
    var is721 = await contract.supportsInterface(ERC721Interface)
    var is1155 = await contract.supportsInterface(ERC1155Interface)
    console.log([collectionAddress, is721])
    return is721 ? 721 : is1155 ? 1155 : null;
}

async function getOwnedCollections(me, type) {
    var collections = [];
    const address = toAddress(me);
    const contract = new ethers.Contract(contractFactoryAddress, factoryABI, provider);
    try {
        var num = 0;
        while (true) {
            var col = null;
            var ABI;
            type == 721 ? col = await contract.ERC721contracts(num) : col = await contract.ERC1155contracts(num);
            type == 721 ? ABI = bhc721 : ABI = bhc1155;
            var colCon = new ethers.Contract(col, ABI, provider);
            //var owner = await colCon.owner();
            console.log(col)
            //if (toAddress(owner) == toAddress(me)) {

            var uri = await colCon.contract_URI();

            var res = await axios.get(uri);
            var collection = res.data;
            collection.address = col;
            collections.push(collection);
            //}
            num = num + 1;
        }

    }
    catch (e) {
    }
    return collections;


}

async function get721Token(contract, collection, tokenId, owner) {
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
}

async function get1155Token(contract, collection, tokenId, owner) {
    //const tokenCount = await contract.tokenCount(tokenId);
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
}

async function getSingles(contractAddress, owner, collection) {
    var tokens = [];
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
    return tokens;
}

async function getMultiples(contractAddress, owner, collection) {
    var tokens = [];
    const contract = new ethers.Contract(contractAddress, bhc1155, provider);
    const currentId = await contract.current_id();
    for (var i = 1; i < Number(currentId) + 1; i++) {
        var ownedCount = await contract.balanceOf(owner, i);
        if (ownedCount > 0) {
            var nft = await get1155Token(contract, collection, i, owner);
            tokens.push(nft);
        }
    }
    return tokens;
}

async function getCollectible(contractAddress, type, isPrivate, owner, id) {
    var collectible = {};
    var contractAddress = toAddress(contractAddress);
    var owner = toAddress(owner);
    if (type == 721) {
        var contract = new ethers.Contract(
            contractAddress,
            isPrivate ? bhc721 : bhc721,
            provider
        );
        var realOwner = await contract.ownerOf(id);
        var col = await contract.contract_URI();
        var res = await axios.get(col);
        var collection = res.data
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
        var collection = res.data
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
    const orderStorage = new ethers.Contract(
        orderStorageAddress,
        orderStorageABI,
        signer
    );
    const order = await orderStorage.generateMessage(
        tokenAddress,
        tokenId,
        value,
        priceToken,
        BigNumber.from(price).mul(BigNumber.from(10).pow(18)),
        salt
    );
    return salt, order;
}

async function checkNFTApproved(contractAddress, from) {
    const ABI = bhc721;
    const contract = new ethers.Contract(
        toAddress(contractAddress),
        ABI,
        provider
    );
    const res = await contract.isApprovedForAll(from, transferProxyAddress);
    return res;
}
async function checkTokensApproved(contractAddress, from) {
    const ABI = bep20ABI;
    const contract = new ethers.Contract(
        toAddress(contractAddress),
        ABI,
        provider
    );
    const res = await contract.allowance(from, erc20TransferProxyAddress);
    console.log(Number(res) / 10 ** 18);
    return Number(res) / 10 ** 18;
}

async function checkTokensBalance(contractAddress, from) {
    const ABI = bep20ABI;
    const contract = new ethers.Contract(
        toAddress(contractAddress),
        ABI,
        provider
    );
    const res = await contract.balanceOf(from);
    return Number(res) / 10 ** 18;
}

//////Set functions/////////

async function createASingle(url, royalty, collection) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    console.log(contract)
    var tx = await contract.mint721(collection, url, BigNumber.from(Number(royalty)), false, { gasPrice: BigNumber.from(30000000000), gasLimit: BigNumber.from(8500000) });
    return tx;
}

async function createABatch(url, count, royalty, collection) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    console.log(contract)
    var tx = await contract.mint1155(collection, url, BigNumber.from(Number(count)), BigNumber.from(Number(royalty)), true, { value: ethers.utils.parseEther("0.25"), gasPrice: BigNumber.from(30000000000), gasLimit: BigNumber.from(8500000) });

    return tx;
}

async function createCollection(type, uri, isBNB) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    if (type == 721) {
        var tx = await contract.generate721(contractFactoryAddress, uri, isBNB, { value: ethers.utils.parseEther("0.25") });
    } else {
        var tx = await contract.generate1155(contractFactoryAddress, "https://ipfs.io/ipfs/", uri, isBNB, { value: ethers.utils.parseEther("0.25"), gasPrice: BigNumber.from(30000000000), gasLimit: BigNumber.from(8500000) });

    }
    return tx;
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
    const contract = new ethers.Contract(toAddress(contractAddress), ABI, signer);
    const tx = await contract.approve(erc20TransferProxyAddress, ethers.utils.parseEther(price));
    return tx.hash;
}

async function buy(collection, is721, tokenId, total, value, buyWith, price, salt, owner, signature) {
    var _price = Number(Number(price) * 1.125 * (10 ** 18)).toString();
    const signer = provider.getSigner()
    const exchange = new ethers.Contract(exchangeAddress, exchangeABI, signer)
    const sig = ethers.utils.splitSignature(signature)
    console.log([is721, collection, tokenId, total, value, buyWith, price, owner, salt, sig])
    const tx = await exchange.exchange(
        [
            is721,
            collection,
            tokenId,
            total,
            value,
            buyWith,
            BigNumber.from(price).mul(BigNumber.from(10).pow(18)),
            owner,
            salt,
            sig.v,
            sig.r,
            sig.s
        ],
        { gasPrice: BigNumber.from(30000000000), gasLimit: BigNumber.from(8500000), value: buyWith == toAddress("") ? BigNumber.from(_price) : "0" }
    )
    return tx.hash;
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
    getCollectionType

};
