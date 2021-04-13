///////////////////////////////////////////////////////////////////////////////////////////////////////////////
import {
    WBNB_tokenAddress,
    hps721Address,
    hps1155Address,
    hpsAddress,
    bhcAddress,
    erc20TransferProxyAddress,
    pancake_routerAddress,
    CAD_tokenAddress,
    ANKR_tokenAddress,
    BELL_tokenAddress,
    julswap_routerAddress,
    exchangeAddress
} from "../js/addresses/constants.js";
import { getUserDetails } from "./data.js";
import { ethers } from "ethers";
import {
    toAddress,
    checkConnection,
    buy,
    generateOrderIdMessage,
    approveNFT,
    approveTokens
} from "./etherFunc.js";
///////////////////////////////////////////////////ABI//////////////////////////////////////////////////////////
const bhc721 = require("../js/abis/bhc_721.json");
const bhc1155 = require("../js/abis/bhc_1155.json");
const pancake_ABI = require("../js/abis/pancake.json");
const token_ABI = require("../js/abis/bep20.json");
const julswap_ABI = require("../js/abis/julswap.json");
const WBNB_ABI = require("../js/abis/wbnb.json");
const exchange_abi = require("../js/abis/new_exchange.json");
//////////////////////////////////////////Get Token Contract////////////////////////////////////////////////////////
function getTokenContract(bidding_token) {
    const signer = provider.getSigner();
    var token_contract;
    switch (bidding_token) {
        case "HPS":
            token_contract = new ethers.Contract(hpsAddress, token_ABI, signer);
            break;
            b;
        case "ANKR":
            token_contract = new ethers.Contract(ANKR_token, token_ABI, signer);
            break;
        case "BELL":
            token_contract = new ethers.Contract(BELL_token, token_ABI, signer);
        case "TEST":
            token_contract = new ethers.Contract(TEST_token, token_ABI, signer);
            break;
        default:
            token_contract = "Not in list";
    }
    return token_contract;
}
////////////////////////////////////////////Get HPS Balance////////////////////////////////////////////////////////
async function getBHCBalance() {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    var address = toAddress(window.ethereum.selectedAddress);
    const hpsContract = new ethers.Contract(bhcAddress, token_ABI, provider);
    const balance = await hpsContract.balanceOf(address);
    console.log("BhC balance");
    console.log(address);
    console.log(balance.toString());
    return parseFloat(balance.toString()) / 10 ** 18;
}
////////////////////////////////////////////Get BNB Balance///////////////////////////////////////////////////////////
async function getBNBBalance() {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    var address = toAddress(window.ethereum.selectedAddress);
    const balance = await provider.getBalance(address);
    return parseFloat(balance.toString()) / 10 ** 18;
}
////////////////////////////////////////////Get WBNB Balance////////////////////////////////////////////////////////
async function getWBNBBalance() {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    var address = toAddress(window.ethereum.selectedAddress);
    const WBNB_Contract = new ethers.Contract(
        WBNB_tokenAddress,
        WBNB_ABI,
        provider
    );
    const balance = await WBNB_Contract.balanceOf(address);
    return parseFloat(balance.toString()) / 10 ** 18;
}
////////////////////////////////////////////Get connected address///////////////////////////////////////////////////
async function getConnectedAddress() {
    var address = toAddress(checkConnection());
    return address;
}
///////////////////////////////////////////Get Token Address////////////////////////////////////////////////////////
function getTokenAddress(bidding_token) {
    var token_address;
    switch (bidding_token) {
        case "HPS":
            token_address = hpsAddress;
            break;
        case "BHC":
            token_address = bhcAddress;
            break;
        case "BELL":
            token_address = BELL_token;
            break;
        case "TEST":
            token_address = ANKR_token;
            break;
        default:
            token_address = "Not in list";
    }
    return token_address;
}
///////////////////////////////////////////Get Token Price//////////////////////////////////////////////////////////
async function getTokenPrice(bidding_token) {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const julswap_router = ethers.utils.getAddress(julswap_routerAddress);
    const julswap_Read = new ethers.Contract(
        julswap_router,
        julswap_ABI,
        provider
    );

    const contract = getTokenAddress(bidding_token);
    const price = await julswap_Read.getAmountsIn(
        ethers.BigNumber.from("1000000000000000000"),
        [WBNB_tokenAddress, contract]
    );
    return parseInt(price[0].toString());
}
///////////////////////////////////////////Start Bidding////////////////////////////////////////////////////////////
async function startBidding(_owner, contract_address, token_id) {
    const signer = provider.getSigner();
    let data = {};
    var res = {};
    data.contract_address = contract_address;
    data.token_id = token_id;
    data.bidding_status = true;
    //const contract = ERC721_Website_Read;
    // const owner = await contract.ownerOf(token_id);
    var address = toAddress(window.ethereum.selectedAddress);
    let txReceipt = await approveNFT(contract_address);
    let txResponse = txReceipt.wait();
    console.log(txResponse);
    console.log("Ownership");
    console.log(address == _owner);
    if (address == _owner) {
        const signature = await signer.signMessage("Allow bidding for token");
        data.signature = signature;
        data.address = address.toLowerCase();
        await axios
            .post("/startBid", data, {})
            .then(function(response) {
                res = response.data;
            })
            .catch(function(error) {});
        return res;
    } else {
        return "Not owner";
    }
}

