import { BigNumber, ethers } from 'ethers';
const bhc721 = require('../js/abis/bhc_721.json')
const bhc1155 = require('../js/abis/bhc_1155.json')
const provider = new ethers.providers.Web3Provider(window.ethereum);
//const selectedAddress = provider.provider.selectedAddress;

//reads
function checkConnection() {

}


async function waitForTransaction(tx) {
    var res = await provider.waitForTransaction(tx);
    console.log(res)
    return res.status;
}

function toAddress(addressString) {
    return ethers.utils.isAddress(addressString) ? ethers.utils.getAddress(addressString) :
        ethers.utils.getAddress('0x0000000000000000000000000000000000000000');
}

async function getOwner(addressString, ABI) {
    var contractAddress = toAddress(addressString);
    var contract = new ethers.Contract(contractAddress, ABI);
    var data = await contract.owner();
    return owner;
}

async function getSingles(contractAddress, owner) {
    var tokens = [];
    var contract = new ethers.Contract(contractAddress, bhc721, provider);
    var singlesHex = await contract.balanceOf(owner);
    var singleCount = Number(singlesHex);
    for (var i = 0; i < singleCount; i++) {
        var data = await contract.tokenOfOwnerByIndex(owner, i)
        var dat = await contract.tokenURI(Number(data))
        tokens.push({ "contract": contractAddress, "id": Number(data), "uri": dat })
    }
    console.log(tokens.length)
    return tokens;
}

async function getMultiples(contractAddress, owner) {
    var tokens = [];
    var contract = new ethers.Contract(contractAddress, bhc1155, provider);
    var currentIdHex = await contract.current_id();
    var currentId = Number(currentIdHex);
    for (var i = 1; i < currentId; i++) {
        var multiplesHex = await contract.balanceOf(owner, i);
        var multiplesCount = Number(multiplesHex);
        if (multiplesCount > 0) {
            var uri = await contract.tokenURI(i);
            tokens.push({
                "contract": contractAddress, "id": i, "uri": uri, count: multiplesCount
            })
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
        if (owner == toAddress(realOwner)) {
            var uri = await contract.tokenURI(id);
            collectible = {
                contract: contractAddress,
                owner: owner,
                uri: uri,
                id: id
            }
        }
    }
    return collectible;
}

/////writes
async function signMessage(message) {
    const signer = provider.getSigner();
    return await signer.signMessage(message);
}

async function createASingle(url, contract) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(contract, bhc721, signer);
    var tx = await contract.mintToken(window.ethereum.selectedAddress, url);
    return tx;
}

async function createABatch(url, count, contract) {
    const signer = provider.getSigner();
    var contract = new ethers.Contract(contract, bhc1155, signer);
    var tx = await contract.mintToken(url, BigNumber.from(Number(count)), ethers.utils.hexlify(0));
    return tx;
}

export { getOwner, signMessage, toAddress, createASingle, getSingles, createABatch, getMultiples, waitForTransaction, getCollectible }