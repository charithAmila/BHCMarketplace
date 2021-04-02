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
    julswap_routerAddress
} from "../js/addresses/constants.js";
import { getUserDetails } from "./data.js";
import { ethers } from "ethers";
import {
    toAddress,
    checkConnection,
    buy,
    generateOrderIdMessage
} from "./etherFunc.js";
///////////////////////////////////////////////////ABI//////////////////////////////////////////////////////////
const bhc721 = require("../js/abis/bhc_721.json");
const bhc1155 = require("../js/abis/bhc_1155.json");
const pancake_ABI = require("../js/abis/pancake.json");
const token_ABI = require("../js/abis/bep20.json");
const julswap_ABI = require("../js/abis/julswap.json");
const WBNB_ABI = require("../js/abis/wbnb.json");
//////////////////////////////////////////Get Token Contract////////////////////////////////////////////////////////
function getTokenContract(bidding_token) {
    const signer = provider.getSigner();
    var token_contract;
    switch (bidding_token) {
        case "HPS":
            token_contract = new ethers.Contract(hpsAddress, token_ABI, signer);
            break;
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
async function getHpsBalance() {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    var address = toAddress(checkConnection());
    const hpsContract = new ethers.Contract(hpsAddress, token_ABI, provider);
    const balance = await hpsContract.balanceOf(address);
    return balance / 10 ** 18;
}
////////////////////////////////////////////Get User from User Id///////////////////////////////////////////////////
async function getBNBBalance() {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    var address = toAddress(checkConnection());
    const balance = await provider.getBalance(address);
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
        case "CAD":
            token_address = hpsAddress;
            break;
        case "ANKR":
            token_address = ANKR_token;
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
    var address = toAddress(checkConnection());
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

    var address = toAddress(checkConnection());
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
async function bid(owner, contract_address, token_id, bidding_token, amount) {
    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const signer = provider.getSigner();
    const WBNB_Write = new ethers.Contract(WBNB_tokenAddress, WBNB_ABI, signer);
    const WBNB_Write_Test = new ethers.Contract(
        "0xae13d989dac2f0debff460ac112a837c89baa7cd",
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
        contract_address,
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
    let rate = 1.025;
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

            await axios
                .post("/bid", data, {})
                .then(function(response) {
                    return response.data;
                })
                .catch(function(error) {});
        }
    }
    //***********************************Token Payment*********************************//
    else {
        let token_contract = getTokenContract(bidding_token);
        const balance = await token_contract.balanceOf(address);
        var address = address.toString().toLowerCase();
        if (balance > amount * 1.2) {
            const txResponse = await token_contract.approve(
                TEST_token,
                (amount * 1.2).toString()
            );
            const txReceipt = await txResponse.wait();
            if (txReceipt["status"] == 1) {
                const signature = await signer.signMessage();
                data.signature = signature;
                await axios
                    .post("/bid", data, {})
                    .then(function(response) {
                        return response.data;
                    })
                    .catch(function(error) {});
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
    var res = await buy(
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
    );
    console.log(res);
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
    var maxAmount = 0;
    var res = {};
    var maxBidder;
    var maxBidToken;
    var maxBidSig;
    var maxBidTime;
    var maxBidMessage;
    var maxBidSalt;
    if (output != []) {
        for (var i = 0; i < output.length; i++) {
            // var price = await getTokenPrice(output[i].bidding_token);
            var price = 10;
            if (price * output[i].bidding_amount > maxAmount) {
                maxAmount = output[i].bidding_amount;
                maxBidder = output[i].bidding_address;
                maxBidToken = output[i].bidding_token;
                maxBidSig = output[i].signature;
                maxBidTime = output[i].created_at;
                maxBidMessage = output[i].message;
                maxBidSalt = output[i].salt;
            }
        }

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
        return res;
    }
    return false;
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
/////////////////////////////////////////////Finish Bidding///////////////////////////////////////////////////////
async function finishBidding(token_id) {}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

export {
    toAddress,
    bid,
    startBidding,
    getHighestBid,
    getBiddingStatus,
    getAllBids,
    endBidding,
    getHpsBalance,
    getBNBBalance,
    getConnectedAddress,
    acceptBid
};

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