///////////////////////////////////////////End Bidding//////////////////////////////////////////////////////////////
async function endBidding(_owner, contract_address, token_id) {
    let data = {};
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const signer = provider.getSigner();
    //const owner = await contract.ownerOf(token_id);

    var address = toAddress(window.ethereum.selectedAddress);
    if (address == _owner) {
        const signature = await signer.signMessage("Stop bidding for token");
        data.contract_address = contract_address;
        data.token_id = token_id;
        data.signature = signature;
        data.address = address.toLowerCase();
        var res = {};
        await axios
            .post("/endBid", data, {})
            .then(function(response) {
                res = response.data;
            })
            .catch(function(error) {});
        return res;
    } else {
        return "Not owner";
    }
}

//////////////////////////////////////////////Bid////////////////////////////////////////////////////////////////////
/*******************************************Apprrove BHC************************************************************/
async function approveBHC(_amount) {
    let amount = parseFloat(_amount) * 10 ** 18;
    let rate = 1;
    const signer = provider.getSigner();
    const hpsContract = new ethers.Contract(hpsAddress, token_ABI, signer);
    var address = toAddress(window.ethereum.selectedAddress); //Get collected wallet address
    const balance = await hpsContract.balanceOf(address);
    var address = address.toString().toLowerCase();
    const txResponse = await hpsContract.approve(
        erc20TransferProxyAddress,
        (amount * rate).toString()
    );
    const txReceipt = await txResponse.wait();
    if (txReceipt.status == 1) {
        return true;
    } else {
        return false;
    }
}
/*****************************************Approve WBNB**************************************************************/
async function approveWBNB(_amount) {
    const signer = provider.getSigner();
    let amount = parseFloat(_amount) * 10 ** 18;
    let rate = 1;
    const WBNB_Write_Test = new ethers.Contract(
        WBNB_tokenAddress,
        WBNB_ABI,
        signer
    );
    const res = await WBNB_Write_Test.approve(
        erc20TransferProxyAddress,
        (amount * rate).toString()
    );
    const output = await res.wait();
    if (output.status == 1) {
        return true;
    } else {
        return false;
    }
}
/*******************************************Convert BNB to WBNB*****************************************************/
async function convertBNBtoWBNB(_amount) {
    const signer = provider.getSigner();
    let amount = parseFloat(_amount) * 10 ** 18;
    let rate = 1;
    let overrides = {
        value: ethers.utils.parseEther(((amount * rate) / 10 ** 18).toString())
    };
    const WBNB_Write_Test = new ethers.Contract(
        WBNB_tokenAddress,
        WBNB_ABI,
        signer
    );
    const res = await WBNB_Write_Test.deposit(overrides);
    const out = await res.wait();
    if (out.status == 1) {
        return true;
    } else {
        return false;
    }
}
/*****************************************Sign Bidding**************************************************************/
async function signBid(
    owner,
    contract_address,
    token_id,
    bidding_token,
    amount
) {
    const salt = Math.random()
        .toString(36)
        .substring(7);
    var address = toAddress(checkConnection());
    let orderId = await generateOrderIdMessage(
        contract_address,
        token_id,
        1,
        bidding_token,
        amount.toString(),
        salt
    );
    const signer = provider.getSigner();

    let data = {};
    data.owner = owner;
    data.bidding_address = address;
    data.contract_address = contract_address;
    data.token_id = token_id;
    data.bidding_token = bidding_token;
    data.bidding_amount = amount;
    data.message = orderId;
    const signature = await signer.signMessage(orderId);
    data.salt = salt;
    data.signature = signature;
    let res;
    await axios
        .post("/bid", data, {})
        .then(function(response) {
            res = response.data;
        })
        .catch(function(error) {});
    return res;
}
/*******************************************************************************************************************/
async function bid(owner, contract_address, token_id, bidding_token, amount) {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const signer = provider.getSigner();
    const WBNB_Write = new ethers.Contract(WBNB_tokenAddress, WBNB_ABI, signer);
    const WBNB_Write_Test = new ethers.Contract(
        WBNB_tokenAddress,
        WBNB_ABI,
        signer
    );
    var salt = Math.random()
        .toString(36)
        .substring(7);
    console.log(token_id);
    console.log(contract_address);
    let orderId = await generateOrderIdMessage(
        contract_address,
        token_id,
        1,
        bidding_token,
        (amount * 10 ** 18).toString(),
        salt
    );
    var status = await getBiddingStatus(owner, contract_address, token_id);
    if (status == 0) {
        return "Not open for bidding";
    }
    var address = toAddress(checkConnection()); //Get collected wallet address
    let success = false;
    let data = {};
    data.owner = owner;
    data.bidding_address = address;
    data.contract_address = contract_address;
    data.token_id = token_id;
    data.bidding_token = bidding_token;
    data.bidding_amount = amount;
    data.message = orderId;
    data.salt = salt;

    amount = parseFloat(amount) * 10 ** 18;
    let rate = 1;
    //*******************************************BNB payment*************************************//

    if (bidding_token == "BNB") {
        const balance = await provider.getBalance(address);
        const WBNB_balance = await WBNB_Write_Test.balanceOf(address);

        if (balance > amount * rate || WBNB_balance > amount * rate) {
            if (WBNB_balance > amount * rate) {
                console.log("WBNB");
                const res = await WBNB_Write_Test.approve(
                    erc20TransferProxyAddress,
                    (amount * rate).toString()
                );
                const output = await res.wait();
                if (output.status == 1) {
                    success = true;
                }
            } else {
                let overrides = {
                    value: ethers.utils.parseEther(
                        ((amount * rate) / 10 ** 18).toString()
                    )
                };
                const res = await WBNB_Write_Test.deposit(overrides);
                const out = await res.wait();
                const txResponse = await WBNB_Write_Test.approve(
                    erc20TransferProxyAddress,
                    (amount * rate).toString()
                );
                const txReceipt = await txResponse.wait();
                console.log(txReceipt);
                if (txReceipt.status == 1) {
                    success = true;
                }
            }
        } else {
            return "Not enough balance";
        }

        if (success == true) {
            data.bidding_address = address.toLowerCase();
            const signature = await signer.signMessage(orderId);
            data.signature = signature;
            let res;
            await axios
                .post("/bid", data, {})
                .then(function(response) {
                    res = response.data;
                })
                .catch(function(error) {});
            return res;
        }
    }
    //***********************************Token Payment*********************************//
    else {
        console.log("HPS");
        const signer = provider.getSigner();
        const hpsContract = new ethers.Contract(hpsAddress, token_ABI, signer);
        //let token_contract = getTokenContract(bidding_token);
        const balance = await hpsContract.balanceOf(address);
        var address = address.toString().toLowerCase();
        if (balance > amount * 1.2) {
            console.log("Enough HPS");
            const txResponse = await hpsContract.approve(
                exchangeAddress,
                (amount * 1.2).toString()
            );
            const txReceipt = await txResponse.wait();
            if (txReceipt["status"] == 1) {
                const signature = await signer.signMessage(orderId);
                data.signature = signature;
                let res;
                await axios
                    .post("/bid", data, {})
                    .then(function(response) {
                        res = response.data;
                    })
                    .catch(function(error) {});
                return res;
            } else {
                return "Failed";
            }
        } else {
            return "Not enough balance";
        }
        //}
    }
}

