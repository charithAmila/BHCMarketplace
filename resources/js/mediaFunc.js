import { ethers } from "ethers";
import { toAddress, checkConnection } from "./etherFunc.js";
if (typeof window.ethereum == "undefined") {
    window.provider = new ethers.providers.JsonRpcProvider(
        "https://data-seed-prebsc-1-s1.binance.org:8545"
    );
} else {
    window.provider = new ethers.providers.Web3Provider(window.ethereum);
}

async function checkliked(contract, id) {
    var address = toAddress(checkConnection());

    window.connected_account = address.toLowerCase();
    var res = await axios.get("/like");
    var data = res.data;
    var valObj = data.likes.filter(function(elem) {
        if (
            elem.token_id == id &&
            elem.contract == contract &&
            elem.address == connected_account
        )
            return elem.token_id;
    });
    if (valObj.length > 0) {
        return true;
    }

    return false;
}

async function LikeController(contract, id) {
    const signer = provider.getSigner();
    var address = toAddress(checkConnection());

    window.connected_account = address.toLowerCase();
    if (!address) {
        alert("Please connect your wallet first");
    } else {
        let data = {};
        var signature;
        var signature_onida;
        var return_array = await checkliked(contract, id);
        if (return_array) {
            signature_onida = false;
        } else {
            signature_onida = true;
        }

        if (signature_onida) {
            signature = await signer.signMessage(
                "I would like to like token : " + contract + id
            );
        } else {
            signature = "already signed";
        }
        data.sign = signature;
        data.contract = contract;
        data.token_id = id;
        data.address = address.toLowerCase();

        var output;
        await axios
            .post("/like", data, {})
            .then(function(response) {
                output = response.data;
            })
            .catch(function(error) {});
        return output;
    }
}

async function checkfollowed(user_id, follower_id) {
    var address = toAddress(checkConnection());

    window.connected_account = address.toLowerCase();
    var res = await axios.get("/followers");
    var data = res.data;
    var valObj = data.followers.filter(function(elem) {
        if (elem.user_id == user_id && elem.follower_id == follower_id)
            return elem.user_id;
    });
    if (valObj.length > 0) {
        return true;
    }

    return false;
}

async function FollowController(user_id, follower_id) {
    const signer = provider.getSigner();
    var address = toAddress(checkConnection());

    window.connected_account = address.toLowerCase();
    if (!address) {
        alert("Please connect your wallet first");
    } else {
        let data = {};
        var signature;
        var signature_onida;
        var return_array = await checkfollowed(user_id, follower_id);
        if (return_array) {
            signature_onida = false;
        } else {
            signature_onida = true;
        }

        if (signature_onida) {
            signature = await signer.signMessage(
                "I would like to follow user : " + user_id + follower_id
            );
        } else {
            signature = "already signed";
        }
        data.sign = signature;
        data.user_id = user_id;
        data.follower_id = follower_id;

        var output;
        await axios
            .post("/follow", data, {})
            .then(function(response) {
                output = response.data;
            })
            .catch(function(error) {});
        return output;
    }
}

export { LikeController, FollowController };