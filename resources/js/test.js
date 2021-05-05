const ethers = require("ethers");
const NFTStorageAddress = "0x5aECe89bF2743bb1e0E33036fF174655AC68252c";
////removed//console.log(ethers.utils.splitSignature("0x32d9e9324ca4d87e0aa56837cf0929bc49f7cf8db3f2ca734e1e50a6b982aadc403dfa3f3d79edfddd68814efca3168cf63f0d3a4c25511b23d0782c824927571b"))
/////////abis///////////////////
const bhc721 = require("../js/abis/bhc_721.json");
const bhc1155 = require("../js/abis/bhc_1155.json");
const nftStorageABI = require("../js/abis/nft_storage.json");
const exchangeABI = require("../js/abis/new_exchange.json");
const bep20ABI = require("./abis/bep20.json");
const minterABI = require("./abis/minter.json");
const factoryABI = require("./abis/factory.json");

var rpcprovider = new ethers.providers.JsonRpcProvider(
    "http://162.0.210.42/rpc"
    //"https://data-seed-prebsc-1-s1.binance.org:8545"
);

async function getCreated(owner) {
    const tokens = [];

    try {
        //removed//console.log("starting");
        const nftStorage = new ethers.Contract(
            NFTStorageAddress,
            nftStorageABI,
            rpcprovider
        );
        //var evts = await nftStorage.queryFilter("NFTAdded", 7090600, "latest");
        var startBlock = 7090600;
        var endBlock = await rpcprovider.getBlockNumber();
        //removed//console.log("catched");

        var evts = [];
        //var filter = nftStorage.filters.NFTAdded({moderator:null,_collection:null,_id:null,_creator:owner,_fee:null})
        var filter = nftStorage.filters.NFTAdded();

        for (var i = startBlock; i <= endBlock; i = i + 40000) {
            var evtsCr = await nftStorage.queryFilter("NFTAdded", i, i + 40000);

            evts = [...evts, ...evtsCr];
            //removed//console.log(evts);
        }
    } catch (e) {
        //removed//console.log(e);
    }

    return tokens;
}

getCreated("0x13930acAd085064bF6Fb5c299812228002E3B604").then(data => {
    //removed//console.log(data);
});
