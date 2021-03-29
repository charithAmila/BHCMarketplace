import { BigNumber, ethers } from 'ethers';
import { hps721Address, hps1155Address, transferProxyAddress, erc20TransferProxyAddress, orderStorageAddress, exchangeAddress, hpsAddress, bhcAddress, minterAddress } from "./addresses/constants"
//console.log(ethers.utils.splitSignature("0x32d9e9324ca4d87e0aa56837cf0929bc49f7cf8db3f2ca734e1e50a6b982aadc403dfa3f3d79edfddd68814efca3168cf63f0d3a4c25511b23d0782c824927571b"))
/////////abis///////////////////
const bhc721 = require('../js/abis/bhc_721.json')
const bhc1155 = require('../js/abis/bhc_1155.json')
const orderStorageABI = require("../js/abis/order_storage.json")
const exchangeABI = require("../js/abis/new_exchange.json")
const bep20ABI = require("./abis/bep20.json")
const minterABI = require("./abis/minter.json")



const provider = new ethers.providers.Web3Provider(window.ethereum);
//const selectedAddress = provider.provider.selectedAddress;

///////Get function//////////

function toAddress(addressString) {
    return ethers.utils.isAddress(addressString) ? ethers.utils.getAddress(addressString) :
        ethers.utils.getAddress('0x0000000000000000000000000000000000000000');
}

function checkConnection() {
    var acc = toAddress(provider.provider.selectedAddress);
    return acc;
}

function redirectToConnect() {
    if (checkConnection() == null) {
        window.location.href = "/connect"
    }
}

async function signMessage(message) {
    const signer = provider.getSigner();
    return await signer.signMessage(message);
}

function splitSign(signature) {
    return ethers.utils.splitSignature(signature)
}

async function waitForTransaction(tx) {
    var res = await provider.waitForTransaction(tx);
    console.log(res)
    return res.status;
}

async function getOwner(addressString, ABI) {
    var contractAddress = toAddress(addressString);
    var contract = new ethers.Contract(contractAddress, ABI);
    var data = await contract.owner();
    return owner;
}

async function get721Token(contract, collection, tokenId, owner) {
    const tokenURI = await contract.tokenURI(tokenId);
    const tokenData = {
        contract: contract.address,
        collection: collection,
        id: tokenId,
        tokenType: 721,
        availableCopies: 1,
        ownedCopies: 1,
        tokenOwner: owner,
        URI: tokenURI,
    }
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
        availableCopies: ownedCount,//tokenCount,
        ownedCopies: ownedCount,
        tokenOwner: owner,
        URI: tokenURI,
    }
    return tokenData;
}

async function getSingles(contractAddress, owner) {
    var tokens = [];
    const contract = new ethers.Contract(contractAddress, bhc721, provider);
    const collection = null//await contract.contractURI();
    const nftCount = await contract.balanceOf(owner);
    for (var i = 0; i < Number(nftCount); i++) {
        var tokenId = await contract.tokenOfOwnerByIndex(owner, i)
        var nft = await get721Token(contract, collection, Number(tokenId), owner)
        tokens.push(nft)
    }
    return tokens;

}

async function getMultiples(contractAddress, owner) {
    var tokens = [];
    const contract = new ethers.Contract(contractAddress, bhc1155, provider);
    const currentId = await contract.current_id();
    const collection = null;//await contract.contractURI();
    for (var i = 1; i < Number(currentId); i++) {
        var ownedCount = await contract.balanceOf(owner, i);;
        if (ownedCount > 0) {
            var nft = await get1155Token(contract, collection, i, owner);
            tokens.push(nft)
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
        )
        var realOwner = await contract.ownerOf(id);
        var collection = null//await contract.contractURI();
        if (owner == toAddress(realOwner)) {
            var collectible = await get721Token(contract, collection, id, owner);
        }
    }
    else if (type == 1155) {
        var contract = new ethers.Contract(
            contractAddress,
            isPrivate ? bhc1155 : bhc1155,
            provider
        )
        var ownerHave = await contract.balanceOf(owner, id);
        var collection = null//await contract.contractURI();
        if (Number(ownerHave) > 0) {
            var collectible = await get1155Token(contract, collection, id, owner);
        }
    }
    return collectible;
}

async function generateOrderIdMessage(tokenAddress, tokenId, value, priceToken, price, salt) {
    const signer = provider.getSigner()
    const orderStorage = new ethers.Contract(orderStorageAddress, orderStorageABI, signer);
    const order = await orderStorage.generateMessage(tokenAddress, tokenId, value, priceToken, BigNumber.from(price).mul(BigNumber.from(10).pow(18)), salt);
    return (salt, order);
}

async function checkNFTApproved(contractAddress, from) {
    const ABI = bhc721;
    const contract = new ethers.Contract(toAddress(contractAddress), ABI, provider);
    const res = await contract.isApprovedForAll(from, transferProxyAddress);
    return res;
}
async function checkTokensApproved(contractAddress, from) {
    const ABI = bep20ABI;
    const contract = new ethers.Contract(toAddress(contractAddress), ABI, provider);
    const res = await contract.allowance(from, erc20TransferProxyAddress);
    console.log(Number(res) / (10 ** 18))
    return Number(res) / (10 ** 18);
}

async function checkTokensBalance(contractAddress, from) {
    const ABI = bep20ABI;
    const contract = new ethers.Contract(toAddress(contractAddress), ABI, provider);
    const res = await contract.balanceOf(from);
    return Number(res) / (10 ** 18);
}

//////Set functions/////////

async function createASingle(url, royalty, collection) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    console.log(contract)
    var tx = await contract.mint721(collection, url, BigNumber.from(Number(royalty)), true, { value: ethers.utils.parseEther("0.25") });
    return tx;
}

async function createABatch(url, count, royalty, collection) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(minterAddress, minterABI, signer);
    console.log(contract)
    var tx = await contract.mint721(collection, url, BigNumber.from(Number(count)), BigNumber.from(Number(royalty)), true)//, { value: ethers.utils.parseEther("0.25") });
    return tx;
}

async function approveNFT(contractAddress) {
    const signer = provider.getSigner();
    const ABI = bhc721;
    const contract = new ethers.Contract(toAddress(contractAddress), ABI, signer);
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

async function buy(collection, is721, tokenId, value, buyWith, price, salt, owner, signature) {
    console.log([is721, collection, tokenId, value, buyWith, price, owner, salt, signature])
    const signer = provider.getSigner()
    const exchange = new ethers.Contract(exchangeAddress, exchangeABI, signer)
    console.log(exchange)
    const sig = ethers.utils.splitSignature(signature)

    const tx = await exchange.exchange(
        [
            is721,
            collection,
            tokenId,
            value,
            value,
            buyWith,
            BigNumber.from(price).mul(BigNumber.from(10).pow(18)),
            owner,
            salt,
            sig.v,
            sig.r,
            sig.s
        ]
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
    splitSign
}