////////////////////////////////////////////Accept Bid///////////////////////////////////////////////////////////////
async function acceptBid(
    is721,
    token,
    tokenId,
    priceToken,
    price,
    winner,
    signature,
    salt
) {
    const signer = provider.getSigner();
    const exchange = new ethers.Contract(exchangeAddress, exchange_abi, signer);
    const sig = ethers.utils.splitSignature(signature);
    console.log([
        is721,
        token,
        tokenId,
        priceToken,
        price,
        winner,
        signature,
        salt
    ]);
    const tx = await exchange.endAuction(
        [
            is721,
            toAddress(token),
            tokenId,
            toAddress(priceToken),
            price.toString(),
            toAddress(winner),
            salt,
            sig.v,
            sig.r,
            sig.s
        ],
        {
            gasLimit: "3000000",
            value:
                priceToken == toAddress("")
                    ? ethers.utils.parseEther(`${price}`)
                    : "0"
        }
    );

    var res = await tx.wait();
    return res.status;
}
////////////////////////////////////////////Get Time Difference//////////////////////////////////////////////////////
function timeDifference(date1, date2) {
    var difference = date1.getTime() - date2.getTime();

    var daysDifference = Math.floor(difference / 1000 / 60 / 60 / 24);
    difference -= daysDifference * 1000 * 60 * 60 * 24;

    var hoursDifference = Math.floor(difference / 1000 / 60 / 60);
    difference -= hoursDifference * 1000 * 60 * 60;

    var minutesDifference = Math.floor(difference / 1000 / 60);
    difference -= minutesDifference * 1000 * 60;

    var secondsDifference = Math.floor(difference / 1000);

    console.log(
        "difference = " +
            daysDifference +
            " day/s " +
            hoursDifference +
            " hour/s " +
            minutesDifference +
            " minute/s " +
            secondsDifference +
            " second/s "
    );
}
////////////////////////////////////////////Get All Bids/////////////////////////////////////////////////////////////
async function getAllBids(owner, contract_address, token_id) {
    var data = {};
    data.contract_address = contract_address;
    data.owner = owner;
    data.token_id = token_id;
    var output = {};
    await axios.post("/getAllBids", data, {}).then(function(response) {
        output = response.data;
    });
    for (var i = 0; i < output.length; i++) {
        output[i].proPic = await (
            await getUserDetails(output[i].bidding_address)
        ).display_photo;
    }
    return output;
}
////////////////////////////////////////////Get Highest Bid//////////////////////////////////////////////////////////
async function getHighestBid(owner, contract_address, token_id) {
    var output = await getAllBids(owner, contract_address, token_id);
    var address = toAddress(window.ethereum.selectedAddress);
    var maxAmount = 0;
    var res = {};
    var maxBidder;
    var maxBidToken;
    var maxBidSig;
    var maxBidTime;
    var maxBidMessage;
    var maxBidSalt;

    if (output.length != 0) {
        for (var i = 0; i < output.length; i++) {
            //var hpsprice = await getTokenPrice(output[i].bidding_token);
            //console.log(hpsprice);
            var hpsprice = 1;

            if (
                output[i].bidding_token ==
                "0x6fd7c98458a943f469E1Cf4eA85B173f5Cd342F4"
            ) {
                console.log("Token is BHC");
                const signer = provider.getSigner();
                const hpsContract = new ethers.Contract(
                    hpsAddress,
                    token_ABI,
                    signer
                );
                /*const balance = await hpsContract.balanceOf(
                    output[i].bidding_address
                );*/
                const balance = 1000000000000000000;
                if (balance > output[i].bidding_amount) {
                    if (hpsprice * output[i].bidding_amount > maxAmount) {
                        maxAmount = output[i].bidding_amount;
                        maxBidder = output[i].bidding_address;
                        maxBidToken = "BHC";
                        maxBidSig = output[i].signature;
                        maxBidTime = output[i].created_at;
                        maxBidMessage = output[i].message;
                        maxBidSalt = output[i].salt;
                    }
                }
            } else {
                console.log("Token is BNB");
                const signer = provider.getSigner();
                //const WBNB_Write = new ethers.Contract(WBNB_tokenAddress, WBNB_ABI, signer);
                const WBNB_Write_Test = new ethers.Contract(
                    WBNB_tokenAddress,
                    WBNB_ABI,
                    signer
                );
                /*  const WBNB_balance = await WBNB_Write_Test.balanceOf(
                    output[i].bidding_address
                );*/
                const WBNB_balance = 100000000000000000000;
                if (WBNB_balance > output[i].bidding_amount) {
                    if (1 * output[i].bidding_amount > maxAmount) {
                        maxAmount = output[i].bidding_amount;
                        maxBidder = output[i].bidding_address;
                        maxBidToken = "BNB";
                        maxBidSig = output[i].signature;
                        maxBidTime = output[i].created_at;
                        maxBidMessage = output[i].message;
                        maxBidSalt = output[i].salt;
                    }
                }
            }
        }
        let highestBidProPic = await (await getUserDetails(maxBidder))
            .display_photo;
        console.log(highestBidProPic);
        res.proPic = await (await getUserDetails(maxBidder)).display_photo;
        res.maxBidToken = maxBidToken;
        res.maxAmount = maxAmount * 10 ** 18;
        res.maxBidder = maxBidder;
        res.maxBidSig = maxBidSig;
        res.maxBidTime = maxBidTime;
        res.maxBidMessage = maxBidMessage;
        res.maxBidSalt = maxBidSalt;

        /*res.maxBidToken = "0xE19DD2fa7d332E593aaf2BBe4386844469e51937";
        res.maxAmount = "1";
        res.maxBidder = output[0].user_id;
        res.maxBidSig = output[0].signature;*/
        console.log("Results");
        console.log(res);
        return res;
    } else {
        return false;
    }
}

///////////////////////////////////////////Get Bidding Status//////////////////////////////////////////////////////
async function getBiddingStatus(owner, contract_address, token_id) {
    var data = {};
    data.contract_address = contract_address;
    data.owner = owner;
    data.token_id = token_id;
    var output = {};

    await axios.post("/getBiddingStatus", data, {}).then(function(response) {
        output = response.data;
    });
    return output;
}

///////////////////////////////////////////Get Bidding Status All ////////////////////////////////////////////////
async function getBiddingStatusAll(collectibles) {
    for (collectible in collectibles) {
        var data = {};
        data.contract_address = collectible.contract;
        data.owner = collectible.owner_id;
        data.token_id = collectible.id;
        var output = {};
        await axios
            .post("/getBiddingStatus", data, {})
            .then(function(response) {
                collectible.bidding_status = response.data;
            });
        return collectibles;
    }
}
/////////////////////////////////////////////Finish Bidding///////////////////////////////////////////////////////
async function finishBidding(token_id) {}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

export {
    toAddress,
    bid,
    startBidding,
    getHighestBid,
    getBiddingStatus,
    getBiddingStatusAll,
    getAllBids,
    endBidding,
    getBHCBalance,
    getBNBBalance,
    getWBNBBalance,
    getConnectedAddress,
    acceptBid,
    approveBHC,
    signBid,
    approveWBNB,
    convertBNBtoWBNB
};

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
